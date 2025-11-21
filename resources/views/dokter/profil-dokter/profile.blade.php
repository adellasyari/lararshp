@extends('layouts.lte.main')

@section('title','Profil Dokter')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Profil Saya</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profil</li>
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
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Detail Profil</h5>
                        <a href="{{ route('dokter.edit', $dokter->id_dokter ?? null) }}" class="btn btn-primary btn-sm">Edit Profil</a>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Nama</dt>
                            <dd class="col-sm-9">{{ $user->nama ?? $user->email }}</dd>

                            <dt class="col-sm-3">Email</dt>
                            <dd class="col-sm-9">{{ $user->email }}</dd>

                            <dt class="col-sm-3">No. HP</dt>
                            <dd class="col-sm-9">{{ $dokter->no_hp ?? '-' }}</dd>

                            <dt class="col-sm-3">Alamat</dt>
                            <dd class="col-sm-9">{{ $dokter->alamat ?? '-' }}</dd>

                            <dt class="col-sm-3">Bidang</dt>
                            <dd class="col-sm-9">{{ $dokter->bidang_dokter ?? '-' }}</dd>

                            <dt class="col-sm-3">Jenis Kelamin</dt>
                            <dd class="col-sm-9">{{ $dokter->jenis_kelamin ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
