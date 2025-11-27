@extends('layouts.main')
@section('title', 'Tambah Alat')

@section('content')
<div class="content-wrapper">
    <!-- Page Title Header Starts-->
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h3 class="page-title">Tambah Alat</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('kadep.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kadep.alat.index') }}">Data Alat</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Alat</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Title Header Ends-->
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kadep.alat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="kode_alat">Kode Alat</label>
                            <input type="text" class="form-control" id="kode_alat" name="kode_alat" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_alat">Nama Alat</label>
                            <input type="text" class="form-control" id="nama_alat" name="nama_alat" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" required>
                        </div>
                        <div class="form-group">
                            <label for="lab_id">Laboratorium</label>
                            <select class="form-control" id="lab_id" name="lab_id" required>
                                @foreach($laboratorium as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama_lab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select class="form-control" id="kondisi" name="kondisi">
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <input type="file" class="form-control-file" id="gambar" name="gambar">
                        </div>

                        <div class="form-group">
    <label>Jumlah</label>
    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') ?? 0 }}" required>
</div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kadep.alat.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
