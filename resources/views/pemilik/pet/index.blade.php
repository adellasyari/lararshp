@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Hewan Saya</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('pemilik.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hewan Saya</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Hewan Saya</h3>
                        <div class="card-tools">
                            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary btn-sm ms-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @php $list = $pets ?? collect(); @endphp

                        @if($list->isEmpty())
                            <div class="d-flex flex-column align-items-center py-4 text-center">
                                <i class="bi bi-paw display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data hewan</h5>
                                <p class="text-muted">Tambahkan data hewan Anda melalui resepsionis atau menu pendaftaran.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:10px">#</th>
                                            <th>Nama</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Warna / Tanda</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Ras</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list as $i => $pet)
                                            @php $key = method_exists($pet,'getKey') ? $pet->getKey() : ($pet->idpet ?? null); @endphp
                                            <tr class="align-middle">
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $pet->nama ?? '-' }}</td>
                                                <td>{{ $pet->tanggal_lahir ?? '-' }}</td>
                                                <td>{{ $pet->warna_tanda ?? '-' }}</td>
                                                <td>{{ $pet->jenis_kelamin ?? '-' }}</td>
                                                <td>{{ optional($pet->rasHewan)->nama_ras ?? 'N/A' }}</td>
                                                <td>
                                                    @if($key)
                                                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#pet-details-{{ $key }}" aria-expanded="false" aria-controls="pet-details-{{ $key }}">Lihat</button>
                                                        @if(Route::has('pemilik.pet.edit'))
                                                            <a href="{{ route('pemilik.pet.edit', ['id' => $key]) }}" class="btn btn-sm btn-warning ms-1">Edit</a>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr class="collapse" id="pet-details-{{ $key }}">
                                                <td colspan="7">
                                                    <div class="card card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 text-center">
                                                                <img src="{{ asset('asset/img/pet-placeholder.png') }}" alt="Pet Image" class="img-fluid rounded mb-2" style="max-height:140px;">
                                                                <p class="text-muted small">ID: {{ $key ?? '-' }}</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <dl class="row mb-0">
                                                                    <dt class="col-sm-4">Nama</dt>
                                                                    <dd class="col-sm-8">{{ $pet->nama ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Pemilik</dt>
                                                                    <dd class="col-sm-8">{{ optional($pet->pemilik->user)->nama ?? optional($pet->pemilik)->nama ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Jenis Hewan</dt>
                                                                    <dd class="col-sm-8">{{ optional($pet->rasHewan->jenisHewan)->nama_jenis_hewan ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Ras</dt>
                                                                    <dd class="col-sm-8">{{ $pet->rasHewan->nama_ras ?? $pet->rasHewan->nama_ras_hewan ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Tanggal Lahir</dt>
                                                                    <dd class="col-sm-8">{{ $pet->tanggal_lahir ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Warna / Tanda</dt>
                                                                    <dd class="col-sm-8">{{ $pet->warna_tanda ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Jenis Kelamin</dt>
                                                                    <dd class="col-sm-8">{{ $pet->jenis_kelamin ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Catatan</dt>
                                                                    <dd class="col-sm-8">{{ $pet->keterangan ?? '-' }}</dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if($list->count() > 0)
                    <div class="card-footer clearfix">
                        <div class="float-start">Menampilkan {{ $list->count() }} hewan</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->

@endsection
