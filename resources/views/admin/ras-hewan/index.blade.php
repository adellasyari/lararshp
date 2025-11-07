@extends('layouts.app')
@section('title', 'Ras Hewan')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Daftar Ras Hewan</h3>
                    <div>
                        <a href="{{ route('admin.ras-hewan.create') }}" class="btn btn-primary me-2">Tambah Ras Hewan</a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama Ras Hewan</th>
                                    <th>Jenis Hewan</th>
                                    <th style="width:160px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_ras as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_ras ?? $item->nama_ras_hewan ?? $item->idras_hewan }}</td>
                                        <td>{{ $item->jenisHewan->nama_jenis_hewan ?? 'Jenis Tidak Ditemukan' }}</td>
                                        <td>
                                            @php $key = method_exists($item, 'getKey') ? $item->getKey() : ($item->idras_hewan ?? null); @endphp
                                            @if($key)
                                                <a href="{{ route('admin.ras-hewan.edit', ['id' => $key]) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('admin.ras-hewan.destroy', ['id' => $key]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Data ras hewan masih kosong.</td>
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