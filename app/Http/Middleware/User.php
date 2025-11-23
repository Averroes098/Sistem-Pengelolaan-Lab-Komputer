<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class User
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // Cek apakah level adalah user
        $user = Auth::user();
        if ($user && $user->level === 'user') {
            return $next($request);
        }

        // Jika bukan user, arahkan ke dashboard
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman user.');
    }
}