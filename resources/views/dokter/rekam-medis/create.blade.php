@extends('layouts.lte.main')

@section('content')
<!-- App header -->
<div class="app-content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6"><h3 class="mb-0">{{ isset($rekamMedis) ? 'Detail Rekam Medis' : 'Tambah Rekam Medis' }}</h3></div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-end">
					<li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('dokter.rekam-medis.index') }}">Rekam Medis</a></li>
					<li class="breadcrumb-item active">{{ isset($rekamMedis) ? 'Detail' : 'Tambah' }}</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="app-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				{{-- Show creation form only when there is no existing rekam --}}
				@unless(isset($rekamMedis))
				<div class="card mb-3">
					<div class="card-header"><h5 class="mb-0">Form Tambah Rekam Medis</h5></div>
					<div class="card-body">
						<form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
							@csrf
							@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

							<div class="mb-3">
								<label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
								<input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($selectedPet) ? now()->format('Y-m-d') : old('tanggal')) }}" required autofocus>
								@error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="mb-3">
								<label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
								<textarea name="anamnesa" id="anamnesa" class="form-control @error('anamnesa') is-invalid @enderror" required>{{ old('anamnesa') }}</textarea>
								@error('anamnesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="mb-3">
								<label for="temuan_klinis" class="form-label">Temuan Klinis</label>
								<textarea name="temuan_klinis" id="temuan_klinis" class="form-control @error('temuan_klinis') is-invalid @enderror">{{ old('temuan_klinis') }}</textarea>
								@error('temuan_klinis')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="mb-3">
								<label for="diagnosa" class="form-label">Diagnosa</label>
								<textarea name="diagnosa" id="diagnosa" class="form-control @error('diagnosa') is-invalid @enderror">{{ old('diagnosa') }}</textarea>
								@error('diagnosa')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="mb-3">
								<label for="idkode_tindakan_terapi" class="form-label">Tindakan Terapi (opsional)</label>
								<select name="idkode_tindakan_terapi" id="idkode_tindakan_terapi" class="form-select @error('idkode_tindakan_terapi') is-invalid @enderror">
									<option value="">-- Pilih Tindakan --</option>
									@if(isset($tindakanTerapis) && $tindakanTerapis->count())
										@foreach($tindakanTerapis as $t)
											<option value="{{ $t->idkode_tindakan_terapi }}" {{ old('idkode_tindakan_terapi') == $t->idkode_tindakan_terapi ? 'selected' : '' }}>{{ $t->kode }} - {{ $t->deskripsi_tindakan_terapi }}</option>
										@endforeach
									@endif
								</select>
								@error('idkode_tindakan_terapi')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="mb-3">
								<label for="idpet" class="form-label">Pet <span class="text-danger">*</span></label>
								<select name="idpet" id="idpet" class="form-select @error('idpet') is-invalid @enderror" required>
									<option value="">-- Pilih Hewan --</option>
									@foreach($pets as $p)
										<option value="{{ $p->idpet }}" {{ (isset($selectedPet) && $selectedPet == $p->idpet) || old('idpet') == $p->idpet ? 'selected' : '' }}>
											{{ $p->nama }}{{ optional(optional($p->pemilik)->user)->name ? ' ('.optional(optional($p->pemilik)->user)->name.')' : (optional($p->pemilik)->nama ? '('.optional($p->pemilik)->nama.')' : '') }}
										</option>
									@endforeach
								</select>
								@error('idpet')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="mb-3">
								<label for="dokter_pemeriksa" class="form-label">Dokter Pemeriksa (opsional)</label>
								<select name="dokter_pemeriksa" id="dokter_pemeriksa" class="form-select @error('dokter_pemeriksa') is-invalid @enderror">
									<option value="">-- Pilih Dokter --</option>
									@foreach($dokters as $d)
										<option value="{{ $d->idrole_user }}" {{ old('dokter_pemeriksa') == $d->idrole_user ? 'selected' : '' }}>{{ $d->user->name ?? $d->idrole_user }}</option>
									@endforeach
								</select>
								@error('dokter_pemeriksa')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>

							<div class="card-footer p-0 mt-2">
								<a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
								<button type="submit" class="btn btn-success ms-2">Simpan</button>
							</div>
						</form>
					</div>
				</div>
				@endunless

				<div class="card mb-3">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">Informasi Umum</h5>
						<a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
					</div>
					<div class="card-body">
						<dl class="row mb-0">
							<dt class="col-sm-4">Waktu Kunjungan</dt>
							<dd class="col-sm-8">{{ isset($rekamMedis) && $rekamMedis->created_at ? \Carbon\Carbon::parse($rekamMedis->created_at)->format('d F Y, H:i') : '-' }}</dd>

							<dt class="col-sm-4">Nama Pasien</dt>
							<dd class="col-sm-8">{{ isset($rekamMedis) ? (optional($rekamMedis->pet)->nama ?? '-') : (isset($petSelected) ? $petSelected->nama : '-') }}</dd>

							<dt class="col-sm-4">Nama Pemilik</dt>
							<dd class="col-sm-8">{{ isset($rekamMedis) ? (optional(optional(optional($rekamMedis->pet)->pemilik)->user)->name ?? (optional(optional($rekamMedis->pet)->pemilik)->nama ?? '-') ) : (isset($petSelected) ? (optional(optional($petSelected->pemilik)->user)->name ?? (optional($petSelected->pemilik)->nama ?? '-')) : '-') }}</dd>


						</dl>
					</div>
				</div>

				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">Tindakan & Terapi</h5>
						@if(isset($rekamMedis))
							<a href="{{ route('dokter.rekam-medis.edit', $rekamMedis->idrekam_medis) }}" class="btn btn-sm btn-primary">+ Tambah Tindakan / Terapi</a>
						@endif
					</div>
					<div class="card-body">
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
									@forelse($detailTindakan as $i => $d)
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
				</div>
			</div>

			<div class="col-md-4">
				<div class="card">
					<div class="card-header"><h5 class="mb-0">Hasil Pemeriksaan</h5></div>
					<div class="card-body">
						<dl class="row mb-0">
							<dt class="col-sm-5">Anamnesa</dt>
							<dd class="col-sm-7">{{ isset($rekamMedis) ? ($rekamMedis->anamnesa ?? '-') : (old('anamnesa') ?? '-') }}</dd>

							<dt class="col-sm-5">Temuan Klinis</dt>
							<dd class="col-sm-7">{{ isset($rekamMedis) ? ($rekamMedis->temuan_klinis ?? '-') : (old('temuan_klinis') ?? '-') }}</dd>

							<dt class="col-sm-5">Diagnosa</dt>
							<dd class="col-sm-7">{{ isset($rekamMedis) ? ($rekamMedis->diagnosa ?? '-') : (old('diagnosa') ?? '-') }}</dd>
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
