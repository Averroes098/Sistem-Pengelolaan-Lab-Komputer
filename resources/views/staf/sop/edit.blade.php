@extends('layouts.staf')

@section('title', 'Edit SOP')

@section('content')
<div class="content-wrapper">

    <h3 class="mb-4">Edit SOP Laboratorium</h3>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validasi Error:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('staf.sop.update', $sop->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="lab_id">Pilih Laboratorium <span class="text-danger">*</span></label>
                    <select name="lab_id" id="lab_id" class="form-control @error('lab_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Lab --</option>
                        @foreach($laboratorium as $lab)
                            <option value="{{ $lab->id }}" {{ old('lab_id', $sop->lab_id) == $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
                        @endforeach
                    </select>
                    @error('lab_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label for="judul">Judul SOP <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" 
                           placeholder="Contoh: SOP Praktikum Pemrograman Web" value="{{ old('judul', $sop->judul) }}" required>
                    @error('judul')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                              rows="3" placeholder="Jelaskan tujuan SOP ini">{{ old('deskripsi', $sop->deskripsi) }}</textarea>
                    @error('deskripsi')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label for="file">File SOP (Opsional)</label>
                    <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.txt" 
                           class="form-control @error('file') is-invalid @enderror">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah file. Tipe file: PDF, DOC, DOCX, TXT (Max 5MB)</small>
                    @if($sop->file_path)
                    <p class="mt-2">File saat ini: <a href="{{ route('sop.download', $sop->id) }}">{{ $sop->judul }}</a></p>
                    @endif
                    @error('file')<small class="text-danger d-block">{{ $message }}</small>@enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save-outline mr-2"></i>Update SOP
                    </button>
                    <a href="{{ route('staf.sop.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
