@extends('layouts.staf')

@section('title', 'Proses Pengembalian')

@section('content')
<div class="content-wrapper">

    <h3 class="mb-4">Proses Pengembalian Alat</h3>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Kondisi Kembali</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($peminjaman as $p)
                    <tr>
                        <td>{{ $p->user?->nama ?? $p->user?->name ?? 'User Tidak Dikenal' }}</td>
                        <td>{{ $p->alat?->nama_alat ?? ($p->laboratorium?->nama_lab ?? 'Lab Tidak Dikenal') }}</td>
                        <td>{{ $p->tgl_pinjam ?? $p->tanggal_pinjam ?? '-' }}</td>
                        <td>
                            <select class="form-control" name="kondisi">
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('staf.pengembalian.konfirmasi', $p->id) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Selesaikan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">Belum ada alat untuk dikembalikan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection