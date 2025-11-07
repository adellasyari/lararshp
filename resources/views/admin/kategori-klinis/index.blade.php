@extends('layouts.app')
@section('title', 'Daftar Kategori Klinis')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Daftar Kategori Klinis</h4>
                        <div>
                            <a href="{{ route('admin.kategori-klinis.create') }}" class="btn btn-primary me-2"><i class="fas fa-plus"></i> Tambah Kategori Klinis</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori Klinis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoriKlinis as $index => $kat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kat->nama_kategori_klinis }}</td>
                                        <td>
                                            @php $key = method_exists($kat, 'getKey') ? $kat->getKey() : ($kat->id_kategori_klinis ?? null); @endphp
                                            @if(!empty($key))
                                                <a href="{{ route('admin.kategori-klinis.edit', ['id' => $key]) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                <form action="{{ route('admin.kategori-klinis.destroy', ['id' => $key]) }}" method="POST" style="display:inline-block; margin-left:6px;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Data kategori klinis masih kosong.</td>
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
