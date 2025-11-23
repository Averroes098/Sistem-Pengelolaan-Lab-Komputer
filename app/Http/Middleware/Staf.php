<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Staf
{
    public function handle($request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // Cek apakah level adalah staf
        $user = Auth::user();
        if ($user && $user->level === 'staf') {
            return $next($request);
        }

        // Jika bukan staf, arahkan ke dashboard
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman staf.');
    }
}
