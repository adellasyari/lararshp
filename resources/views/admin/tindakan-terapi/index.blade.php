@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Daftar Kode Tindakan Terapi</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Tindakan Terapi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center position-relative">
                        <h3 class="card-title mb-0">Tabel Tindakan Terapi</h3>
                        <div class="ms-auto d-flex gap-2">
                            <a href="{{ route('admin.tindakan-terapi.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Tindakan</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm"><i class="bi bi-speedometer2"></i> Dashboard</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:10px">#</th>
                                    <th>ID</th>
                                    <th>Kode</th>
                                    <th>Deskripsi Tindakan</th>
                                    <th>Kategori</th>
                                    <th>Kategori Klinis</th>
                                    <th style="width:150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tindakanTerapi as $index => $tindakan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tindakan->idkode_tindakan_terapi }}</td>
                                    <td>{{ $tindakan->kode }}</td>
                                    <td>{{ $tindakan->deskripsi_tindakan_terapi }}</td>
                                    <td>{{ $tindakan->kategori ? $tindakan->kategori->nama_kategori : $tindakan->idkategori }}</td>
                                    <td>{{ $tindakan->kategoriKlinis ? $tindakan->kategoriKlinis->nama_kategori_klinis : $tindakan->idkategori_klinis }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.tindakan-terapi.edit', $tindakan->idkode_tindakan_terapi) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <form action="{{ route('admin.tindakan-terapi.destroy', $tindakan->idkode_tindakan_terapi) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus tindakan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-3">
                                            <i class="bi bi-inbox display-4 text-muted mb-2"></i>
                                            <div class="text-muted">Data tindakan terapi masih kosong.</div>
                                            <a href="{{ route('admin.tindakan-terapi.create') }}" class="btn btn-primary mt-2">Tambah Tindakan</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) { setTimeout(function() { const bsAlert = new bootstrap.Alert(alert); bsAlert.close(); }, 5000); });
});
</script>
@endsection
