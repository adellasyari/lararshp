@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Jadwal Kunjungan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('pemilik.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jadwal Kunjungan</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Tabel Jadwal Kunjungan</h3>
                        <div class="card-tools">
                            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary btn-sm ms-2">
                                <i class="bi bi-arrow-left"></i> Kembali
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

                        @php $list = $temuDokters ?? collect(); @endphp

                        @if($list->isEmpty())
                            <div class="d-flex flex-column align-items-center py-4 text-center">
                                <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada jadwal kunjungan</h5>
                                <p class="text-muted">Jika Anda ingin mendaftar temu dokter, silakan hubungi resepsionis.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:10px">#</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>No. Urut</th>
                                            <th>Nama Hewan</th>
                                            <th>Dokter</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list as $index => $t)
                                            <tr class="align-middle">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $t->waktu_daftar ? \Illuminate\Support\Carbon::parse($t->waktu_daftar)->format('Y-m-d') : ($t->tanggal ?? '-') }}</td>
                                                <td>{{ $t->waktu ?? ($t->waktu_daftar ? \Illuminate\Support\Carbon::parse($t->waktu_daftar)->format('H:i') : '-') }}</td>
                                                <td>{{ $t->no_urut ?? '-' }}</td>
                                                <td>{{ optional($t->pet)->nama ?? '-' }}</td>
                                                <td>{{ optional($t->dokter->user)->nama ?? (optional($t->dokter)->nama ?? '-') }}</td>
                                                <td>{{ $t->status ?? 'Terdaftar' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if($list->count() > 0)
                    <div class="card-footer clearfix">
                        <div class="float-start">Menampilkan {{ $list->count() }} jadwal</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->

@endsection
