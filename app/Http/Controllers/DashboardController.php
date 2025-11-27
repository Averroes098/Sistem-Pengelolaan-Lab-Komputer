<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // Arahkan pengguna berdasarkan level/role
        if ($user->level === 'staf') {
            return redirect()->route('staf.dashboard');
        }

        if ($user->level === 'kadep') { // Sebaiknya gunakan elseif
            return redirect()->route('kadep.dashboard');
        } else { // Dashboard default untuk role lain (misal: mahasiswa)
            $labs = Laboratorium::all();
            $riwayat = Peminjaman::where('user_id', $user->id)
                ->with(['laboratorium', 'alat', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('user.index', [
                'available_lab' => $labs,
                'riwayat_peminjaman' => $riwayat,
            ]);
        }
    }
}
