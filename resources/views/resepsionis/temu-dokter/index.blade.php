@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Temu Dokter</h4>
                </div>
                <div class="card-body">
                    <p>Daftar temu dokter yang telah dijadwalkan.</p>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Hewan</th>
                                <th>Nama Pemilik</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($temuDokters as $temuDokter)
                                <tr>
                                    <td>{{ $temuDokter->id }}</td>
                                    <td>{{ $temuDokter->pet->nama ?? 'N/A' }}</td>
                                    <td>{{ $temuDokter->pemilik->user->name ?? 'N/A' }}</td>
                                    <td>{{ $temuDokter->tanggal }}</td>
                                    <td>{{ $temuDokter->waktu }}</td>
                                    <td>
                                        <span class="badge
                                            @if($temuDokter->status == 'pending') badge-warning
                                            @elseif($temuDokter->status == 'confirmed') badge-success
                                            @elseif($temuDokter->status == 'completed') badge-info
                                            @elseif($temuDokter->status == 'cancelled') badge-danger
                                            @endif">
                                            {{ ucfirst($temuDokter->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data temu dokter.</td>
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
