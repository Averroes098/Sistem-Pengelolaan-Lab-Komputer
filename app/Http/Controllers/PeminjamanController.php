<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.index', [
            'peminjaman' => Peminjaman::all()
        ]);
    }
    public function create()
    {
        $available_lab = Laboratorium::where('status',1)->get();
        return view('admin.peminjaman.create', [
            'available_lab' => $available_lab
        ]);
    }
    public function store(Request $request)
    {
        $validation = $request->validate([
            'lab_id' => 'required|unique:laboratorium',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after:tgl_pinjam'
        ],['tgl_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam']);

        if($validation) {
            Peminjaman::create([
                'lab_id' => $request->lab_id,
                'user_id' => 'Dummy User',
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
            ]);

            $current_lab = Laboratorium::where('id',$request->lab_id)->get();
            $current_lab->update(['status' => 0]);

            return redirect()->route('peminjaman.index')->with(['success'=>'Data berhasil ditambah!']);
        }
        return redirect()->route('peminjaman.index')->with(['error'=>'Data gagal ditambah!']);
    }
    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('admin.peminjaman.edit',[
            'peminjaman' => $peminjaman
        ]);
    }
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $validation = $request->validate([
            'status_peminjaman' => 'required',
            'status_pengembalian' => 'required'
        ]);

        if($validation) {
            $peminjaman->update([
                'status_peminjaman' => $request->status_peminjaman,
                'status_pengembalian' => $request->status_pengembalian
            ]);

            $current_lab = Laboratorium::where('id',$peminjaman->lab_id)->first();
            
            if($request->status_pengembalian == 'belum dikembalikan'){
                $current_lab->update(['status' => 0]);
            }else if($request->status_pengembalian == 'sudah dikembalikan') {
                $current_lab->update(['status' => 1]);
            }

            return redirect()->route('peminjaman.index')->with(['success'=>'Data berhasil diubah!']);
        }
        return redirect()->route('peminjaman.index')->with(['error'=>'Data gagal diubah!']);
    }
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $current_lab = Laboratorium::where('id',$peminjaman->lab_id)->first();
        $current_lab->update(['status' => 1]);

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with(['success'=>'Data berhasil dihapus!']);
    }
}
