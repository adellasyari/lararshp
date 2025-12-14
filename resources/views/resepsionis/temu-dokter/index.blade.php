@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Temu Dokter</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Temu Dokter</li>
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Daftarkan Janji Temu Baru</h3>
                    </div>
                    <div class="card-body text-center">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <a href="{{ route('resepsionis.temu-dokter.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Buat Pendaftaran Baru
                        </a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Antrian Hari Ini</h3>
                    </div>
                    <div class="card-body">
                        @php $antrian = $antrianHariIni ?? collect(); @endphp
                        @if($antrian->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>No Antrian</th>
                                            <th>Waktu</th>
                                            <th>Nama Hewan</th>
                                            <th>Pemilik</th>
                                            <th>Dokter</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($antrian as $item)
                                            @php $tid = $item->idreservasi_dokter ?? $item->id ?? (method_exists($item,'getKey') ? $item->getKey() : ''); @endphp
                                                <tr>
                                                    <td><strong>#{{ $item->no_urut ?? $loop->iteration }}</strong></td>
                                                    <td>
                                                        @php
                                                            // Show the waktu_daftar value exactly as stored in DB
                                                            if($item->waktu_daftar) {
                                                                echo \Illuminate\Support\Carbon::parse($item->waktu_daftar)->format('d/m/Y H:i');
                                                            } else {
                                                                echo '-';
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>{{ $item->pet->nama ?? '-' }}</td>
                                                    <td>{{ optional($item->pet->pemilik->user)->name ?? '-' }}</td>
                                                    <td>{{ $item->dokter->user->name ?? $item->dokter->nama ?? '-' }}</td>
                                                    <td>
                                                        @php $s = (string)($item->status ?? ''); @endphp
                                                        @if($s === \App\Models\TemuDokter::STATUS_MENUNGGU)
                                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                                        @elseif($s === \App\Models\TemuDokter::STATUS_SELESAI)
                                                            <span class="badge bg-success">Selesai</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $item->status_label }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            {{-- Cancel / Delete form --}}
                                                            <form action="{{ route('resepsionis.temu-dokter.destroy', ['id' => $item->getKey() ?? $item->idreservasi_dokter ?? $item->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Batalkan pendaftaran ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Batal</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted">Belum ada antrian untuk hari ini.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Semua Janji Temu</h3>
                    </div>
                    <div class="card-body p-0">
                        @php $list = $riwayat ?? collect(); @endphp
                        <div style="max-height:480px; overflow:auto;">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Waktu</th>
                                        <th>Nama Hewan</th>
                                        <th>Pemilik</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($list as $r)
                                        <tr>
                                            <td>{{ $r->id ?? ($r->getKey() ?? '-') }}</td>
                                            <td>
                                                @php
                                                    if($r->waktu_daftar) {
                                                        echo \Illuminate\Support\Carbon::parse($r->waktu_daftar)->format('Y-m-d H:i');
                                                    } else {
                                                        echo '-';
                                                    }
                                                @endphp
                                            </td>
                                            <td>{{ $r->pet->nama ?? '-' }}</td>
                                            <td>{{ optional($r->pet->pemilik->user)->name ?? '-' }}</td>
                                            <td>
                                                @php $s = (string)($r->status ?? ''); @endphp
                                                @if($s === \App\Models\TemuDokter::STATUS_MENUNGGU)
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @elseif($s === \App\Models\TemuDokter::STATUS_SELESAI)
                                                    <span class="badge bg-primary">Selesai</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $r->status_label }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-muted">Riwayat kosong.</td>
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
