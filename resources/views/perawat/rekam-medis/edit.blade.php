@extends('layouts.lte.main')

@section('title','Ubah Rekam Medis')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Ubah Rekam Medis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('perawat.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('perawat.rekam-medis.index') }}">Rekam Medis</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                    <div class="card-header"><h3 class="card-title">Form Ubah Rekam Medis</h3></div>
                    <form action="{{ url('/rekam-medis/' . $rekamMedis->getKey()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', optional($rekamMedis->created_at)->format('Y-m-d')) }}" required autofocus>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
                                <textarea name="anamnesa" id="anamnesa" class="form-control @error('anamnesa') is-invalid @enderror" required>{{ old('anamnesa', $rekamMedis->anamnesa) }}</textarea>
                                @error('anamnesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="temuan_klinis" class="form-label">Temuan Klinis</label>
                                <textarea name="temuan_klinis" id="temuan_klinis" class="form-control @error('temuan_klinis') is-invalid @enderror">{{ old('temuan_klinis', $rekamMedis->temuan_klinis) }}</textarea>
                                @error('temuan_klinis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="diagnosa" class="form-label">Diagnosa</label>
                                <textarea name="diagnosa" id="diagnosa" class="form-control @error('diagnosa') is-invalid @enderror">{{ old('diagnosa', $rekamMedis->diagnosa) }}</textarea>
                                @error('diagnosa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Pet and Dokter Pemeriksa fields removed per request --}}
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('perawat.rekam-medis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary ms-2">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-info">
                    <div class="card-header"><h3 class="card-title">Info</h3></div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">ID</dt>
                            <dd class="col-sm-7">{{ $rekamMedis->idrekam_medis ?? $rekamMedis->getKey() }}</dd>

                            <dt class="col-sm-5">Pet</dt>
                            <dd class="col-sm-7" id="info-pet">{{ optional($rekamMedis->pet)->nama ?? '-' }}</dd>

                            <dt class="col-sm-5">Pemilik</dt>
                            <dd class="col-sm-7" id="info-pemilik">{{ optional(optional(optional($rekamMedis->pet)->pemilik)->user)->nama ?? '-' }}</dd>

                            <dt class="col-sm-5">Ras / Jenis</dt>
                            <dd class="col-sm-7" id="info-rasjenis">{{ optional(optional($rekamMedis->pet)->rasHewan)->nama_ras ?? '-' }} / {{ optional(optional(optional($rekamMedis->pet)->rasHewan)->jenisHewan)->nama_jenis_hewan ?? '-' }}</dd>

                            <dt class="col-sm-5">Dokter</dt>
                            <dd class="col-sm-7" id="info-dokter">{{ optional(optional($rekamMedis->roleUser)->user)->nama ?? optional($rekamMedis->dokter)->nama ?? '-' }}</dd>

                            <dt class="col-sm-5">Dibuat</dt>
                            <dd class="col-sm-7" id="info-created">{{ $rekamMedis->created_at ? \Carbon\Carbon::parse($rekamMedis->created_at)->format('d M Y H:i') : '-' }}</dd>

                            <dt class="col-sm-5">Terakhir diubah</dt>
                            <dd class="col-sm-7" id="info-updated">{{ $rekamMedis->updated_at ? \Carbon\Carbon::parse($rekamMedis->updated_at)->format('d M Y H:i') : '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

