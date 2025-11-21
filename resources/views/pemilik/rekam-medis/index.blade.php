@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Rekam Medis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('pemilik.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rekam Medis</li>
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
                        <h3 class="card-title">Daftar Rekam Medis</h3>
                        <div class="card-tools">
                            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary btn-sm ms-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @php $list = $rekamMediss ?? collect(); @endphp

                        @if($list->isEmpty())
                            <div class="d-flex flex-column align-items-center py-4 text-center">
                                <i class="bi bi-file-medical display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada rekam medis</h5>
                                <p class="text-muted">Rekam medis untuk hewan Anda belum tersedia.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:10px">#</th>
                                            <th>Tanggal</th>
                                            <th>Nama Hewan</th>
                                            <th>Anamnesa</th>
                                            <th>Temuan Klinis</th>
                                            <th>Tindakan</th>
                                            <th>Obat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list as $i => $rekamMedis)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ optional($rekamMedis->created_at)->format('Y-m-d') ?? '-' }}</td>
                                                <td>{{ optional($rekamMedis->pet)->nama ?? 'N/A' }}</td>
                                                <td>{{ $rekamMedis->anamnesa ?? '-' }}</td>
                                                <td>{{ $rekamMedis->temuan_klinis ?? '-' }}</td>
                                                <td>{{ $rekamMedis->tindakan ?? '-' }}</td>
                                                <td>{{ $rekamMedis->obat ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if($list->count() > 0)
                    <div class="card-footer clearfix">
                        <div class="float-start">Menampilkan {{ $list->count() }} rekam medis</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::App Content-->

@endsection
