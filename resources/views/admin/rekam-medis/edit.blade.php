@extends('layouts.app')
@section('title', 'Edit Rekam Medis')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><h4>Edit Rekam Medis</h4></div>
                    <div class="card-body">
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                        <form action="{{ route('admin.rekam-medis.update', $rekamMedis->getKey()) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', optional($rekamMedis->created_at)->format('Y-m-d')) }}" required>
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

                            <!-- 'tindakan' and 'obat' removed per request; these columns are not in DB -->

                            <div class="mb-3">
                                <label for="idpet" class="form-label">Pet <span class="text-danger">*</span></label>
                                <select name="idpet" id="idpet" class="form-control @error('idpet') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hewan --</option>
                                    @foreach($pets as $p)
                                        <option value="{{ $p->idpet }}" {{ old('idpet', $rekamMedis->idpet) == $p->idpet ? 'selected' : '' }}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                                @error('idpet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="dokter_pemeriksa" class="form-label">Dokter Pemeriksa <span class="text-danger">*</span></label>
                                <select name="dokter_pemeriksa" id="dokter_pemeriksa" class="form-control @error('dokter_pemeriksa') is-invalid @enderror" required>
                                    <option value="">-- Pilih Dokter (Role User) --</option>
                                    @foreach($doctors as $d)
                                        <option value="{{ $d->idrole_user }}" {{ old('dokter_pemeriksa', $rekamMedis->dokter_pemeriksa) == $d->idrole_user ? 'selected' : '' }}>{{ $d->user->nama ?? ('Role: '.$d->role->idrole) }}</option>
                                    @endforeach
                                </select>
                                @error('dokter_pemeriksa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.rekam-medis.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
