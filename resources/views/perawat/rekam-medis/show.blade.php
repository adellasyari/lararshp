@extends('layouts.lte.main')

@section('content')
<!-- App header -->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Detail Rekam Medis</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('perawat.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('perawat.rekam-medis.index') }}">Rekam Medis</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rekam Medis #{{ $rekamMedis->idrekam_medis }}</h5>
            <div>
              <a href="{{ route('perawat.rekam-medis.index') }}" class="btn btn-sm btn-secondary ms-2">Kembali</a>
            </div>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-3">Tanggal Periksa</dt>
              <dd class="col-sm-9">{{ $rekamMedis->created_at ? \Carbon\Carbon::parse($rekamMedis->created_at)->format('d M Y H:i') : '-' }}</dd>

              <dt class="col-sm-3">Nama Hewan</dt>
              <dd class="col-sm-9">{{ optional($rekamMedis->pet)->nama ?? '-' }}</dd>

              <dt class="col-sm-3">Nama Pemilik</dt>
              <dd class="col-sm-9">{{ optional(optional(optional($rekamMedis->pet)->pemilik)->user)->nama ?? '-' }}</dd>

              <dt class="col-sm-3">Ras / Jenis</dt>
              <dd class="col-sm-9">{{ optional(optional($rekamMedis->pet)->rasHewan)->nama_ras ?? '-' }} / {{ optional(optional(optional($rekamMedis->pet)->rasHewan)->jenisHewan)->nama_jenis_hewan ?? '-' }}</dd>

              <dt class="col-sm-3">Dokter Pemeriksa</dt>
              <dd class="col-sm-9">{{ optional($rekamMedis->roleUser)->user->nama ?? (optional($rekamMedis->dokter)->nama ?? ($rekamMedis->dokter_pemeriksa ?? '-')) }}</dd>

              <dt class="col-sm-3">Anamnesa</dt>
              <dd class="col-sm-9">{{ $rekamMedis->anamnesa ?? '-' }}</dd>

              <dt class="col-sm-3">Temuan Klinis</dt>
              <dd class="col-sm-9">{{ $rekamMedis->temuan_klinis ?? '-' }}</dd>

              <dt class="col-sm-3">Diagnosa</dt>
              <dd class="col-sm-9">{{ $rekamMedis->diagnosa ?? '-' }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
