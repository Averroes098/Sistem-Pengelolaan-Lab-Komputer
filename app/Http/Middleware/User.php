<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class User
{
    public function handle($request, Closure $next)
    {
        // Accept both legacy 'mahasiswa' and modern 'user' roles for compatibility
        if (Auth::check() && in_array(Auth::user()->level, ['user', 'mahasiswa'])) {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}
