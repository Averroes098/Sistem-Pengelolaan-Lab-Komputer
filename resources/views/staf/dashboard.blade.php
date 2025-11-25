@extends('layouts.staf')

@section('title', 'Dashboard Staf')

@section('content')
<div class="content-wrapper">

    <div class="row mb-4">
        <div class="col-md-4 grid-margin">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h4 class="mb-2">Permintaan Menunggu</h4>
                    <h2>{{ $menunggu }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="mb-2">Alat Sedang Dipinjam</h4>
                    <h2>{{ $dipinjam }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h4 class="mb-2">Alat Rusak</h4>
                    <h2>{{ $rusak }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-3">
            <a href="{{ route('staf.peminjaman') }}" class="btn btn-primary btn-block">Validasi Peminjaman</a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('staf.pengembalian') }}" class="btn btn-success btn-block">Proses Pengembalian</a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('staf.kerusakan') }}" class="btn btn-danger btn-block">Catat Kerusakan</a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('staf.sop.index') }}" class="btn btn-info btn-block">Upload SOP</a>
        </div>

    </div>

</div>
@endsection
