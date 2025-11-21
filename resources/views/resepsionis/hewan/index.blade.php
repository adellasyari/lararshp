@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Hewan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hewan</li>
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
                    <div class="card-header">
                        <h3 class="card-title">Tabel Data Hewan</h3>
                        <div class="card-tools">
                            <a href="{{ route('resepsionis.hewan.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Tambah Hewan
                            </a>
                            <a href="{{ route('resepsionis.dashboard') }}" class="btn btn-secondary btn-sm ms-2">
                                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @php $list = $pets ?? collect(); @endphp

                        @if($list->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Nama Hewan</th>
                                        <th>Jenis</th>
                                        <th>Ras</th>
                                        <th>Nama Pemilik</th>
                                        <th style="width:150px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $index => $item)
                                        @php $pid = $item->idpet ?? $item->id ?? (method_exists($item,'getKey') ? $item->getKey() : ''); @endphp
                                        <tr class="align-middle">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->nama ?? '-' }}</td>
                                            <td>{{ $item->rasHewan->jenisHewan->nama_jenis_hewan ?? ($item->jenis ?? '-') }}</td>
                                            <td>{{ $item->rasHewan->nama_ras ?? ($item->ras ?? '-') }}</td>
                                            <td>{{ $item->pemilik->user->nama ?? $item->pemilik->user->name ?? 'User Dihapus' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ $pid ? route('resepsionis.hewan.edit', $pid) : '#' }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    <form action="{{ $pid ? route('resepsionis.hewan.destroy', $pid) : '#' }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(this);">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="d-flex flex-column align-items-center py-4 text-center">
                                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">Data hewan masih kosong</h5>
                                <p class="text-muted">Silakan tambah data hewan baru</p>
                                <a href="{{ route('resepsionis.hewan.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Hewan
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($list->count() > 0)
                    <div class="card-footer clearfix">
                        <div class="float-start">Menampilkan {{ $list->count() }} data hewan</div>
                        <div class="float-end">
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

function confirmDelete(form) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
        form.submit();
    }
    return false;
}
</script>
@endsection
