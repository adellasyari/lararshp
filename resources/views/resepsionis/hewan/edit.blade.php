@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Hewan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.hewan.index') }}">Hewan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ubah Data Hewan</h3>
                    </div>
                    <form action="{{ route('resepsionis.hewan.update', $pet->getKey()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $pet->nama) }}" required autofocus>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idpemilik" class="form-label">Pemilik <span class="text-danger">*</span></label>
                                <select name="idpemilik" id="idpemilik" class="form-select @error('idpemilik') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pemilik --</option>
                                    @foreach($pemilik as $p)
                                        <option value="{{ $p->idpemilik }}" {{ old('idpemilik', $pet->idpemilik) == $p->idpemilik ? 'selected' : '' }}>{{ $p->user->name ?? 'User Dihapus' }}</option>
                                    @endforeach
                                </select>
                                @error('idpemilik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idras_hewan" class="form-label">Ras Hewan <span class="text-danger">*</span></label>
                                <select name="idras_hewan" id="idras_hewan" class="form-select @error('idras_hewan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Ras --</option>
                                    @foreach($ras as $r)
                                        <option value="{{ $r->idras_hewan }}" {{ old('idras_hewan', $pet->idras_hewan) == $r->idras_hewan ? 'selected' : '' }}>{{ $r->nama_ras ?? $r->nama_ras_hewan }} ({{ $r->jenisHewan->nama_jenis_hewan ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                                @error('idras_hewan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $pet->tanggal_lahir) }}">
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="warna_tanda" class="form-label">Warna / Tanda</label>
                                <input type="text" name="warna_tanda" id="warna_tanda" class="form-control @error('warna_tanda') is-invalid @enderror" value="{{ old('warna_tanda', $pet->warna_tanda) }}">
                                @error('warna_tanda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div>
                                    <label class="me-3"><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'L' ? 'checked' : '' }}> Laki-laki</label>
                                    <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'P' ? 'checked' : '' }}> Perempuan</label>
                                </div>
                                @error('jenis_kelamin')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('resepsionis.hewan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Info</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">ID</dt>
                            <dd class="col-sm-7">{{ $pet->getKey() }}</dd>

                            <dt class="col-sm-5">Pemilik</dt>
                            <dd class="col-sm-7">{{ $pet->pemilik->user->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">Dibuat</dt>
                            <dd class="col-sm-7">{{ optional($pet->created_at)->format('d M Y H:i') ?? '-' }}</dd>

                            <dt class="col-sm-5">Terakhir diubah</dt>
                            <dd class="col-sm-7">{{ optional($pet->updated_at)->format('d M Y H:i') ?? '-' }}</dd>
                        </dl>
                    </div>
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
