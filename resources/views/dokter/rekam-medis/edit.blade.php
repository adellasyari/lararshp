@extends('layouts.lte.main')

@section('title','Ubah Rekam Medis')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Ubah Rekam Medis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dokter.rekam-medis.index') }}">Rekam Medis</a></li>
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
                    <form action="{{ url('/dokter/rekam-medis/' . $rekamMedis->getKey()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                            <p class="text-muted">Dokter hanya dapat menambahkan atau mengubah <strong>Tindakan Terapi</strong> dan <strong>Detail</strong>. Informasi lain bersifat read-only.</p>

                            <div class="mb-3">
                                <label for="idkode_tindakan_terapi" class="form-label">Tindakan Terapi <span class="text-danger">(opsional)</span></label>
                                <select name="idkode_tindakan_terapi" id="idkode_tindakan_terapi" class="form-select @error('idkode_tindakan_terapi') is-invalid @enderror">
                                    <option value="">-- Pilih Tindakan --</option>
                                    @if(isset($tindakanTerapis) && $tindakanTerapis->count())
                                        @foreach($tindakanTerapis as $t)
                                            <option value="{{ $t->idkode_tindakan_terapi }}" {{ old('idkode_tindakan_terapi', $selectedTindakan ?? '') == $t->idkode_tindakan_terapi ? 'selected' : '' }}>{{ $t->kode }} - {{ $t->deskripsi_tindakan_terapi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('idkode_tindakan_terapi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="detail" class="form-label">Detail Tindakan</label>
                                <textarea name="detail" id="detail" class="form-control @error('detail') is-invalid @enderror" rows="4">{{ old('detail') }}</textarea>
                                @error('detail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary ms-2">Simpan Tindakan</button>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() { const first = document.querySelector('[autofocus]'); if(first) first.focus(); });
</script>
@endsection
