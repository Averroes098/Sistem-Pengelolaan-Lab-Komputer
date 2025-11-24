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

        // --- ADMIN ---
        if ($user->level === 'admin') {
            $data_lab = Laboratorium::paginate(3);
            $data_peminjaman = Peminjaman::with(['user', 'laboratorium', 'alat'])
                ->latest()
                ->paginate(3);

            return view('admin.dashboard', [
                'data_lab' => $data_lab,
                'data_peminjaman' => $data_peminjaman,
                'user' => $user,
            ]);
        }

        // --- STAF ---
        elseif ($user->level === 'staf') {
            return $this->stafDashboard();
        }

        // --- KADEP (LOGIKA BARU DITAMBAHKAN) ---
        elseif ($user->level === 'kadep') {
            // Menghitung data statistik untuk Kadep
            $totalPeminjaman = Peminjaman::count();
            $alatRusak = Alat::whereIn('kondisi', ['Rusak', 'Perbaikan'])->count();
            
            // Pastikan Anda membuat view: resources/views/kadep/dashboard.blade.php
            return view('kadep.dashboard', compact('totalPeminjaman', 'alatRusak')); 
        }

        // --- USER / MAHASISWA ---
        else {
            $labs = Laboratorium::all();
            $riwayat = Peminjaman::where('user_id', $user->id)
                        ->with(['laboratorium', 'alat', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

            return view('user.index', [
                'available_lab' => $labs,
                'data' => $riwayat,
            ]);
        }
    }

    // ... (fungsi stafDashboard tetap sama)
    public function stafDashboard()
    {
        try {
            $menunggu = Peminjaman::where('status_peminjaman', 'pending')->count();
            $dipinjam = Peminjaman::where('status_peminjaman', 'disetujui')->count();
            $rusak = Alat::whereIn('kondisi', ['Rusak', 'Perbaikan'])->count();

            return view('staf.dashboard', compact('menunggu', 'dipinjam', 'rusak'));
        } catch (\Exception $e) {
            return view('staf.dashboard', ['menunggu' => 0, 'dipinjam' => 0, 'rusak' => 0]);
        }
    }
}