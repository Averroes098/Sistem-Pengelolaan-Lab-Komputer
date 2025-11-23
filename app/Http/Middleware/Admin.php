<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // Cek apakah level adalah admin
        $user = Auth::user();
        if ($user && $user->level === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, arahkan ke dashboard
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}
