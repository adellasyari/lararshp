@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Dashboard Pemilik</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard Pemilik</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @php
            $rekamCount = isset($rekamMediss) ? count($rekamMediss) : 0;
            $petsCount = isset($pets) ? count($pets) : 0;
            $appointmentsCount = isset($temuDokters) ? count($temuDokters) : 0;
            $recentRecords = isset($rekamMediss) ? $rekamMediss->slice(0,5) : collect();
        @endphp

        <!-- Summary cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-4 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon bg-primary text-white rounded-circle p-3">
                            <i class="bi bi-file-medical fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $rekamCount }}</h4>
                            <small class="text-muted">Rekam Medis</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon bg-success text-white rounded-circle p-3">
                            <i class="bi bi-journal-medical fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $petsCount }}</h4>
                            <small class="text-muted">Hewan Saya</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon bg-warning text-dark rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $appointmentsCount }}</h4>
                            <small class="text-muted">Janji / Kunjungan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Activity Overview</h5>
                        <div class="text-muted"><small>Ringkasan singkat</small></div>
                    </div>
                    <div class="card-body">
                        <canvas id="overviewChart" style="height:320px;"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Recent Rekam Medis</h5>
                        <div class="text-muted"><small>Terakhir</small></div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr><th>Hewan</th><th class="text-end">Tanggal</th></tr>
                            </thead>
                            <tbody>
                                @forelse($recentRecords as $r)
                                <tr>
                                    <td>{{ optional($r->pet)->nama ?? ($r->nama ?? '—') }}</td>
                                    <td class="text-end">{{ optional($r->created_at)->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted p-3">Belum ada rekam medis</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('pemilik.rekam-medis.index') }}" class="btn btn-outline-primary">Lihat Rekam Medis</a>
                            <a href="{{ route('pemilik.pet.index') }}" class="btn btn-outline-success">Hewan Saya</a>
                            <a href="{{ route('pemilik.temu-dokter.index') }}" class="btn btn-outline-warning">Jadwal Kunjungan</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Info Singkat</h5></div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">Gunakan menu di atas untuk mengelola hewan dan melihat rekam medis serta jadwal kunjungan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Simple overview chart placeholder
const ctx = document.getElementById('overviewChart')?.getContext('2d');
if (ctx) {
    const overviewChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [
                { label: 'Rekam Medis', data: [3,2,4,1,5,2], borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.08)', fill: true, tension: 0.3 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}
</script>
@endsection
