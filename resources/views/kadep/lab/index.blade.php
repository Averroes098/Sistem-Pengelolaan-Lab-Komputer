@extends('layouts.kadep')

@section('content')
<h1>Peminjaman Laboratorium</h1>

<a href="{{ route('kadep.peminjaman.lab.create') }}" class="btn btn-primary">Tambah Peminjaman Lab</a>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Laboratorium</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status Peminjaman</th>
            <th>Status Pengembalian</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($peminjaman as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->laboratorium->nama_lab ?? '-' }}</td>
            <td>{{ $item->tgl_pinjam }}</td>
            <td>{{ $item->tgl_kembali }}</td>
            <td>{{ ucfirst($item->status_peminjaman) }}</td>
            <td>{{ ucfirst($item->status_pengembalian) }}</td>
            <td>
                <a href="{{ route('kadep.peminjaman.lab.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('kadep.peminjaman.lab.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection