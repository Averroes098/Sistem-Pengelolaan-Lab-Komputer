<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Alat; // ← WAJIB TAMBAHKAN

class StafController extends Controller
{
    public function index() {
        return view('staf.dashboard');
    }

    public function peminjaman() {
        $menunggu = Peminjaman::where('status_peminjaman', 'pending')->get();
        return view('staf.peminjaman', compact('menunggu'));
    }

    public function approve($id) {
        //
    }

    public function reject($id) {
        //
    }

    public function pengembalian() {
        $data = Peminjaman::where('status_peminjaman', 'disetujui')->get();
        return view('staf.pengembalian', compact('data'));
    }

    public function konfirmasiPengembalian($id) {
        //
    }

    // ==================== KERUSAKAN ====================
public function kerusakan() 
{
    $alat = Alat::all();
    return view('staf.kerusakan', compact('alat'));
}

public function inputKerusakan(Request $request)
{
    // Simpan ke tabel laporan, atau update kondisi alat
    // (sementara: redirect saja)
    return redirect()->back()->with('success', 'Kerusakan berhasil dicatat');
}

    public function sop() {
        return view('staf.sop');
    }

    public function uploadSOP(Request $request) {
        //
    }
}
