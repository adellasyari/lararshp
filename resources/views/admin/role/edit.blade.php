@extends('layouts.app')
@section('title', 'Edit Role')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4>Edit Role</h4></div>
                    <div class="card-body">
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                        <form action="{{ route('admin.role.update', $role->idrole) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama_role" class="form-label">Nama Role <span class="text-danger">*</span></label>
                                <input type="text" name="nama_role" id="nama_role" class="form-control @error('nama_role') is-invalid @enderror" value="{{ old('nama_role', $role->nama_role) }}" required>
                                @error('nama_role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
