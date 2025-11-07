@extends('layouts.app')
@section('title', 'Tambah Pet')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Pet</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.pet.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idpemilik" class="form-label">Pemilik <span class="text-danger">*</span></label>
                                <select name="idpemilik" id="idpemilik" class="form-control @error('idpemilik') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pemilik --</option>
                                    @foreach($pemilik as $p)
                                        <option value="{{ $p->idpemilik }}" {{ old('idpemilik') == $p->idpemilik ? 'selected' : '' }}>{{ $p->user->nama ?? 'User Dihapus' }}</option>
                                    @endforeach
                                </select>
                                @error('idpemilik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idras_hewan" class="form-label">Ras Hewan <span class="text-danger">*</span></label>
                                <select name="idras_hewan" id="idras_hewan" class="form-control @error('idras_hewan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Ras --</option>
                                    @foreach($ras as $r)
                                        <option value="{{ $r->idras_hewan }}" {{ old('idras_hewan') == $r->idras_hewan ? 'selected' : '' }}>{{ $r->nama_ras ?? $r->nama_ras_hewan }} ({{ $r->jenisHewan->nama_jenis_hewan ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                                @error('idras_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="warna_tanda" class="form-label">Warna / Tanda</label>
                                <input type="text" name="warna_tanda" id="warna_tanda" class="form-control @error('warna_tanda') is-invalid @enderror" value="{{ old('warna_tanda') }}">
                                @error('warna_tanda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div>
                                    <label class="me-3"><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}> Laki-laki</label>
                                    <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}> Perempuan</label>
                                </div>
                                @error('jenis_kelamin')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.pet.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
