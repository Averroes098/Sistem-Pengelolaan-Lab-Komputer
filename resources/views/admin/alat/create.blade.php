@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tambah Alat</h4>

        <form action="{{ route('admin.alat.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Kode Alat</label>
                <input type="text" name="kode_alat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Laboratorium</label>
                <select name="lab_id" class="form-control" required>
                    <option value="">-- Pilih Lab --</option>
                    @foreach($laboratorium as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->nama_lab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" class="form-control" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <button class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
</div>
@endsection
