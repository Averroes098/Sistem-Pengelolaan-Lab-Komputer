<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $data = Peminjaman::where('user_id', Auth::user()->id)->get();
        return view('user.index',[
            'data' => $data, // nnti diubah saat authentication sudah selesai
            'available_lab' => Laboratorium::where('status',1)->get()
        ]);
    }
    public function pinjam(Request $request) 
    {
        $validation = $request->validate([
            'lab_id' => 'required',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after:tgl_pinjam'
        ],['tgl_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam']);

        if($validation) {
            Peminjaman::create([
                'lab_id' => $request->lab_id,
                'user_id' => Auth::user()->id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
            ]);

            $current_lab = Laboratorium::where('id',$request->lab_id)->first();
            $current_lab->update(['status' => 0]);

            return redirect()->route('user.index')->with(['success'=>'Peminjaman berhasil dilakukan. Mohon cek secara berkala status peminjaman anda']);
        }

        return redirect()->route('user.index')->with(['error'=>'Gagal melakukan peminjaman']);
    }
}
