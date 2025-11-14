@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Rekam Medis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Rekam Medis</li>
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
                    <div class="card-header">
                        <h3 class="card-title">Tabel Rekam Medis</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.rekam-medis.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Rekam Medis</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm ms-2"><i class="bi bi-arrow-left"></i> Dashboard</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:80px">ID</th>
                                    <th>Tanggal</th>
                                    <th>Anamnesa</th>
                                    <th>Temuan Klinis</th>
                                    <th>Nama Pet</th>
                                    <th>Pemilik Pet</th>
                                    <th>Ras Hewan</th>
                                    <th>Dokter Pemeriksa</th>
                                    <th>Diagnosa</th>
                                    <th style="width:150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekamMedis as $rm)
                                <tr>
                                    <td>{{ $rm->idrekam_medis }}</td>
                                    <td>{{ $rm->created_at ? \Carbon\Carbon::parse($rm->created_at)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ Illuminate\Support\Str::limit($rm->anamnesa ?? '-', 80) }}</td>
                                    <td>{{ Illuminate\Support\Str::limit($rm->temuan_klinis ?? '-', 80) }}</td>
                                    <td>{{ $rm->pet->nama ?? 'Pet Dihapus' }}</td>
                                    <td>{{ $rm->pet->pemilik->user->nama ?? 'Pemilik Dihapus' }}</td>
                                    <td>{{ $rm->pet->rasHewan->nama_ras ?? 'Ras Dihapus' }}</td>
                                    <td>{{ $rm->dokter->nama ?? 'Dokter Dihapus' }}</td>
                                    <td>{{ $rm->diagnosa ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.rekam-medis.edit', $rm->idrekam_medis) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <form action="{{ route('admin.rekam-medis.destroy', $rm->idrekam_medis) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="py-3">
                                            <i class="bi bi-inbox display-4 text-muted mb-2"></i>
                                            <div class="text-muted">Data rekam medis kosong.</div>
                                            <a href="{{ route('admin.rekam-medis.create') }}" class="btn btn-primary mt-2">Tambah Rekam Medis</a>
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
    alerts.forEach(function(alert) {
        setTimeout(function() { const bsAlert = new bootstrap.Alert(alert); bsAlert.close(); }, 5000);
    });
});
</script>
@endsection
