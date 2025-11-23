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

            // Buat laporan kerusakan di documents table
            Document::create([
                'lab_id' => $alat->lab_id,
                'tipe_dokumen' => 'Laporan Kerusakan',
                'judul' => "Laporan Kerusakan - {$alat->nama_alat}",
                'deskripsi' => $request->keterangan,
                'file_path' => null,
                'uploaded_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Kerusakan berhasil dicatat dan alat diupdate menjadi Rusak');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==================== SOP ====================
    public function sop() {
        $laboratorium = Laboratorium::all();
        return view('staf.sop', compact('laboratorium'));
    }

    public function uploadSOP(Request $request) {
        $request->validate([
            'lab_id' => 'required|exists:laboratorium,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:5120', // max 5MB
        ]);

        try {
            // Store file
            $filePath = $request->file('file')->store('documents/sop', 'public');

            // Create document record
            Document::create([
                'lab_id' => $request->lab_id,
                'tipe_dokumen' => 'SOP',
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_path' => $filePath,
                'uploaded_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'SOP berhasil diupload!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error uploading SOP: ' . $e->getMessage());
        }
    }
}
