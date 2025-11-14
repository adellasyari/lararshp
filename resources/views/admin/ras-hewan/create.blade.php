@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Ras Hewan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ras-hewan.index') }}">Ras Hewan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Ras Hewan</h3>
                    </div>
                    <form action="{{ route('admin.ras-hewan.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                            <div class="mb-3">
                                <label for="nama_ras" class="form-label">Nama Ras Hewan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_ras" id="nama_ras" class="form-control @error('nama_ras') is-invalid @enderror" value="{{ old('nama_ras') }}" required autofocus>
                                @error('nama_ras')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idjenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                                <select name="idjenis_hewan" id="idjenis_hewan" class="form-select @error('idjenis_hewan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis Hewan --</option>
                                    @foreach($jenis as $j)
                                        <option value="{{ $j->idjenis_hewan }}" {{ old('idjenis_hewan') == $j->idjenis_hewan ? 'selected' : '' }}>{{ $j->nama_jenis_hewan }}</option>
                                    @endforeach
                                </select>
                                @error('idjenis_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.ras-hewan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-success ms-2"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() { const first = document.querySelector('[autofocus]'); if(first) first.focus(); });
</script>
@endsection
