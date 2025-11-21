@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Perawat</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Perawat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Perawat</h3>
                        <a href="{{ route('admin.perawat.create') }}" class="btn btn-primary">Tambah Perawat</a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Pendidikan</th>
                                        <th>No. HP</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $u)
                                    <tr>
                                        <td>{{ $u->iduser }}</td>
                                        <td>{{ $u->nama }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ optional($u->perawat)->pendidikan ?? '-' }}</td>
                                        <td>{{ optional($u->perawat)->no_hp ?? '-' }}</td>
                                        <td>{{ optional($u->perawat)->alamat ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.perawat.edit', $u->iduser) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('admin.perawat.destroy', $u->iduser) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus perawat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">{{ $users->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
