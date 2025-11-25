<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Laboratorium;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index() {
        $alat = Alat::with('laboratorium')->get();
        return view('admin.alat.index', compact('alat'));
    }

    public function create() {
        $laboratorium = Laboratorium::all();
        return view('admin.alat.create', compact('laboratorium'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_alat' => 'required|unique:alat',
            'nama_alat' => 'required',
            'lab_id'    => 'required|exists:laboratorium,id',
            'kategori'  => 'required',
            'kondisi'   => 'required',
            'jumlah'    => 'required|integer|min:0',
        ]);

        Alat::create($request->all());
        return redirect()->route('admin.alat.index')
            ->with('success', 'Data alat berhasil ditambahkan');
    }

    public function edit($id) {
        $alat = Alat::findOrFail($id);
        $laboratorium = Laboratorium::all();
        return view('admin.alat.edit', compact('alat', 'laboratorium'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'kode_alat' => 'required|unique:alat,kode_alat,'.$id,
            'nama_alat' => 'required',
            'lab_id'    => 'required|exists:laboratorium,id',
            'kategori'  => 'required',
            'kondisi'   => 'required',
            'jumlah'    => 'required|integer|min:0',
        ]);

        $alat = Alat::findOrFail($id);
        $alat->update($request->all());
        return redirect()->route('admin.alat.index')
            ->with('success', 'Data alat berhasil diperbarui');
    }

    public function destroy($id) {
        $alat = Alat::findOrFail($id);
        $alat->delete();
        return redirect()->route('admin.alat.index')
            ->with('success', 'Data alat berhasil dihapus');
    }
}