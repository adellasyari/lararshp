@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Pendaftaran Temu Dokter</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.temu-dokter.index') }}">Temu Dokter</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Pendaftaran Temu Dokter</h3>
                    </div>
                    <form action="{{ route('resepsionis.temu-dokter.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label for="idpet" class="form-label">Pilih Hewan <span class="text-danger">*</span></label>
                                <input type="text" id="petSearch" class="form-control mb-2" placeholder="Cari hewan (nama atau pemilik)..." autofocus>
                                <select name="idpet" id="idpet" class="form-select @error('idpet') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hewan --</option>
                                    @foreach($pets as $pet)
                                        <option value="{{ $pet->idpet }}" {{ old('idpet') == $pet->idpet ? 'selected' : '' }}>{{ $pet->nama }} - {{ $pet->pemilik->user->name ?? 'Pemilik N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('idpet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="idrole_user" class="form-label">Pilih Dokter <span class="text-danger">*</span></label>
                                <select name="idrole_user" id="idrole_user" class="form-select select2 @error('idrole_user') is-invalid @enderror" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokters as $dokter)
                                        <option value="{{ $dokter->idrole_user }}" {{ old('idrole_user') == $dokter->idrole_user ? 'selected' : '' }}>{{ $dokter->user->name ?? $dokter->user->name ?? 'Dokter' }}</option>
                                    @endforeach
                                </select>
                                @error('idrole_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="alert alert-info">Waktu pendaftaran akan otomatis diisi saat Anda menekan <strong>Daftarkan</strong> (waktu server saat ini).</div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('resepsionis.temu-dokter.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                            <button type="submit" class="btn btn-success ms-2"><i class="bi bi-save"></i> Daftarkan</button>
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

    // Prepare pets list for client-side filtering
    const pets = @json($pets->map(function($p){ return ['id'=>$p->idpet, 'label' => ($p->nama . ' - ' . ($p->pemilik->user->name ?? 'Pemilik N/A'))]; }));
    const select = document.getElementById('idpet');
    const input = document.getElementById('petSearch');

    if (select && input) {
        const render = function(filter) {
            const current = select.value;
            select.innerHTML = '';
            const ph = document.createElement('option'); ph.value = ''; ph.textContent = '-- Pilih Hewan --'; select.appendChild(ph);
            const q = (filter||'').trim().toLowerCase();
            const list = pets.filter(o => { if(!q) return true; return o.label.toLowerCase().includes(q); });
            list.forEach(o => {
                const opt = document.createElement('option'); opt.value = o.id; opt.textContent = o.label; if(String(o.id)===String(current)) opt.selected = true; select.appendChild(opt);
            });
        };
        render('');
        input.addEventListener('input', function(e){ render(e.target.value); });
    }
});
</script>
@endsection
