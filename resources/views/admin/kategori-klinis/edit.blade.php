@extends('layouts.app')
@section('title', 'Edit Kategori Klinis')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Kategori Klinis</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.kategori-klinis.update', $kategoriKlinis->getKey()) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama_kategori_klinis" class="form-label">Nama Kategori Klinis <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_kategori_klinis') is-invalid @enderror"
                                    id="nama_kategori_klinis" name="nama_kategori_klinis" value="{{ old('nama_kategori_klinis', $kategoriKlinis->nama_kategori_klinis) }}"
                                    placeholder="Masukkan nama kategori klinis" required>
                                @error('nama_kategori_klinis')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.kategori-klinis.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
