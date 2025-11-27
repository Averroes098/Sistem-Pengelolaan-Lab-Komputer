<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index() {
        $alat = Alat::with('laboratorium')->get();
        return view('kadep.alat.index', compact('alat'));
    }

    public function create() {
        $laboratorium = Laboratorium::all();
        return view('kadep.alat.create', compact('laboratorium'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_alat' => 'required|unique:alat',
            'nama_alat' => 'required',
            'lab_id' => 'required|exists:laboratorium,id',
            'kategori' => 'required',
            'kondisi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images/alat'), $imageName);
            $input['gambar'] = 'images/alat/'.$imageName;
        }

        Alat::create($input);
        return redirect()->route('kadep.alat.index')->with('success', 'Data alat berhasil ditambahkan');
    }

    public function edit($id) {
        $alat = Alat::findOrFail($id);
        $laboratorium = Laboratorium::all();
        return view('kadep.alat.edit', compact('alat', 'laboratorium'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'kode_alat' => 'required|unique:alat,kode_alat,'.$id,
            'nama_alat' => 'required',
            'lab_id' => 'required|exists:laboratorium,id',
            'kategori' => 'required',
            'kondisi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $alat = Alat::findOrFail($id);
        $input = $request->all();

        if ($request->hasFile('gambar')) {
            if ($alat->gambar && file_exists(public_path($alat->gambar))) {
                unlink(public_path($alat->gambar));
            }
            $image = $request->file('gambar');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images/alat'), $imageName);
            $input['gambar'] = 'images/alat/'.$imageName;
        }

        $alat->update($input);
        return redirect()->route('kadep.alat.index')->with('success', 'Data alat berhasil diperbarui');
    }

    public function destroy($id) {
        $alat = Alat::findOrFail($id);
        if ($alat->gambar && file_exists(public_path($alat->gambar))) {
            unlink(public_path($alat->gambar));
        }
        $alat->delete();
        return redirect()->route('kadep.alat.index')->with('success', 'Data alat berhasil dihapus');
    }
}