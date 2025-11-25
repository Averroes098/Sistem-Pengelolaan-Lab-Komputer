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
}
