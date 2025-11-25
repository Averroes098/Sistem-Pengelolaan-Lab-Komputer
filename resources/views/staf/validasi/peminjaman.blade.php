@extends('layouts.staf')

@section('title', 'Validasi Peminjaman')

@section('content')
<div class="content-wrapper">

    <h3 class="mb-4">Validasi Peminjaman</h3>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($peminjaman as $p)
                    <tr>
                        <td>{{ $p->user?->nama ?? $p->user?->name ?? 'User Tidak Dikenal' }}</td>
                        <td>{{ $p->alat?->nama_alat ?? ($p->laboratorium?->nama_lab ?? 'Lab Tidak Dikenal') }}</td>
                        <td>{{ $p->tgl_pinjam ?? $p->tanggal_pinjam ?? '-' }}</td>
                        <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                        <td>
                            <form method="POST" action="{{ route('staf.peminjaman.approve', $p->id) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>

                            <form method="POST" action="{{ route('staf.peminjaman.reject', $p->id) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">Tidak ada permintaan menunggu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection