@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Tindakan Terapi</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Tindakan</th>
                                <th>Kategori</th>
                                <th>Kategori Klinis</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tindakanTerapis as $tindakan)
                            <tr>
                                <td>{{ $tindakan->idkode_tindakan_terapi }}</td>
                                <td>{{ $tindakan->deskripsi_tindakan_terapi }}</td>
                                <td>{{ $tindakan->kategori->nama_kategori ?? 'N/A' }}</td>
                                <td>{{ $tindakan->kategoriKlinis->nama_kategori_klinis ?? 'N/A' }}</td>
                                <td>{{ $tindakan->deskripsi }}</td>
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
