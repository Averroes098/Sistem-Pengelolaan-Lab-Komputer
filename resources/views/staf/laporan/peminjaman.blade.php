@extends('layouts.staf') {{-- sesuaikan dengan layout stafmu --}}

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Laporan Peminjaman Laboratorium</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Alat / Laboratorium</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td>{{ $p->user?->nama ?? $p->user?->name ?? 'User Tidak Dikenal' }}</td>
                    <td>{{ $p->alat?->nama_alat ?? ($p->laboratorium?->nama_lab ?? 'Lab Tidak Dikenal') }}</td>
                    <td>{{ $p->tgl_pinjam ?? $p->tanggal_pinjam ?? '-' }}</td>
                    <td>
                        @if($p->status_peminjaman == 'pending')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @elseif($p->status_peminjaman == 'approved')
                            <span class="badge bg-success text-white">Disetujui</span>
                        @elseif($p->status_peminjaman == 'returned')
                            <span class="badge bg-info text-white">Dikembalikan</span>
                        @else
                            <span class="badge bg-secondary text-white">{{ $p->status_peminjaman }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection