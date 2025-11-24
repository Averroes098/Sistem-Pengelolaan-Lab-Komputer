<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index() {
        $alat = Alat::all();
        return view('alat.index', compact('alat'));
    }

    public function create() {
        return view('alat.create');
    }

    public function store(Request $request) {
        $request->validate([
            'kode_alat' => 'required|unique:alat',
            'nama_alat' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required|integer',
        ]);

        Alat::create($request->all());
        return redirect()->route('alat.index')->with('success', 'Data alat berhasil ditambahkan');
    }

    public function edit($id) {
        $alat = Alat::findOrFail($id);
        return view('alat.edit', compact('alat'));
    }

    public function update(Request $request, $id) {
        $alat = Alat::findOrFail($id);
        $alat->update($request->all());
        return redirect()->route('alat.index')->with('success', 'Data alat berhasil diperbarui');
    }

    public function destroy($id) {
        $alat = Alat::findOrFail($id);
        $alat->delete();
        return redirect()->route('alat.index')->with('success', 'Data alat berhasil dihapus');
    }
}