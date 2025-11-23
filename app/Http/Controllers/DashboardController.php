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
        
        // Validasi user tidak null
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // ---------------------------------------------------------
        // KONDISI 1: JIKA USER ADALAH ADMIN
        // ---------------------------------------------------------
        if ($user->level === 'admin') {
            $data_lab = Laboratorium::paginate(3);
            // Tambahkan with() untuk eager load relasi
            $data_peminjaman = Peminjaman::with(['user', 'laboratorium', 'alat'])
                ->latest()
                ->paginate(3);

            return view('admin.dashboard', [
                'data_lab' => $data_lab,
                'data_peminjaman' => $data_peminjaman,
                'user' => $user,
            ]);
        }

        // ---------------------------------------------------------
        // KONDISI 2: JIKA USER ADALAH STAF
        // ---------------------------------------------------------
        elseif ($user->level === 'staf') {
            return $this->stafDashboard();
        }

        // ---------------------------------------------------------
        // KONDISI 3: JIKA USER ADALAH USER (MAHASISWA / DOSEN)
        // ---------------------------------------------------------
        else {
            // 1. Ambil semua data Lab untuk ditampilkan di katalog (grid view)
            $labs = Laboratorium::all();

            // 2. Ambil Riwayat Peminjaman milik user yang sedang login saja
            $riwayat = Peminjaman::where('user_id', $user->id)
                        ->with(['laboratorium', 'alat', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

            // Arahkan ke view khusus user
            return view('user.index', [
                'available_lab' => $labs,
                'data' => $riwayat,
            ]);
        }
    }

    /**
     * Logika Dashboard Staf (Dipisah agar rapi, dipanggil di logika index di atas)
     */
    public function stafDashboard()
    {
        try {
            $menunggu = Peminjaman::where('status_peminjaman', 'pending')->count();
            $dipinjam = Peminjaman::where('status_peminjaman', 'disetujui')->count();
            $rusak = Alat::whereIn('kondisi', ['Rusak', 'Perbaikan'])->count();

            return view('staf.dashboard', compact('menunggu', 'dipinjam', 'rusak'));
        } catch (\Exception $e) {
            // Jika ada error di query, kembalikan dengan data default
            return view('staf.dashboard', [
                'menunggu' => 0,
                'dipinjam' => 0,
                'rusak' => 0
            ])->with('error', 'Terjadi error saat memuat data: ' . $e->getMessage());
        }
    }
}