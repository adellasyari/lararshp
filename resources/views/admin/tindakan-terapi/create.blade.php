@extends('layouts.app')
@section('title', 'Tambah Tindakan Terapi')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Tambah Tindakan Terapi</h4></div>
                    <div class="card-body">
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                        <form action="{{ route('admin.tindakan-terapi.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" required>
                                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_tindakan_terapi" class="form-label">Deskripsi Tindakan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_tindakan_terapi" id="deskripsi_tindakan_terapi" class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" required>{{ old('deskripsi_tindakan_terapi') }}</textarea>
                                @error('deskripsi_tindakan_terapi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="idkategori" id="idkategori" class="form-control @error('idkategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->idkategori }}" {{ old('idkategori') == $k->idkategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('idkategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idkategori_klinis" class="form-label">Kategori Klinis</label>
                                <select name="idkategori_klinis" id="idkategori_klinis" class="form-control @error('idkategori_klinis') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori Klinis (opsional) --</option>
                                    @foreach($kategoriKlinis as $kk)
                                        <option value="{{ $kk->idkategori_klinis }}" {{ old('idkategori_klinis') == $kk->idkategori_klinis ? 'selected' : '' }}>{{ $kk->nama_kategori_klinis }}</option>
                                    @endforeach
                                </select>
                                @error('idkategori_klinis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tindakan-terapi.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
