@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rekam Medis</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Hewan</th>
                                <th>Anamnesa</th>
                                <th>Temuan Klinis</th>
                                <th>Tindakan</th>
                                <th>Obat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekamMediss as $rekamMedis)
                                <tr>
                                    <td>{{ $rekamMedis->created_at }}</td>
                                    <td>{{ $rekamMedis->pet->nama ?? 'N/A' }}</td>
                                    <td>{{ $rekamMedis->anamnesa }}</td>
                                    <td>{{ $rekamMedis->temuan_klinis }}</td>
                                    <td>{{ $rekamMedis->tindakan }}</td>
                                    <td>{{ $rekamMedis->obat }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data rekam medis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
