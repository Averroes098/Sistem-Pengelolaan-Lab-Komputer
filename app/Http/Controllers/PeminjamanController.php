<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use App\Models\User;

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
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
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
            'tgl_kembali'         => $request->tanggal_kembali,
            'status_peminjaman'   => 'pending',
            'status_pengembalian' => 'belum dikembalikan',
        ]);

        // 3. Redirect ke Dashboard User dengan Pesan
        return redirect()->route('dashboard.user')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan.');
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

    // Untuk Kadep
    public function kadepIndex()
    {
        $peminjaman = Peminjaman::with(['user', 'alat', 'laboratorium'])->latest()->get();

        return view('kadep.peminjaman.index', compact('peminjaman'));
    }

    // ==============================
    // LIST PEMINJAMAN LAB (Admin)
    // ==============================
    public function labIndex()
    {
        $peminjaman = Peminjaman::whereNotNull('lab_id')->with(['laboratorium', 'user'])->latest()->get();
        return view('admin.peminjaman.lab.index', compact('peminjaman'));
    }

    // ==============================
    // FORM TAMBAH LAB (Admin)
    // ==============================
    public function labCreate()
    {
        $laboratorium = Laboratorium::all();
        return view('admin.peminjaman.lab.create', compact('laboratorium'));
    }

    // ==============================
    // STORE PEMINJAMAN LAB (Admin)
    // ==============================
    public function labStore(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:laboratorium,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
        ]);

        Peminjaman::create([
            'lab_id' => $request->lab_id,
            'user_id' => Auth::id(),
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'status_peminjaman' => 'pending',
            'status_pengembalian' => 'belum dikembalikan',
        ]);

        return redirect()->route('admin.peminjaman.lab.index')->with('success', 'Data peminjaman laboratorium berhasil dibuat!');
    }



    // ==============================
    // LIST PEMINJAMAN ALAT (Admin)
    // ==============================
    public function alatIndex()
    {
        $peminjaman = Peminjaman::whereNotNull('alat_id')->with(['alat', 'user'])->latest()->get();
        return view('admin.peminjaman.alat.index', compact('peminjaman'));
    }

    // ==============================
    // FORM TAMBAH ALAT PEMINJAMAN (Admin)
    // ==============================
    public function alatCreate()
    {
        $users = User::where('level', 'user')->get();
        $alats = Alat::all();
        return view('admin.peminjaman.alat.create', compact('users', 'alats'));
    }

    // ==============================
    // STORE PEMINJAMAN ALAT (Admin)
    // ==============================
    public function alatStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'alat_id' => 'required',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'status_peminjaman' => 'required'
        ]);

        // Ambil data alat agar lab_id otomatis ikut
        $alat = Alat::findOrFail($request->alat_id);

        Peminjaman::create([
            'alat_id' => $request->alat_id,
            'user_id' => $request->user_id,
            'lab_id' => $alat->lab_id, // otomatis berdasarkan alat
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'status_peminjaman' => $request->status_peminjaman,
            'status_pengembalian' => 'belum dikembalikan',
        ]);

        return redirect()->route('admin.peminjaman.alat.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }



    // ==============================
    // EDIT LAB (Kadep)
    // ==============================
    public function editLab($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $laboratorium = Laboratorium::all();

        return view('kadep.peminjaman.lab.edit', compact('peminjaman', 'laboratorium'));
    }


    // ==============================
    // UPDATE LAB (Kadep)
    // ==============================
    public function updateLab(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'status_peminjaman' => 'required',
        ]);

        $peminjaman->update([
            'status_peminjaman' => $request->status_peminjaman,
        ]);

        return redirect()->route('kadep.peminjaman.lab.index')->with('success', 'Status permintaan diperbarui!');
    }


    // ==============================
    // DELETE LAB (Kadep)
    // ==============================
    public function destroyLab($id)
    {
        Peminjaman::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
