<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class PeminjamanController extends Controller
{
    // ==============================
    // INDEX (Admin melihat semua)
    // ==============================
    public function index()
    {
        return view('admin.peminjaman.index', [
            'peminjaman' => Peminjaman::with(['user', 'alat', 'laboratorium'])->latest()->get()
        ]);
    }

    // ==============================
    // CREATE (Versi Admin - Pilih manual lewat dropdown)
    // ==============================
    public function create()
    {
        $laboratorium = Laboratorium::all();
        $alat = Alat::all();

        return view('admin.peminjaman.create', compact('laboratorium', 'alat'));
    }

    // ==============================
    // [BARU] CREATE FOR USER (Versi FlowChart - Klik dari Dashboard)
    // ==============================
    public function createForUser($lab_id)
    {
        // Ambil data lab berdasarkan ID yang diklik di dashboard
        $lab = Laboratorium::findOrFail($lab_id);
        
        // Arahkan ke view form khusus user
        return view('user.peminjaman.create', compact('lab'));
    }

    // ==============================
    // STORE (Versi Admin)
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'lab_id'      => 'required|exists:laboratorium,id',
            'alat_id'     => 'nullable|exists:alat,id', // Ubah jadi nullable jika admin hanya pinjam ruangan
            'tgl_pinjam'  => 'required|date',
            'kembali'     => 'required|date|after_or_equal:tgl_pinjam',
        ]);

        $peminjaman = new Peminjaman();
        $peminjaman->lab_id              = $request->lab_id;
        $peminjaman->alat_id             = $request->alat_id; // Bisa null
        $peminjaman->user_id             = Auth::id();
        $peminjaman->tgl_pinjam          = $request->tgl_pinjam;
        $peminjaman->tgl_kembali         = $request->kembali;
        $peminjaman->status_peminjaman   = "pending";
        $peminjaman->status_pengembalian = "belum dikembalikan";
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman Berhasil Ditambahkan');
    }

    // ==============================
    // [BARU] STORE USER (Proses simpan dari Form User)
    // ==============================
    public function storeUser(Request $request)
    {
        // 1. Validasi Input User
        $request->validate([
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'tanggal_pinjam'  => 'required|date',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'keperluan'       => 'required|string|max:255',
        ]);

        // 2. Simpan ke Database
        Peminjaman::create([
            'user_id'             => Auth::id(),
            'lab_id'              => $request->laboratorium_id, // Mapping nama form ke db
            'alat_id'             => null, // User meminjam ruangan, bukan alat spesifik
            'tgl_pinjam'          => $request->tanggal_pinjam,
            'tgl_kembali'         => $request->tanggal_pinjam, // Asumsi pinjam harian (pulang hari)
            'status_peminjaman'   => 'pending',
            'status_pengembalian' => 'belum dikembalikan',
        ]);

        // 3. Redirect ke Dashboard User dengan Pesan
        return redirect()->route('dashboard')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan.');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        return view('admin.peminjaman.edit', [
            'peminjaman'   => Peminjaman::findOrFail($id),
            'laboratorium' => Laboratorium::all(),
            'alat'         => Alat::all()
        ]);
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'status_peminjaman'  => 'required',
            'status_pengembalian' => 'required',
        ]);

        $peminjaman->status_peminjaman   = $request->status_peminjaman;
        $peminjaman->status_pengembalian = $request->status_pengembalian;
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data berhasil diubah!');
    }

    // ==============================
    // DELETE
    // ==============================
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    // ==============================
    // VALIDASI (untuk staf)
    // ==============================
    public function validasi()
    {
        $menunggu = Peminjaman::with(['user', 'alat', 'laboratorium'])
            ->where('status_peminjaman', 'pending')
            ->get();

        return view('staf.peminjaman', compact('menunggu'));
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
}