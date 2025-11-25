<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SopController extends Controller
{
    /**
     * Display a listing of the resource for staff.
     */
    public function index()
    {
        $sops = Document::where('tipe_dokumen', 'SOP')->with('laboratorium', 'uploadedBy')->latest()->get();
        return view('staf.sop.index', compact('sops'));
    }

    /**
     * Display a listing of the resource for users.
     */
    public function indexForUser()
    {
        $sops = Document::where('tipe_dokumen', 'SOP')->with('laboratorium')->latest()->get();
        return view('user.sop.index', compact('sops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratorium = Laboratorium::all();
        return view('staf.sop.create', compact('laboratorium'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:laboratorium,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:5120', // max 5MB
        ]);

        $filePath = $request->file('file')->store('documents/sop', 'public');

        Document::create([
            'lab_id' => $request->lab_id,
            'tipe_dokumen' => 'SOP',
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'uploaded_by' => Auth::id(),
            'status' => 'confirmed' // SOPs are auto-confirmed
        ]);

        return redirect()->route('staf.sop.index')->with('success', 'SOP berhasil diunggah.');
    }

    /**
     * Display the specified resource.
     * Note: We don't have a dedicated show page, but it's here for resource completeness.
     */
    public function show(Document $sop)
    {
        return redirect()->route('staf.sop.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $sop)
    {
        if ($sop->tipe_dokumen !== 'SOP') {
            abort(404);
        }
        $laboratorium = Laboratorium::all();
        return view('staf.sop.edit', compact('sop', 'laboratorium'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $sop)
    {
        if ($sop->tipe_dokumen !== 'SOP') {
            abort(404);
        }

        $request->validate([
            'lab_id' => 'required|exists:laboratorium,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120', // File is optional on update
        ]);

        $filePath = $sop->file_path;

        // If a new file is uploaded, delete the old one and store the new one
        if ($request->hasFile('file')) {
            if ($sop->file_path && Storage::disk('public')->exists($sop->file_path)) {
                Storage::disk('public')->delete($sop->file_path);
            }
            $filePath = $request->file('file')->store('documents/sop', 'public');
        }

        $sop->update([
            'lab_id' => $request->lab_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
        ]);

        return redirect()->route('staf.sop.index')->with('success', 'SOP berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $sop)
    {
        if ($sop->tipe_dokumen !== 'SOP') {
            abort(404);
        }

        // Delete the file from storage
        if ($sop->file_path && Storage::disk('public')->exists($sop->file_path)) {
            Storage::disk('public')->delete($sop->file_path);
        }

        $sop->delete();

        return redirect()->route('staf.sop.index')->with('success', 'SOP berhasil dihapus.');
    }

    /**
     * Handle file download.
     */
    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->judul . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }
}