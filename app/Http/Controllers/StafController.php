<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\Document;
use App\Models\Laboratorium;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StafController extends Controller
{
    // ==================== DASHBOARD ====================
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

    // ==================== KERUSAKAN ====================
    public function kerusakan()
    {
        $alat = Alat::all();
        return view('staf.kerusakan', compact('alat'));
    }

    public function inputKerusakan(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'keterangan' => 'required|string|max:500',
        ]);

        try {
            // Ambil alat
            $alat = Alat::findOrFail($request->alat_id);

            // Update kondisi alat menjadi "Rusak"
            $alat->update([
                'kondisi' => 'Rusak',
            ]);

            // Buat laporan kerusakan di documents table (simpan alat_id untuk referensi)
            Document::create([
                'lab_id' => $alat->lab_id,
                'alat_id' => $alat->id,
                'tipe_dokumen' => 'Laporan Kerusakan',
                'judul' => "Laporan Kerusakan - {$alat->nama_alat}",
                'deskripsi' => $request->keterangan,
                'file_path' => null,
                'uploaded_by' => Auth::id(),
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Kerusakan berhasil dicatat dan alat diupdate menjadi Rusak');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==============================
    // VALIDASI (untuk staf)
    // ==============================
    public function validasi()
    {
        $menunggu = Peminjaman::with(['user', 'alat', 'laboratorium'])
            ->where('status_peminjaman', 'pending')
            ->get();

        return view('staf.validasi.peminjaman', ['peminjaman' => $menunggu]);
    }

    // ==============================
    // APPROVE PEMINJAMAN
    // ==============================
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_peminjaman = 'disetujui';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman berhasil disetujui');
    }

    // ==============================
    // REJECT PEMINJAMAN
    // ==============================
    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_peminjaman = 'ditolak';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman berhasil ditolak');
    }

    // ==============================
    // PENGEMBALIAN (untuk staf)
    // ==============================
    public function pengembalian()
    {
        $peminjaman = Peminjaman::with(['user', 'alat', 'laboratorium'])
            ->where('status_peminjaman', 'disetujui')
            ->where('status_pengembalian', 'belum dikembalikan')
            ->get();

        return view('staf.pengembalian', compact('peminjaman'));
    }

    // ==============================
    // KONFIRMASI PENGEMBALIAN
    // ==============================
    public function konfirmasiPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_pengembalian = 'sudah dikembalikan';
        $peminjaman->save();

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi');
    }

    // ==============================
    // LAPORAN PEMINJAMAN
    // ==============================
    public function laporanPeminjaman()
    {
        // Ambil semua data peminjaman
        $peminjaman = Peminjaman::all(); 
        // Kirim data ke view staf/laporan/peminjaman.blade.php
        return view('staf.laporan.peminjaman', compact('peminjaman'));
    }
}
