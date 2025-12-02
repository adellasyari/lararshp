@extends('layouts.lte.main')

@section('title','Rekam Medis - Perawat')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Rekam Medis - Perawat</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('perawat.dashboard') }}">Home</a></li>
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
                        <h3 class="card-title">Daftar Pasien (Antrian)</h3>
                        <div class="card-tools">
                            <a href="{{ route('perawat.dashboard') }}" class="btn btn-secondary btn-sm ms-2"><i class="bi bi-arrow-left"></i> Dashboard</a>
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
                                    <th>Nama Hewan</th>
                                    <th>Jenis / Ras</th>
                                    <th>Pemilik</th>
                                    <th>No. HP</th>
                                    <th style="width:220px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pets as $pet)
                                <tr>
                                    <td>{{ $pet->nama }}</td>
                                    <td>
                                        {{ optional(optional($pet->rasHewan)->jenisHewan)->nama_jenis_hewan ?? '-' }} / 
                                        {{ optional($pet->rasHewan)->nama_ras ?? optional($pet->rasHewan)->nama_ras_hewan ?? '-' }}
                                    </td>
                                    <td>{{ optional(optional($pet->pemilik)->user)->name ?? '-' }}</td>
                                    <td>{{ optional($pet->pemilik)->no_wa ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('perawat.rekam-medis.create') }}?idpet={{ $pet->idpet }}" class="btn btn-sm btn-primary"><i class="bi bi-person-check"></i> Periksa</a>
                                            @php $last = $pet->rekamMedis()->orderBy('created_at','desc')->first(); @endphp
                                            @if($last)
                                                {{-- pass the rekam_medis primary key explicitly to avoid accidental passing of unrelated ids --}}
                                                <a href="{{ route('perawat.rekam-medis.show', $last->idrekam_medis) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Detail</a>
                                                <a href="{{ route('perawat.rekam-medis.edit', $last->idrekam_medis) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                            @else
                                                <span class="text-muted small">Belum ada rekam</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="py-3">
                                            <i class="bi bi-inbox display-4 text-muted mb-2"></i>
                                            <div class="text-muted">Tidak ada pasien dalam antrian.</div>
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
