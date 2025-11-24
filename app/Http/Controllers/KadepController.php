<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Alat;
use Illuminate\Support\Facades\Auth;

class KadepController extends Controller
{
    // List laporan kerusakan
    public function kerusakanIndex()
    {
        $reports = Document::where('tipe_dokumen', 'Laporan Kerusakan')
            ->with(['laboratorium', 'uploadedBy', 'alat'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kadep.kerusakan.index', compact('reports'));
    }

    // Konfirmasi laporan (benarkan) — hanya kadep
    public function confirmReport(Request $request, $id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->tipe_dokumen !== 'Laporan Kerusakan') {
            return redirect()->back()->with('error', 'Dokumen bukan laporan kerusakan.');
        }

        // Jika sudah dikonfirmasi
        if ($doc->status === 'confirmed') {
            return redirect()->back()->with('info', 'Laporan sudah dikonfirmasi.');
        }

        // Update kondisi alat menjadi Baik (atau Diperbaiki) dan tandai dokumen sebagai confirmed
        if ($doc->alat_id) {
            $alat = Alat::find($doc->alat_id);
            if ($alat) {
                $alat->update(['kondisi' => 'Baik']);
            }
        }

        $doc->update([
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Laporan kerusakan dikonfirmasi dan alat diperbarui.');
    }
}
