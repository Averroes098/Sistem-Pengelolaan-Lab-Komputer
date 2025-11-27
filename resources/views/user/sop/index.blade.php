
@extends('layouts.main', ['title' => 'SOP Laboratorium'])

@section('content')
<style>
    .description-cell {
        max-width: 300px;
        white-space: normal;
        word-wrap: break-word;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Standard Operating Procedures (SOP)</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul SOP</th>
                                    <th>Laboratorium</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sops as $sop)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sop->judul }}</td>
                                        <td>{{ $sop->laboratorium->nama ?? 'Umum' }}</td>
                                        <td class="description-cell">{{ $sop->deskripsi ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('sop.download', $sop->id) }}" class="btn btn-sm btn-primary" title="Download">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada SOP yang diunggah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
