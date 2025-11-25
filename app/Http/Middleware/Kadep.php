<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Kadep
{
public function handle($request, Closure $next)
{
    // Cek apakah user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
    }

    // Cek apakah level adalah kadep
    $user = Auth::user();
    if ($user && $user->level === 'kadep') {
        return $next($request);
    }

    // Jika bukan kadep, arahkan ke dashboard masing-masing level
    if ($user->level === 'admin') {
        return redirect()->route('admin.dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    } elseif ($user->level === 'staf') {
        return redirect()->route('staf.dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    } elseif ($user->level === 'user') {
        return redirect()->route('dashboard.user')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    // Default fallback
    return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
}
}
