<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'status' => 'required|in:tersedia,terpakai,maintenance',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        $input['nama_lab'] = $request->nama;

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images/lab'), $imageName);
            $input['foto'] = 'images/lab/'.$imageName;
        }

        Laboratorium::create($input);

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
            'status' => 'required|in:tersedia,terpakai,maintenance',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $laboratorium = Laboratorium::findOrFail($id);
        $input = $request->all();
        $input['nama_lab'] = $request->nama;

        if ($request->hasFile('foto')) {
            if ($laboratorium->foto && file_exists(public_path($laboratorium->foto))) {
                unlink(public_path($laboratorium->foto));
            }
            $image = $request->file('foto');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images/lab'), $imageName);
            $input['foto'] = 'images/lab/'.$imageName;
        }


        $laboratorium->update($input);

        return redirect()->route('admin.laboratorium.index')
            ->with(['success' => 'Data berhasil diubah!']);
    }

    public function destroy($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        if ($laboratorium->foto && file_exists(public_path($laboratorium->foto))) {
            unlink(public_path($laboratorium->foto));
        }
        $laboratorium->delete();

        return redirect()->route('admin.laboratorium.index')
            ->with(['success' => 'Data berhasil dihapus!']);
    }
}
