<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // REKOMENDASI:
        // Kita hanya mengecek 'user' karena di database migration Anda 
        // (2025_11_20_000000_update_user_level_enum.php)
        // kolom level didefinisikan sebagai ENUM('admin', 'staf', 'user').
        // Menggunakan 'mahasiswa' di sini akan membuat kode tidak konsisten dengan database.
        
        if (Auth::check() && Auth::user()->level === 'user') {
            return $next($request);
        }

        // Jika user mencoba masuk tapi bukan role 'user' (misal admin iseng akses link user)
        abort(403, 'Akses ditolak. Halaman ini khusus untuk User (Mahasiswa/Dosen).');
    }
}