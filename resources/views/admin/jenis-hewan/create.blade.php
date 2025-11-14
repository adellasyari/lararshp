@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Tambah Jenis Hewan</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Master Data</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.jenis-hewan.index') }}">Jenis Hewan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </div>
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Form Tambah Jenis Hewan</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" action="{{ route('admin.jenis-hewan.store') }}">
            @csrf
            <div class="card-body">
              @if (session('error'))
                <div class="alert alert-danger">
                  <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                </div>
              @endif

              <div class="form-group mb-3">
                <label for="nama_jenis_hewan" class="form-label">Nama Jenis Hewan <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('nama_jenis_hewan') is-invalid @enderror" 
                       id="nama_jenis_hewan" 
                       name="nama_jenis_hewan" 
                       placeholder="Masukkan nama jenis hewan"
                       value="{{ old('nama_jenis_hewan') }}"
                       required>
                @error('nama_jenis_hewan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                  Contoh: Anjing, Kucing, Kelinci, Hamster, dll.
                </small>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Simpan
              </button>
              <a href="{{ route('admin.jenis-hewan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
      
      <div class="col-md-4">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Informasi</h3>
          </div>
          <div class="card-body">
            <p><strong>Panduan Pengisian:</strong></p>
            <ul class="list-unstyled">
              <li><i class="bi bi-check-circle text-success"></i> Nama jenis hewan wajib diisi</li>
              <li><i class="bi bi-check-circle text-success"></i> Gunakan nama yang mudah dipahami</li>
              <li><i class="bi bi-check-circle text-success"></i> Hindari singkatan yang membingungkan</li>
            </ul>
            
            <p class="mt-3"><strong>Contoh Jenis Hewan:</strong></p>
            <div class="list-group list-group-flush">
              <div class="list-group-item px-0 py-1">
                <small class="text-muted">• Anjing</small>
              </div>
              <div class="list-group-item px-0 py-1">
                <small class="text-muted">• Kucing</small>
              </div>
              <div class="list-group-item px-0 py-1">
                <small class="text-muted">• Kelinci</small>
              </div>
              <div class="list-group-item px-0 py-1">
                <small class="text-muted">• Hamster</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto focus on first input
  const firstInput = document.getElementById('nama_jenis_hewan');
  if (firstInput) {
    firstInput.focus();
  }

  // Form validation
  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
    const namaJenisHewan = document.getElementById('nama_jenis_hewan').value.trim();
    
    if (!namaJenisHewan) {
      e.preventDefault();
      alert('Nama jenis hewan wajib diisi!');
      document.getElementById('nama_jenis_hewan').focus();
      return false;
    }
  });
});
</script>
@endsection
