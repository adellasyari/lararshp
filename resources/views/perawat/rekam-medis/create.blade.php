@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Rekam Medis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('perawat.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('perawat.rekam-medis.index') }}">Rekam Medis</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Form Tambah Rekam Medis</h3></div>
                    <form action="{{ route('perawat.rekam-medis.store') }}" method="POST">
                        @csrf
                        {{-- default verification status: 0 (not verified) --}}
                        <input type="hidden" name="status_verifikasi" value="0">
                        <div class="card-body">
                            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($selectedPet) ? now()->format('Y-m-d') : old('tanggal')) }}" required autofocus>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Jika route dipanggil dengan ?idpet=..., gunakan hidden input untuk idpet agar validasi terpenuhi --}}
                            @if(!empty($selectedPet))
                                <input type="hidden" name="idpet" value="{{ $selectedPet }}">
                                <div class="mb-3">
                                    <label class="form-label">Hewan</label>
                                    @php $pet = \App\Models\Pet::with('pemilik.user')->find($selectedPet); @endphp
                                    <div class="form-control-plaintext">{{ $pet->nama ?? 'Terpilih' }} @if($pet) - {{ optional($pet->pemilik->user)->nama ?? '' }}@endif</div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <label for="idpet" class="form-label">Pilih Hewan <span class="text-danger">*</span></label>
                                    <select name="idpet" id="idpet" class="form-select @error('idpet') is-invalid @enderror" required>
                                        <option value="">-- Pilih Hewan --</option>
                                        @foreach($pets as $p)
                                            <option value="{{ $p->idpet }}" @if(old('idpet') == $p->idpet) selected @endif>{{ $p->nama }} - {{ optional($p->pemilik->user)->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('idpet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            {{-- Dokter pemeriksa dan tindakan terapi dihapus sesuai permintaan --}}

                            <div class="mb-3">
                                <label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
                                <textarea name="anamnesa" id="anamnesa" class="form-control @error('anamnesa') is-invalid @enderror" required>{{ old('anamnesa') }}</textarea>
                                @error('anamnesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="temuan_klinis" class="form-label">Temuan Klinis</label>
                                <textarea name="temuan_klinis" id="temuan_klinis" class="form-control @error('temuan_klinis') is-invalid @enderror">{{ old('temuan_klinis') }}</textarea>
                                @error('temuan_klinis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="diagnosa" class="form-label">Diagnosa</label>
                                <textarea name="diagnosa" id="diagnosa" class="form-control @error('diagnosa') is-invalid @enderror">{{ old('diagnosa') }}</textarea>
                                @error('diagnosa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Pet and Dokter Pemeriksa fields removed per request --}}
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('perawat.rekam-medis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button id="btn-submit" type="submit" class="btn btn-success ms-2">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const first = document.querySelector('[autofocus]'); if(first) first.focus();

    // Prevent double-submit by disabling the submit button on first click
    const form = document.querySelector('form[action="{{ route('perawat.rekam-medis.store') }}"]');
    const btn = document.getElementById('btn-submit');
    if (form && btn) {
        form.addEventListener('submit', function(e){
            // If the button is already disabled, prevent submission
            if (btn.disabled) {
                e.preventDefault();
                return false;
            }
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
        });
    }
});
</script>
@endsection
