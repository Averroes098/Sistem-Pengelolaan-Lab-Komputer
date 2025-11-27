<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Alat;
use App\Models\Laboratorium;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KadepController extends Controller
{
    // Dashboard Kepala Departemen
    public function dashboard()
    {
        // Lab yang tersedia
        $labTersedia = Laboratorium::where('status', 'tersedia')->get();

        // Lab paling sering dipinjam
        $labSeringDipinjam = Peminjaman::select('lab_id', DB::raw('count(*) as total'))
            ->whereNotNull('lab_id')
            ->groupBy('lab_id')
            ->orderByDesc('total')
            ->with('laboratorium')
            ->first();

        // Alat paling sering dipinjam
        $alatSeringDipinjam = Peminjaman::select('alat_id', DB::raw('count(*) as total'))
            ->whereNotNull('alat_id')
            ->groupBy('alat_id')
            ->orderByDesc('total')
            ->with('alat')
            ->first();

        // Tren peminjaman per bulan
        $trenPeminjaman = Peminjaman::select(
                DB::raw("MONTH(tgl_pinjam) as bulan"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear('tgl_pinjam', date('Y'))
            ->groupBy(DB::raw("MONTH(tgl_pinjam)"))
            ->pluck('total', 'bulan');

        // Total peminjaman
        $totalPeminjaman = Peminjaman::count();

        // Total alat rusak
        $alatRusak = Alat::where('kondisi', 'rusak')->count();

        // Data untuk grafik penggunaan lab
        $labUsage = Peminjaman::select('lab_id', DB::raw('count(*) as total'))
            ->whereHas('laboratorium') // Ensure the relation exists
            ->groupBy('lab_id')
            ->with('laboratorium')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->laboratorium->nama => $item->total];
            });

        // Data untuk grafik penggunaan alat
        $alatUsage = Peminjaman::select('alat_id', DB::raw('count(*) as total'))
            ->whereNotNull('alat_id')
            ->whereHas('alat') // Ensure the relation exists
            ->groupBy('alat_id')
            ->with('alat')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->alat->nama_alat => $item->total];
            });

        return view('kadep.dashboard', compact(
            'labTersedia',
            'labSeringDipinjam',
            'trenPeminjaman',
            'alatSeringDipinjam',
            'totalPeminjaman',
            'alatRusak',
            'labUsage',
            'alatUsage'
        ));
    }

    // List laporan kerusakan
    public function kerusakanIndex()
    {
        $reports = Document::where('tipe_dokumen', 'Laporan Kerusakan')
            ->with(['laboratorium', 'uploadedBy', 'alat'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kadep.kerusakan.index', compact('reports'));
    }

    // Konfirmasi laporan kerusakan
    public function confirmReport(Request $request, $id)
    {
        $doc = Document::findOrFail($id);

        if ($doc->tipe_dokumen !== 'Laporan Kerusakan') {
            return redirect()->back()->with('error', 'Dokumen bukan laporan kerusakan.');
        }

        if ($doc->status === 'confirmed') {
            return redirect()->back()->with('info', 'Laporan sudah dikonfirmasi.');
        }

        // Update kondisi alat jika ada
        if ($doc->alat_id) {
            $alat = Alat::find($doc->alat_id);
            if ($alat) {
                $alat->update(['kondisi' => 'Baik']);
            }
        }

        // Update status dokumen
        $doc->update([
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Laporan kerusakan dikonfirmasi dan alat diperbarui.');
    }
}