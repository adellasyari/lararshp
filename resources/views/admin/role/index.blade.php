@extends('layouts.app')
@section('title', 'Daftar Role')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Daftar Role</h4>
                            <div>
                                <a href="{{ route('admin.role.create') }}" class="btn btn-primary me-2">Tambah Role</a>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                            </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($role as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_role }}</td>
                                        <td>
                                            <a href="{{ route('admin.role.edit', $item->idrole) }}" class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('admin.role.destroy', $item->idrole) }}" method="POST" style="display:inline-block; margin-left:6px;" onsubmit="return confirm('Yakin ingin menghapus role ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Data role masih kosong.</td>
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
