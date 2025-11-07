@extends('layouts.app')
@section('title', 'Daftar Tindakan Terapi')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Daftar Kode Tindakan Terapi</h4>
                        <div>
                            <a href="{{ route('admin.tindakan-terapi.create') }}" class="btn btn-primary me-2">Tambah Tindakan</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Kode</th>
                                    <th>Deskripsi Tindakan</th>
                                    <th>Kategori</th>
                                    <th>Kategori Klinis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tindakanTerapi as $index => $tindakan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tindakan->idkode_tindakan_terapi }}</td>
                                        <td>{{ $tindakan->kode }}</td>
                                        <td>{{ $tindakan->deskripsi_tindakan_terapi }}</td>
                                        <td>{{ $tindakan->kategori ? $tindakan->kategori->nama_kategori : $tindakan->idkategori }}</td>
                                        <td>{{ $tindakan->kategoriKlinis ? $tindakan->kategoriKlinis->nama_kategori_klinis : $tindakan->idkategori_klinis }}</td>
                                        <td>
                                            <a href="{{ route('admin.tindakan-terapi.edit', $tindakan->idkode_tindakan_terapi) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('admin.tindakan-terapi.destroy', $tindakan->idkode_tindakan_terapi) }}" method="POST" style="display:inline-block; margin-left:6px;" onsubmit="return confirm('Yakin ingin menghapus tindakan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data tindakan terapi masih kosong.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
