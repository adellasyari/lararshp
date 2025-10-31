@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rekam Medis - Perawat</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                                <th>Obat</th>
                                <th>Pet</th>
                                <th>Role User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekamMediss as $rekamMedis)
                                <tr>
                                    <td>{{ $rekamMedis->idrekam_medis }}</td>
                                    <td>{{ $rekamMedis->created_at }}</td>
                                    <td>{{ $rekamMedis->anamnesa }}</td>
                                    <td>{{ $rekamMedis->temuan_klinis }}</td>
                                    <td>{{ $rekamMedis->idpet }}</td>
                                    <td>{{ $rekamMedis->dokter_pemeriksa }}</td>
                                    <td>{{ $rekamMedis->pet ? $rekamMedis->pet->nama : 'N/A' }}</td>
                                    <td>{{ $rekamMedis->roleUser ? $rekamMedis->roleUser->idrole_user : 'N/A' }}</td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
