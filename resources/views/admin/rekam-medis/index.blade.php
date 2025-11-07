@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rekam Medis</h4>
                    <a href="{{ route('admin.rekam-medis.create') }}" class="btn btn-primary">Tambah Rekam Medis</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
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
                                <th>Anamnesa</th>
                                <th>Temuan Klinis</th>
                                <th>Nama Pet</th>
                                <th>Pemilik Pet</th>
                                <th>Ras Hewan</th>
                                <th>Dokter Pemeriksa</th>
                                <th>Diagnosa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekamMedis as $rm)
                                <tr>
                                    <td>{{ $rm->idrekam_medis }}</td>
                                    <td>{{ $rm->created_at ? \Carbon\Carbon::parse($rm->created_at)->format('d-m-Y') : '-' }}</td>

                                    <td>{{ Illuminate\Support\Str::limit($rm->anamnesa ?? '-', 80) }}</td>
                                    <td>{{ Illuminate\Support\Str::limit($rm->temuan_klinis ?? '-', 80) }}</td>

                                    <td>{{ $rm->pet->nama ?? 'Pet Dihapus' }}</td>
                                    <td>{{ $rm->pet->pemilik->user->nama ?? 'Pemilik Dihapus' }}</td>
                                    <td>{{ $rm->pet->rasHewan->nama_ras ?? 'Ras Dihapus' }}</td>

                                    <td>{{ $rm->dokter->nama ?? 'Dokter Dihapus' }}</td>

                                    <td>{{ $rm->diagnosa ?? '-' }}</td>
                                    
                                    <td>
                                        <a href="{{ route('admin.rekam-medis.edit', $rm->idrekam_medis) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.rekam-medis.destroy', $rm->idrekam_medis) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Data rekam medis kosong.</td>
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
