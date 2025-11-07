@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Hewan</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Hewan</th>
                                <th>Pemilik</th>
                                <th>Jenis Hewan</th>
                                <th>Ras Hewan</th>
                                <th>Tanggal Lahir</th>
                                <th>Warna/Tanda</th>
                                <th>Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pet as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->pemilik->user->nama ?? 'User Dihapus' }}</td>
                                <td>{{ $item->rasHewan->jenisHewan->nama_jenis_hewan ?? 'Jenis Dihapus' }}</td>
                                <td>{{ $item->rasHewan->nama_ras ?? $item->rasHewan->nama_ras_hewan ?? 'Ras Dihapus' }}</td>
                                <td>{{ $item->tanggal_lahir }}</td>
                                <td>{{ $item->warna_tanda }}</td>
                                <td>{{ $item->jenis_kelamin }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center;">Data hewan masih kosong.</td>
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
