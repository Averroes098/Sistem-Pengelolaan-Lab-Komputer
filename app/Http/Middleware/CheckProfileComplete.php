<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        /** @var User|null $user */
        /** @phpstan-ignore-next-line */
        $user = Auth::user();
        /** @phpstan-ignore-next-line */
        if (Auth::check() && $user && !$user->is_profile_complete) {
            // Jika sudah di halaman profile completion, lanjutkan
            if ($request->routeIs(['profile.edit', 'profile.update'])) {
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
