<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Staf
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->level === 'staf') {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}
