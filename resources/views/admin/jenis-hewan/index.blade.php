
@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Jenis Hewan</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Master Data</a></li>
          <li class="breadcrumb-item active" aria-current="page">Jenis Hewan</li>
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
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Tabel Data Jenis Hewan</h3>
            <div class="card-tools">
              <a href="{{ route('admin.jenis-hewan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Jenis Hewan
              </a>
              <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm ms-2">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
              </a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Nama Jenis Hewan</th>
                  <th style="width: 150px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($jenisHewan as $index => $hewan)
                  <tr class="align-middle">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hewan->nama_jenis_hewan }}</td>
                    <td>
                      <div class="btn-group" role="group">
                        <a href="{{ route('admin.jenis-hewan.edit', $hewan->idjenis_hewan) }}" class="btn btn-sm btn-warning">
                          <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('admin.jenis-hewan.destroy', $hewan->idjenis_hewan) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center">
                      <div class="d-flex flex-column align-items-center py-4">
                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">Data jenis hewan masih kosong</h5>
                        <p class="text-muted">Silakan tambah data jenis hewan baru</p>
                        <a href="{{ route('admin.jenis-hewan.create') }}" class="btn btn-primary">
                          <i class="bi bi-plus-circle"></i> Tambah Jenis Hewan
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          @if($jenisHewan->count() > 0)
          <div class="card-footer clearfix">
            <div class="float-start">
              Menampilkan {{ $jenisHewan->count() }} data jenis hewan
            </div>
            <div class="float-end">
              <!-- Pagination bisa ditambahkan di sini jika diperlukan -->
              <ul class="pagination pagination-sm m-0">
                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>
          @endif
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->
@endsection

@section('scripts')
<script>
// Auto hide alert after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function(alert) {
    setTimeout(function() {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });
});

// Confirmation for delete actions
function confirmDelete(form) {
  if (confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
    form.submit();
  }
  return false;
}
</script>
@endsection