<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TrafficAnalytics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LogTraffic
{
    public function handle(Request $request, Closure $next)
    {
        $request->attributes->set('start_time', microtime(true));
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        $path = $request->path();
        
        if ($request->isMethod('GET') && 
            !$request->expectsJson() && 
            !str_starts_with($path, 'admin') && 
            !str_starts_with($path, 'api') && 
            !str_contains($path, '.')) {
            
            try {
                $duration = round((microtime(true) - $request->attributes->get('start_time')) * 1000);
                $size = strlen($response->getContent());
                $ip = $request->ip();
                $ua = $request->userAgent();

                // GeoIP
                $countryCode = Cache::remember('geoip_' . $ip, 86400, function() use ($ip) {
                    try {
                        if ($ip === '127.0.0.1' || $ip === '::1') return 'ID';
                        $data = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=status,countryCode"));
                        return ($data && $data->status === 'success') ? $data->countryCode : null;
                    } catch (\Exception $e) { return null; }
                });

                $parsedUA = $this->parseUA($ua);

                TrafficAnalytics::create([
                    'url' => $request->fullUrl(),
                    'path' => '/' . $path,
                    'method' => $request->method(),
                    'status_code' => $response->getStatusCode(),
                    'response_time' => $duration,
                    'response_size' => $size,
                    'ip_address' => $ip,
                    'country_code' => $countryCode,
                    'user_agent' => $ua,
                    'os' => $parsedUA['os'],
                    'browser' => $parsedUA['browser'],
                    'device_type' => $parsedUA['device'],
                    'referer' => $request->headers->get('referer'),
                    'user_id' => Auth::id(),
                    'session_id' => Session::getId(),
                ]);
            } catch (\Exception $e) {
                Log::error('Traffic Logging Error: ' . $e->getMessage());
            }
        }
    }

    private function parseUA($ua)
    {
        $os = 'Unknown';
        $browser = 'Other';
        $device = 'Desktop';

        if (preg_match('/iphone/i', $ua)) { $os = 'iOS'; $device = 'Mobile'; }
        elseif (preg_match('/android/i', $ua)) { $os = 'Android'; $device = 'Mobile'; }
        elseif (preg_match('/windows/i', $ua)) { $os = 'Windows'; }
        elseif (preg_match('/macintosh/i', $ua)) { $os = 'macOS'; }
        elseif (preg_match('/linux/i', $ua)) { $os = 'Linux'; }

        if (preg_match('/chrome/i', $ua)) { $browser = 'Chrome'; }
        elseif (preg_match('/firefox/i', $ua)) { $browser = 'Firefox'; }
        elseif (preg_match('/safari/i', $ua)) { $browser = 'Safari'; }
        elseif (preg_match('/edge/i', $ua)) { $browser = 'Edge'; }
        elseif (preg_match('/msie|trident/i', $ua)) { $browser = 'IE'; }

        if (preg_match('/mobile|phone|tablet/i', $ua)) { $device = 'Mobile'; }

        return ['os' => $os, 'browser' => $browser, 'device' => $device];
    }
}
