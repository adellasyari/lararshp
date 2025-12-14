@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Daftar Pet (Hewan Peliharaan)</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pemilik & Pet</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pet</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Tabel Data Pet</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.pet.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Pet</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm ms-2"><i class="bi bi-arrow-left"></i> Dashboard</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:10px">#</th>
                                    <th>Nama Hewan</th>
                                    <th>Pemilik</th>
                                    <th>Jenis Hewan</th>
                                    <th>Ras Hewan</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Warna/Tanda</th>
                                    <th>Jenis Kelamin</th>
                                    <th style="width:150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pet as $index => $item)
                                <tr class="align-middle">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->pemilik->user->name ?? 'User Dihapus' }}</td>
                                    <td>{{ $item->rasHewan->jenisHewan->nama_jenis_hewan ?? 'Jenis Dihapus' }}</td>
                                    <td>{{ $item->rasHewan->nama_ras ?? $item->rasHewan->nama_ras_hewan ?? 'Ras Dihapus' }}</td>
                                    <td>{{ $item->tanggal_lahir }}</td>
                                    <td>{{ $item->warna_tanda }}</td>
                                    <td>
                                        @php $jk = $item->jenis_kelamin ?? ''; @endphp
                                        @if($jk === 'J' || $jk === 'L')
                                            Jantan
                                        @elseif($jk === 'B' || $jk === 'P')
                                            Betina
                                        @else
                                            {{ $jk }}
                                        @endif
                                    </td>
                                    <td>
                                        @php $key = method_exists($item, 'getKey') ? $item->getKey() : ($item->idpet ?? null); @endphp
                                        @if(!empty($key))
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.pet.edit', ['id' => $key]) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <form action="{{ route('admin.pet.destroy', ['id' => $key]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                            <h5 class="text-muted">Data hewan masih kosong</h5>
                                            <a href="{{ route('admin.pet.create') }}" class="btn btn-primary mt-2"><i class="bi bi-plus-circle"></i> Tambah Pet</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endsection