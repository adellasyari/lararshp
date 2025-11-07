@extends('layouts.app')
@section('title', 'Daftar Pet (Hewan Peliharaan)')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Daftar Pet (Hewan Peliharaan)</h4>
                        <div>
                            <a href="{{ route('admin.pet.create') }}" class="btn btn-primary me-2"><i class="fas fa-plus"></i> Tambah Pet</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

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
                                    <th>Aksi</th>
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
                                        <td>
                                            @php $key = method_exists($item, 'getKey') ? $item->getKey() : ($item->idpet ?? null); @endphp
                                            @if(!empty($key))
                                                <a href="{{ route('admin.pet.edit', ['id' => $key]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>

                                                <form action="{{ route('admin.pet.destroy', ['id' => $key]) }}" method="POST" style="display:inline-block; margin-left:6px;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Data hewan masih kosong.</td>
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