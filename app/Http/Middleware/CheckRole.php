<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|int  ...$levels
     */
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check if the user's role level is in the allowed levels
        $userLevel = Auth::user()->role->level;

        if (in_array($userLevel, $levels)) {
            return $next($request);
        }

        // If not authorized, return 403 Forbidden
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
