@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Hewan Saya</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>Warna Tanda</th>
                                <th>Jenis Kelamin</th>
                                <th>Ras Hewan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pets as $pet)
                                <tr>
                                    <td>{{ $pet->nama }}</td>
                                    <td>{{ $pet->tanggal_lahir }}</td>
                                    <td>{{ $pet->warna_tanda }}</td>
                                    <td>{{ $pet->jenis_kelamin }}</td>
                                    <td>{{ $pet->rasHewan->nama_ras ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data hewan.</td>
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
