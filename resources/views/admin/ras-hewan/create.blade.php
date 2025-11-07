@extends('layouts.app')
@section('title', 'Tambah Ras Hewan')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Tambah Ras Hewan</h4></div>
                    <div class="card-body">
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                        <form action="{{ route('admin.ras-hewan.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_ras" class="form-label">Nama Ras Hewan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_ras" id="nama_ras" class="form-control @error('nama_ras') is-invalid @enderror" value="{{ old('nama_ras') }}" required>
                                @error('nama_ras')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idjenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                                <select name="idjenis_hewan" id="idjenis_hewan" class="form-control @error('idjenis_hewan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis Hewan --</option>
                                    @foreach($jenis as $j)
                                        <option value="{{ $j->idjenis_hewan }}" {{ old('idjenis_hewan') == $j->idjenis_hewan ? 'selected' : '' }}>{{ $j->nama_jenis_hewan }}</option>
                                    @endforeach
                                </select>
                                @error('idjenis_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.ras-hewan.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
