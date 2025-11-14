@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Daftar Ras Hewan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ras Hewan</li>
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
                        <h3 class="card-title">Tabel Ras Hewan</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.ras-hewan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Ras Hewan</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm ms-2"><i class="bi bi-arrow-left"></i> Dashboard</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama Ras Hewan</th>
                                    <th>Jenis Hewan</th>
                                    <th style="width:160px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_ras as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_ras ?? $item->nama_ras_hewan ?? $item->idras_hewan }}</td>
                                    <td>{{ $item->jenisHewan->nama_jenis_hewan ?? 'Jenis Tidak Ditemukan' }}</td>
                                    <td>
                                        @php $key = method_exists($item, 'getKey') ? $item->getKey() : ($item->idras_hewan ?? null); @endphp
                                        @if($key)
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.ras-hewan.edit', ['id' => $key]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <form action="{{ route('admin.ras-hewan.destroy', ['id' => $key]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="py-3">
                                            <i class="bi bi-inbox display-4 text-muted mb-2"></i>
                                            <div class="text-muted">Data ras hewan masih kosong.</div>
                                            <a href="{{ route('admin.ras-hewan.create') }}" class="btn btn-primary mt-2">Tambah Ras Hewan</a>
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