<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\Alat;  // ← WAJIB DITAMBAH (Sesuai request Anda)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk Auth

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ---------------------------------------------------------
        // KONDISI 1: JIKA USER ADALAH ADMIN
        // ---------------------------------------------------------
        if ($user->level === 'admin') {
            $data_lab = Laboratorium::paginate(3);
            $data_peminjaman = Peminjaman::with('user')->latest()->paginate(3);

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
        // KONDISI 3: JIKA USER ADALAH MAHASISWA / DOSEN
        // ---------------------------------------------------------
        else {
            // 1. Ambil semua data Lab untuk ditampilkan di katalog (grid view)
            $labs = Laboratorium::all();

            // 2. Ambil Riwayat Peminjaman milik user yang sedang login saja
            $riwayat = Peminjaman::where('user_id', $user->id)
                        ->with('laboratorium')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

            // Arahkan ke view khusus user
            // Sesuaikan nama variabel dengan yang digunakan di `user.index`
            return view('user.index', [
                'available_lab' => $labs,
                'data' => $riwayat,
            ]);
        }
    }

    // Logika Dashboard Staf (Dipisah agar rapi, dipanggil di logika index di atas)
    public function stafDashboard()
    {
        $menunggu = Peminjaman::where('status_peminjaman', 'pending')->count();
        $dipinjam = Peminjaman::where('status_peminjaman', 'disetujui')->count();
        $rusak = Alat::whereIn('kondisi', ['Rusak', 'Perbaikan'])->count();

        return view('staf.dashboard', compact('menunggu', 'dipinjam', 'rusak'));
    }
}