<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login tapi profil belum lengkap
        if (auth()->check() && !auth()->user()->is_profile_complete) {
            // Jika sudah di halaman profile completion, lanjutkan
            if ($request->routeIs('profile.edit', 'profile.update')) {
                return $next($request);
            }

            // Redirect ke profile completion page dengan notifikasi
            return redirect()
                ->route('profile.edit')
                ->with('warning', 'Silakan lengkapi data profil Anda terlebih dahulu untuk mengakses fitur lainnya.');
        }

        return $next($request);
    }
}
