@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Dokter</h4>
                </div>
                <div class="card-body">
                    <p>Selamat datang di Dashboard Dokter. Anda dapat mengelola data klinis dan hewan.</p>

                    <!-- Menu Navigasi -->
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-success w-100 p-3">
                                <i class="fas fa-notes-medical"></i> Rekam Medis
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dokter.tindakan-terapi.index') }}" class="btn btn-info w-100 p-3">
                                <i class="fas fa-briefcase-medical"></i> Tindakan Terapi
                            </a>
                        </div>
                    </div>

                    <!-- Data Summary -->
                    <hr>
                    <h5>Data Summary</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Rekam Medis</h5>
                                    <p class="text-muted">{{ count($rekamMediss) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Tindakan Terapi</h5>
                                    <p class="text-muted">{{ count($tindakans) }} data</p>
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
