<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    public function index()
    {
        return view('admin.laboratorium.index', [
            'data' => Laboratorium::all()
        ]);
    }

    public function create()
    {
        return view('admin.laboratorium.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:30',
            'status' => 'required|in:tersedia,terpakai,maintenance'
        ]);

        Laboratorium::create([
            'nama_lab' => $request->nama, // FIX
            'status' => $request->status,
        ]);

        return redirect()->route('admin.laboratorium.index')
            ->with(['success' => 'Data berhasil ditambah!']);
    }

    public function edit($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        return view('admin.laboratorium.edit', [
            'laboratorium' => $laboratorium
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:30',
            'status' => 'required|in:tersedia,terpakai,maintenance'
        ]);

        Laboratorium::where('id', $id)->update([
            'nama_lab' => $request->nama, // FIX
            'status' => $request->status,
        ]);

        return redirect()->route('admin.laboratorium.index')
            ->with(['success' => 'Data berhasil diubah!']);
    }

    public function destroy($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        $laboratorium->delete();

        return redirect()->route('admin.laboratorium.index')
            ->with(['success' => 'Data berhasil dihapus!']);
    }
}
