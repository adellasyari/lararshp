
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Resepsionis</h4>
                </div>
                <div class="card-body">
                    <p>Selamat datang di Dashboard Resepsionis. Anda dapat mengelola data pendaftaran pemilik dan hewan.</p>

                    <!-- Menu Navigasi -->
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-success w-100 p-3">
                                <i class="fas fa-users"></i> Pemilik
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('resepsionis.hewan.index') }}" class="btn btn-primary w-100 p-3">
                                <i class="fas fa-paw"></i> Hewan
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('resepsionis.temu-dokter.index') }}" class="btn btn-info w-100 p-3">
                                <i class="fas fa-calendar-check"></i> Temu Dokter
                            </a>
                        </div>
                    </div>

                    <!-- Data Summary -->
                    <hr>
                    <h5>Data Summary</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Pemilik</h5>
                                    <p class="text-muted">{{ count($pemiliks) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Hewan</h5>
                                    <p class="text-muted">{{ count($pets) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Temu Dokter</h5>
                                    <p class="text-muted">{{ count($temuDokters) }} data</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
