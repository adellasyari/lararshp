
@extends('layouts.lte.main')

@section('title', 'Dashboard Resepsionis')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="m-0">Dashboard Resepsionis</h3>
            <small class="text-muted">Ringkasan cepat aktivitas</small>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted">Total Pemilik</div>
                        <div class="h4">{{ isset($pemilikCount) ? $pemilikCount : (isset($pemiliks) ? count($pemiliks) : '—') }}</div>
                        <a href="{{ route('resepsionis.pemilik.index') }}" class="small">Kelola Pemilik</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-success">
                        <i class="bi bi-paw"></i>
                    </div>
                    <div>
                        <div class="text-muted">Total Hewan</div>
                        <div class="h4">{{ isset($petCount) ? $petCount : (isset($pets) ? count($pets) : '—') }}</div>
                        <a href="{{ route('resepsionis.hewan.index') }}" class="small">Kelola Hewan</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 display-6 text-warning">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-muted">Janji Temu Dokter</div>
                        <div class="h4">{{ isset($temuCount) ? $temuCount : (isset($temuDokters) ? count($temuDokters) : '—') }}</div>
                        <a href="{{ route('resepsionis.temu-dokter.index') }}" class="small">Kelola Janji</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Activity Overview</h5>
                    <div class="text-muted"><small>Ringkasan hari ini</small></div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">Ringkasan singkat aktivitas pendaftaran dan janji temu.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Recent Pemilik</h5></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Nama</th><th class="text-end">No. HP</th></tr>
                        </thead>
                        <tbody>
                            @forelse($pemiliks ?? [] as $p)
                            <tr>
                                <td>{{ $p->nama ?? ($p->user->name ?? '—') }}</td>
                                <td class="text-end">{{ $p->no_wa ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted p-3">No recent owners</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Quick Actions</h5></div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-outline-primary">Kelola Pemilik</a>
                        <a href="{{ route('resepsionis.hewan.index') }}" class="btn btn-outline-success">Kelola Hewan</a>
                        <a href="{{ route('resepsionis.temu-dokter.create') }}" class="btn btn-outline-warning">Buat Janji Baru</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Summary</h5></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li>Pemilik: <strong>{{ isset($pemilikCount) ? $pemilikCount : (isset($pemiliks) ? count($pemiliks) : 0) }}</strong></li>
                        <li>Hewan: <strong>{{ isset($petCount) ? $petCount : (isset($pets) ? count($pets) : 0) }}</strong></li>
                        <li>Temu Dokter: <strong>{{ isset($temuCount) ? $temuCount : (isset($temuDokters) ? count($temuDokters) : 0) }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

