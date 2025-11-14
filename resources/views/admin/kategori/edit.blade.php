@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Kategori</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
            <div class="col-md-8">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Kategori</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.kategori.update', $kategori->idkategori) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text"
                                             class="form-control @error('nama_kategori') is-invalid @enderror"
                                             id="nama_kategori"
                                             name="nama_kategori"
                                             placeholder="Masukkan nama kategori"
                                             value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                             required>
                                @error('nama_kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Contoh: Umum, Darurat, Rawat Jalan, dll.</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-repeat"></i> Update
                            </button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Edit</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Data Saat Ini:</strong></p>
                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted">Nama Kategori:</small><br>
                            <strong>{{ $kategori->nama_kategori }}</strong>
                        </div>
                        <p><strong>Panduan Pengisian:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Nama kategori wajib diisi</li>
                            <li><i class="bi bi-check-circle text-success"></i> Gunakan nama yang jelas dan deskriptif</li>
                        </ul>
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i>
                            <small><strong>Perhatian:</strong> Perubahan data ini akan mempengaruhi semua data terkait yang menggunakan kategori ini.</small>
                        </div>
                    </div>
                </div>
                <div class="card card-secondary mt-2">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Data</h3>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>ID:</strong> {{ $kategori->idkategori }}<br>
                            <strong>Dibuat:</strong> {{ $kategori->created_at ? $kategori->created_at->format('d/m/Y H:i') : '-' }}<br>
                            <strong>Diupdate:</strong> {{ $kategori->updated_at ? $kategori->updated_at->format('d/m/Y H:i') : '-' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.getElementById('nama_kategori');
    if (firstInput) {
        firstInput.focus();
        firstInput.select();
    }

    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const val = (document.getElementById('nama_kategori').value || '').trim();
        if (!val) {
            e.preventDefault();
            alert('Nama kategori wajib diisi!');
            document.getElementById('nama_kategori').focus();
            return false;
        }
        if (!confirm('Apakah Anda yakin ingin mengupdate data kategori ini?')) {
            e.preventDefault();
            return false;
        }
    });

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
});
</script>
@endsection
