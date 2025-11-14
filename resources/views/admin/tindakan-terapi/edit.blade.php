@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Tindakan Terapi</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tindakan-terapi.index') }}">Tindakan Terapi</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Tindakan</h3>
                    </div>
                    <div class="card-body">
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                        <form action="{{ route('admin.tindakan-terapi.update', $tindakanTerapi->idkode_tindakan_terapi) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $tindakanTerapi->kode) }}" required autofocus>
                                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_tindakan_terapi" class="form-label">Deskripsi Tindakan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_tindakan_terapi" id="deskripsi_tindakan_terapi" class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" required>{{ old('deskripsi_tindakan_terapi', $tindakanTerapi->deskripsi_tindakan_terapi) }}</textarea>
                                @error('deskripsi_tindakan_terapi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="idkategori" id="idkategori" class="form-control @error('idkategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->idkategori }}" {{ old('idkategori', $tindakanTerapi->idkategori) == $k->idkategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('idkategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idkategori_klinis" class="form-label">Kategori Klinis</label>
                                <select name="idkategori_klinis" id="idkategori_klinis" class="form-control @error('idkategori_klinis') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori Klinis (opsional) --</option>
                                    @foreach($kategoriKlinis as $kk)
                                        <option value="{{ $kk->idkategori_klinis }}" {{ old('idkategori_klinis', $tindakanTerapi->idkategori_klinis) == $kk->idkategori_klinis ? 'selected' : '' }}>{{ $kk->nama_kategori_klinis }}</option>
                                    @endforeach
                                </select>
                                @error('idkategori_klinis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.tindakan-terapi.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><strong>Detail</strong></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>ID:</strong> {{ $tindakanTerapi->idkode_tindakan_terapi }}</p>
                        <p class="mb-1"><strong>Terakhir diubah:</strong> {{ optional($tindakanTerapi->updated_at)->format('d M Y H:i') }}</p>
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
    const input = document.getElementById('kode');
    if (input) input.focus();
});
</script>
@endsection
