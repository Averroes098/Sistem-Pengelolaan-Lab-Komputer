@extends('layouts.main')
@section('title', 'Tambah Peminjaman')

@section('content')
<div class="content-wrapper">

  <div class="card">
    <div class="card-body">

      <h4 class="card-title">Form Tambah Peminjaman</h4>

      <form method="POST" action="{{ route('admin.peminjaman.store') }}">
        @csrf

        {{-- Pilih Laboratorium --}}
        <div class="form-group">
          <label>Laboratorium</label>
          <select name="lab_id" class="form-control" required>
            @foreach ($laboratorium as $lab)
              <option value="{{ $lab->id }}">{{ $lab->nama_lab }}</option>
            @endforeach
          </select>
        </div>

        {{-- Pilih Alat --}}
        <div class="form-group">
          <label>Alat</label>
          <select name="alat_id" class="form-control" required>
            @foreach ($alat as $item)
              <option value="{{ $item->id }}">{{ $item->nama_alat }}</option>
            @endforeach
          </select>
        </div>

        {{-- Tanggal Pinjam --}}
        <div class="form-group">
          <label>Tanggal Pinjam</label>
          <input type="date" name="tanggal_pinjam" class="form-control" required>
        </div>

        {{-- Tanggal Kembali --}}
        <div class="form-group">
          <label>Tanggal Kembali</label>
          <input type="date" name="tanggal_kembali" class="form-control" required>
        </div>

        {{-- Keterangan --}}
        <div class="form-group">
          <label>Keterangan</label>
          <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-light">Cancel</a>
      </form>

    </div>
  </div>

</div>
@endsection
