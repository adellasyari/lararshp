@extends('layouts.lte.main')

@section('content')
<!-- App header -->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Detail Rekam Medis</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('dokter.rekam-medis.index') }}">Rekam Medis</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        {{-- Show all rekam medis for this pet (read-only) --}}
        @if(isset($allRekamMedis) && $allRekamMedis->count())
          @foreach($allRekamMedis as $rek)
            <div class="card mb-3">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Rekam - {{ \Carbon\Carbon::parse($rek->created_at)->format('d F Y, H:i') }}</h5>
              </div>
              <div class="card-body">
                <dl class="row mb-0">
                  <dt class="col-sm-4">Nama Pasien</dt>
                  <dd class="col-sm-8">{{ optional($rek->pet)->nama ?? '-' }}</dd>

                  <dt class="col-sm-4">Nama Pemilik</dt>
                  <dd class="col-sm-8">{{ optional(optional(optional($rek->pet)->pemilik)->user)->name ?? '-' }}</dd>

                  <dt class="col-sm-4">Dokter Pemeriksa</dt>
                  <dd class="col-sm-8">{{ optional($rek->roleUser)->user->name ?? (optional($rek->dokter)->nama ?? ($rek->dokter_pemeriksa ?? '-')) }}</dd>
                </dl>
              </div>

              <div class="card-body border-top">
                <h6>Tindakan & Terapi</h6>
                <div class="table-responsive">
                  <table class="table table-bordered mb-0">
                    <thead>
                      <tr>
                        <th style="width:10%">#</th>
                        <th style="width:15%">Kategori</th>
                        <th style="width:10%">Kode</th>
                        <th>Deskripsi</th>
                        <th style="width:20%">Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $rows = (isset($allDetails) && isset($allDetails[$rek->idrekam_medis])) ? $allDetails[$rek->idrekam_medis] : collect(); @endphp
                      @forelse($rows as $i => $d)
                        <tr>
                          <td>{{ $i + 1 }}</td>
                          <td>{{ $d->kategori ?? $d->kategori_klinis ?? '-' }}</td>
                          <td>{{ $d->kode ?? '-' }}</td>
                          <td>{{ $d->deskripsi_tindakan_terapi ?? '-' }}</td>
                          <td>{{ $d->detail ?? '-' }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="5" class="text-center">Belum ada tindakan tercatat.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-body border-top">
                <h6>Hasil Pemeriksaan</h6>
                <dl class="row mb-0">
                  <dt class="col-sm-3">Anamnesa</dt>
                  <dd class="col-sm-9">{{ $rek->anamnesa ?? '-' }}</dd>

                  <dt class="col-sm-3">Temuan Klinis</dt>
                  <dd class="col-sm-9">{{ $rek->temuan_klinis ?? '-' }}</dd>

                  <dt class="col-sm-3">Diagnosa</dt>
                  <dd class="col-sm-9">{{ $rek->diagnosa ?? '-' }}</dd>
                </dl>
              </div>
            </div>
          @endforeach
        @else
          <div class="card">
            <div class="card-body text-center text-muted">Belum ada rekam medis untuk pasien ini.</div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
