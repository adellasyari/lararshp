@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Pendaftaran Temu Dokter</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.temu-dokter.index') }}">Temu Dokter</a></li>
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
                        <h3 class="card-title">Form Ubah Pendaftaran</h3>
                    </div>
                    <form action="{{ route('resepsionis.temu-dokter.update', $temu->getKey()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label for="idpet" class="form-label">Pilih Hewan <span class="text-danger">*</span></label>
                                <select name="idpet" id="idpet" class="form-select @error('idpet') is-invalid @enderror" required autofocus>
                                    <option value="">-- Pilih Hewan --</option>
                                    @foreach($pets as $pet)
                                        <option value="{{ $pet->idpet }}" {{ old('idpet', $temu->idpet) == $pet->idpet ? 'selected' : '' }}>{{ $pet->nama }} - {{ $pet->pemilik->user->name ?? 'Pemilik N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('idpet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idrole_user" class="form-label">Pilih Dokter <span class="text-danger">*</span></label>
                                <select name="idrole_user" id="idrole_user" class="form-select @error('idrole_user') is-invalid @enderror" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokters as $dokter)
                                        <option value="{{ $dokter->idrole_user }}" {{ old('idrole_user', $temu->idrole_user) == $dokter->idrole_user ? 'selected' : '' }}>{{ $dokter->user->name ?? $dokter->user->name ?? 'Dokter' }}</option>
                                    @endforeach
                                </select>
                                @error('idrole_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', optional(\Illuminate\Support\Carbon::parse($temu->waktu_daftar))->format('Y-m-d')) }}" required>
                                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu" class="form-label">Waktu <span class="text-danger">*</span></label>
                                    <input type="time" name="waktu" id="waktu" class="form-control @error('waktu') is-invalid @enderror" value="{{ old('waktu', optional(\Illuminate\Support\Carbon::parse($temu->waktu_daftar))->format('H:i')) }}" required>
                                    @error('waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="0" {{ old('status', $temu->status) == 0 ? 'selected' : '' }}>tunggu</option>
                                    <option value="1" {{ old('status', $temu->status) == 1 ? 'selected' : '' }}>di periksa</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('resepsionis.temu-dokter.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
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
                            <dd class="col-sm-7">{{ $temu->getKey() }}</dd>

                            <dt class="col-sm-5">No. Urut</dt>
                            <dd class="col-sm-7">{{ $temu->no_urut ?? '-' }}</dd>

                            <dt class="col-sm-5">Waktu Daftar</dt>
                            <dd class="col-sm-7">{{ optional(\Illuminate\Support\Carbon::parse($temu->waktu_daftar))->format('d M Y H:i') ?? '-' }}</dd>
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
document.addEventListener('DOMContentLoaded', function() {
    const first = document.querySelector('[autofocus]'); if(first) first.focus();
});
</script>
@endsection
