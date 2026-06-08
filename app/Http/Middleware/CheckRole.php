<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:superadmin,admin')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = auth()->user()->role ?? null;

        if (!$userRole || !in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
