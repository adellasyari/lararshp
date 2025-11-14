@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Kategori Klinis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kategori-klinis.index') }}">Kategori Klinis</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Kategori Klinis</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.kategori-klinis.store') }}">
                        @csrf
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="nama_kategori_klinis" class="form-label">Nama Kategori Klinis <span class="text-danger">*</span></label>
                                <input type="text"
                                             class="form-control @error('nama_kategori_klinis') is-invalid @enderror"
                                             id="nama_kategori_klinis"
                                             name="nama_kategori_klinis"
                                             placeholder="Masukkan nama kategori klinis"
                                             value="{{ old('nama_kategori_klinis') }}"
                                             required>
                                @error('nama_kategori_klinis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                            <a href="{{ route('admin.kategori-klinis.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header"><h3 class="card-title">Informasi</h3></div>
                    <div class="card-body">
                        <p><strong>Panduan Pengisian:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Nama kategori klinis wajib diisi</li>
                            <li><i class="bi bi-check-circle text-success"></i> Gunakan nama yang jelas dan deskriptif</li>
                        </ul>
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
    const firstInput = document.getElementById('nama_kategori_klinis');
    if (firstInput) firstInput.focus();

    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const val = (document.getElementById('nama_kategori_klinis').value || '').trim();
        if (!val) {
            e.preventDefault();
            alert('Nama kategori klinis wajib diisi!');
            document.getElementById('nama_kategori_klinis').focus();
        }
    });
});
</script>
@endsection
