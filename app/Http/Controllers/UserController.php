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
        $user = Auth::user();
        
        // Check if profile is complete, if not redirect to profile completion
        if (!$user->is_profile_complete) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Silakan lengkapi data profil Anda terlebih dahulu.');
        }
        
        $data = Peminjaman::where('user_id', Auth::id())->get();

        return view('user.index', [
            'data' => $data,
            'available_lab' => Laboratorium::all(), // ambil semua lab untuk dropdown
        ]);
    }

    public function pinjam(Request $request)
    {
        $request->validate([
            'lab_id' => 'required',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after:tgl_pinjam',
        ], [
            'tgl_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam',
        ]);

        // Simpan data peminjaman
        Peminjaman::create([
            'lab_id' => $request->lab_id,
            'user_id' => Auth::id(),
            'tanggal_pinjam' => $request->tgl_pinjam,
            'tanggal_kembali' => $request->tgl_kembali,
        ]);

        // Update status lab jika kolom 'status' sudah ada
        $current_lab = Laboratorium::find($request->lab_id);
        if ($current_lab && $current_lab->fillable && in_array('status', $current_lab->getFillable())) {
            $current_lab->update(['status' => 0]);
        }

        return redirect()->route('user.index')
            ->with('success', 'Peminjaman berhasil dilakukan. Mohon cek secara berkala status peminjaman anda');
    }
}