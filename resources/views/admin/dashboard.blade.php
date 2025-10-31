@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Administrator</h4>
                </div>
                <div class="card-body">
                    <p>Selamat datang di Dashboard Administrator. Anda dapat mengelola semua data master sistem.</p>

                    <!-- Menu Navigasi -->
                    <div class="row mt-3">
                        <!-- Baris 1: Manajemen Utama -->
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-primary w-100 p-3">
                                <i class="fas fa-user-cog"></i> Manajemen User
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.role.index') }}" class="btn btn-primary w-100 p-3">
                                <i class="fas fa-user-tag"></i> Manajemen Role
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.pemilik.index') }}" class="btn btn-primary w-100 p-3">
                                <i class="fas fa-users"></i> Manajemen Pemilik
                            </a>
                        </div>

                        <!-- Baris 2: Manajemen Hewan -->
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.pet.index') }}" class="btn btn-success w-100 p-3">
                                <i class="fas fa-cat"></i> Manajemen Hewan (Pet)
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.jenis-hewan.index') }}" class="btn btn-success w-100 p-3">
                                <i class="fas fa-paw"></i> Jenis Hewan
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.ras-hewan.index') }}" class="btn btn-success w-100 p-3">
                                <i class="fas fa-dog"></i> Ras Hewan
                            </a>
                        </div>

                        <!-- Baris 3: Manajemen Klinis -->
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.tindakan-terapi.index') }}" class="btn btn-danger w-100 p-3">
                                <i class="fas fa-briefcase-medical"></i> Tindakan Terapi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.kategori-klinis.index') }}" class="btn btn-danger w-100 p-3">
                                <i class="fas fa-notes-medical"></i> Kategori Klinis
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-warning w-100 p-3 text-dark">
                                <i class="fas fa-tags"></i> Kategori Umum
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.rekam-medis.index') }}" class="btn btn-success w-100 p-3">
                                <i class="fas fa-file-medical"></i> Rekam Medis
                            </a>
                        </div>
                    </div>

                    <!-- Data Summary -->
                    <hr>
                    <h5>Data Summary</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Kategori</h5>
                                    <p class="text-muted">{{ count($kategoris) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Kategori Klinis</h5>
                                    <p class="text-muted">{{ count($kategoriKliniss) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Jenis Hewan</h5>
                                    <p class="text-muted">{{ count($jenisHewans) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Ras Hewan</h5>
                                    <p class="text-muted">{{ count($rasHewans) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Role</h5>
                                    <p class="text-muted">{{ count($roles) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>User</h5>
                                    <p class="text-muted">{{ count($users) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Pemilik</h5>
                                    <p class="text-muted">{{ count($pemiliks) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Hewan</h5>
                                    <p class="text-muted">{{ count($pets) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Rekam Medis</h5>
                                    <p class="text-muted">{{ count($rekamMediss) }} data</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
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
