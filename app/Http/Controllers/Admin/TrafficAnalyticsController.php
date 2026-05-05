<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrafficAnalytics;
use App\Models\TrafficEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TrafficAnalyticsController extends Controller
{
    private function getDateLimit($request)
    {
        $days = $request->get('days', 7);
        return [now()->subDays($days), $days];
    }

    public function index(Request $request)
    {
        [$dateLimit, $days] = $this->getDateLimit($request);

        // Pre-fill last 24 hours to ensure chart is always visible
        $flowData = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i)->format('H:00');
            $flowData[$hour] = 0;
        }

        $dbFlow = TrafficAnalytics::where('created_at', '>=', now()->subHours(24))
            ->select(DB::raw('DATE_FORMAT(created_at, "%H:00") as hour'), DB::raw('count(*) as hits'))
            ->groupBy('hour')
            ->get();

        foreach ($dbFlow as $item) {
            $flowData[$item->hour] = $item->hits;
        }

        $stats = [
            'total_hits' => TrafficAnalytics::where('created_at', '>=', $dateLimit)->count(),
            'unique_visitors' => TrafficAnalytics::where('created_at', '>=', $dateLimit)->distinct('session_id')->count(),
            'avg_response_time' => round(TrafficAnalytics::where('created_at', '>=', $dateLimit)->avg('response_time') ?? 0),
            'total_bandwidth' => TrafficAnalytics::where('created_at', '>=', $dateLimit)->sum('response_size'),
            
            'traffic_flow_labels' => array_keys($flowData),
            'traffic_flow_values' => array_values($flowData),

            'status_codes' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select(DB::raw('COALESCE(status_code, 200) as status_code'), DB::raw('count(*) as count'))
                ->groupBy('status_code')
                ->get(),

            'top_pages' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select('path', DB::raw('count(*) as hits'), DB::raw('count(distinct session_id) as visitors'))
                ->groupBy('path')
                ->orderBy('hits', 'desc')
                ->limit(10)
                ->get(),
            
            'recent_visits' => TrafficAnalytics::orderBy('created_at', 'desc')->paginate(15)->withQueryString(),
            'catalog_downloads' => TrafficEvent::where('event_type', 'download')->where('created_at', '>=', $dateLimit)->count(),
        ];

        return view('admin.traffic.index', compact('stats', 'days'));
    }

    public function audience(Request $request)
    {
        [$dateLimit, $days] = $this->getDateLimit($request);

        $stats = [
            'browsers' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select(DB::raw('COALESCE(browser, "Unknown") as name'), DB::raw('count(*) as count'))
                ->groupBy('name')->orderBy('count', 'desc')->get(),
            'os' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select(DB::raw('COALESCE(os, "Unknown") as name'), DB::raw('count(*) as count'))
                ->groupBy('name')->orderBy('count', 'desc')->get(),
            'devices' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select(DB::raw('COALESCE(device_type, "Desktop") as name'), DB::raw('count(*) as count'))
                ->groupBy('name')->orderBy('count', 'desc')->get(),
            'bandwidth_by_device' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select(DB::raw('COALESCE(device_type, "Desktop") as name'), DB::raw('sum(response_size) as size'))
                ->groupBy('name')->orderBy('size', 'desc')->get(),
        ];

        return view('admin.traffic.audience', compact('stats', 'days'));
    }

    public function geo(Request $request)
    {
        [$dateLimit, $days] = $this->getDateLimit($request);

        $stats = [
            'countries' => TrafficAnalytics::where('created_at', '>=', $dateLimit)
                ->select('country_code', DB::raw('count(*) as count'))
                ->whereNotNull('country_code')
                ->groupBy('country_code')->orderBy('count', 'desc')->get(),
        ];

        return view('admin.traffic.geo', compact('stats', 'days'));
    }

    public function behavior(Request $request)
    {
        [$dateLimit, $days] = $this->getDateLimit($request);

        $stats = [
            'avg_scroll' => round(TrafficAnalytics::where('created_at', '>=', $dateLimit)->avg('scroll_depth') ?? 0),
            'top_clicks' => TrafficEvent::where('event_type', 'click')
                ->where('created_at', '>=', $dateLimit)
                ->select('element_text', 'element_id', 'page_path', DB::raw('count(*) as count'))
                ->groupBy('element_text', 'element_id', 'page_path')
                ->orderBy('count', 'desc')->limit(20)->get(),
            'bounce_rate' => $this->calculateBounceRate($dateLimit),
            'catalog_downloads' => TrafficEvent::where('event_type', 'download')
                ->where('created_at', '>=', $dateLimit)
                ->select('element_text as name', DB::raw('count(*) as count'))
                ->groupBy('name')
                ->orderBy('count', 'desc')->get(),
        ];

        return view('admin.traffic.behavior', compact('stats', 'days'));
    }

    private function calculateBounceRate($dateLimit)
    {
        $totalSessions = TrafficAnalytics::where('created_at', '>=', $dateLimit)
            ->distinct('session_id')->count();
        
        if ($totalSessions == 0) return 0;

        $singlePageSessions = TrafficAnalytics::where('created_at', '>=', $dateLimit)
            ->select('session_id', DB::raw('count(*) as count'))
            ->groupBy('session_id')
            ->having('count', '=', 1)
            ->get()->count();

        return round(($singlePageSessions / $totalSessions) * 100, 1);
    }

    public function trackEvent(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|string',
            'element_tag' => 'nullable|string',
            'element_id' => 'nullable|string',
            'element_text' => 'nullable|string',
            'page_path' => 'nullable|string',
            'scroll_depth' => 'nullable|integer',
        ]);

        $sessionId = Session::getId();

        if ($validated['event_type'] === 'scroll') {
            // Update the latest traffic entry for this session/path
            $latest = TrafficAnalytics::where('session_id', $sessionId)
                ->where('path', $validated['page_path'])
                ->orderBy('created_at', 'desc')->first();
            
            if ($latest && $validated['scroll_depth'] > $latest->scroll_depth) {
                $latest->update(['scroll_depth' => $validated['scroll_depth']]);
            }
            return response()->json(['status' => 'success']);
        }

        // For clicks, we create a new event record
        $latestTraffic = TrafficAnalytics::where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')->first();

        TrafficEvent::create([
            'traffic_id' => $latestTraffic ? $latestTraffic->id : null,
            'session_id' => $sessionId,
            'event_type' => $validated['event_type'],
            'element_tag' => $validated['element_tag'],
            'element_id' => $validated['element_id'],
            'element_text' => $validated['element_text'],
            'page_path' => $validated['page_path'],
        ]);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        TrafficAnalytics::destroy($id);
        return back()->with('success', 'Data traffic berhasil dihapus.');
    }
    
    public function purge(Request $request)
    {
        $days = $request->get('days');
        if ($days) {
            TrafficAnalytics::where('created_at', '<', now()->subDays($days))->delete();
            return back()->with('success', "Data traffic lebih dari $days hari berhasil dibersihkan.");
        }
        TrafficAnalytics::truncate();
        TrafficEvent::truncate();
        return back()->with('success', 'Semua data traffic berhasil dibersihkan.');
    }
}
