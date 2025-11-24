@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Kerusakan (Kadep)</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Alat</th>
                <th>Lab</th>
                <th>Deskripsi</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $r)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ optional($r->alat)->nama_alat ?? $r->judul }}</td>
                <td>{{ optional($r->laboratorium)->nama_lab ?? '-' }}</td>
                <td>{{ $r->deskripsi }}</td>
                <td>{{ optional($r->uploadedBy)->nama ?? optional($r->uploadedBy)->email }}</td>
                <td>{{ ucfirst($r->status ?? 'pending') }}</td>
                <td>
                    @if(($r->status ?? 'pending') === 'pending')
                    <form method="POST" action="{{ route('kadep.kerusakan.confirm', $r->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-success" type="submit">Konfirmasi & Benarkan</button>
                    </form>
                    @else
                    <span class="text-muted">Dikonfirmasi</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Tidak ada laporan kerusakan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection