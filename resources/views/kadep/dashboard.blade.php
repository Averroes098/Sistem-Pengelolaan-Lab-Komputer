@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <div class="row page-title-header">
        <div class="col-12">
            <div class="page-header">
                <h4 class="page-title">Dashboard Kepala Departemen</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-0">Total Peminjaman</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-inline-block pt-3">
                            <div class="d-md-flex">
                                <h2 class="mb-0">{{ $totalPeminjaman ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="d-inline-block">
                            <i class="typcn typcn-clipboard display-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-0">Laporan Kerusakan Alat</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-inline-block pt-3">
                            <div class="d-md-flex">
                                <h2 class="mb-0">{{ $alatRusak ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="d-inline-block">
                            <i class="typcn typcn-warning-outline display-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection