@extends('layouts.staf')

@section('title', 'Manajemen SOP')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Manajemen SOP Laboratorium</h3>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Daftar SOP</h4>
                        <a href="{{ route('staf.sop.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus-circle-outline mr-2"></i>Tambah SOP Baru
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul SOP</th>
                                    <th>Laboratorium</th>
                                    <th>Uploader</th>
                                    <th>Tgl Upload</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sops as $key => $sop)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sop->judul }}</td>
                                    <td>{{ $sop->laboratorium->nama_lab ?? 'N/A' }}</td>
                                    <td>{{ $sop->uploadedBy->name ?? 'N/A' }}</td>
                                    <td>{{ $sop->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('sop.download', $sop->id) }}" class="btn btn-sm btn-info" title="Download">
                                            <i class="mdi mdi-download"></i>
                                        </a>
                                        <a href="{{ route('staf.sop.edit', $sop->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('staf.sop.destroy', $sop->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SOP ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada SOP yang diunggah.</td>
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
