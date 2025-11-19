@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-white">Form Peminjaman Lab</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="laboratorium_id" value="{{ $lab->id }}">

                        <div class="form-group">
                            <label>Laboratorium Tujuan</label>
                            <input type="text" class="form-control" value="{{ $lab->nama_lab }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Peminjam</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Peminjaman</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" name="tanggal_pinjam" required>
                            @error('tanggal_pinjam') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai">Jam Mulai</label>
                                    <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" name="jam_mulai" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_selesai">Jam Selesai</label>
                                    <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" name="jam_selesai" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keperluan">Keperluan / Keterangan</label>
                            <textarea class="form-control @error('keperluan') is-invalid @enderror" name="keperluan" rows="3" placeholder="Contoh: Praktikum Pemrograman Web" required></textarea>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-success">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection