@extends('layouts.lte.main')

@section('title','Ubah Rekam Medis')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Ubah Rekam Medis</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dokter.rekam-medis.index') }}">Rekam Medis</a></li>
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
                    <div class="card-header"><h3 class="card-title">Form Ubah Rekam Medis</h3></div>
                    <form action="{{ url('/dokter/rekam-medis/' . $rekamMedis->getKey()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                            <p class="text-muted">Dokter hanya dapat menambahkan atau mengubah <strong>Tindakan Terapi</strong> dan <strong>Detail</strong>. Informasi lain bersifat read-only.</p>

                            <div class="mb-3">
                                <label class="form-label">Tindakan Terapi (opsional)</label>
                                <div id="tindakan-rows">
                                    <div class="tindakan-row mb-2 d-flex gap-2">
                                        <select name="idkode_tindakan_terapi[]" class="form-select flex-grow-1 @error('idkode_tindakan_terapi') is-invalid @enderror">
                                            <option value="">-- Pilih Tindakan --</option>
                                            @if(isset($tindakanTerapis) && $tindakanTerapis->count())
                                                @foreach($tindakanTerapis as $t)
                                                    <option value="{{ $t->idkode_tindakan_terapi }}">{{ $t->kode }} - {{ $t->deskripsi_tindakan_terapi }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-row" style="display:none;">&times;</button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" id="btn-add-row" class="btn btn-sm btn-primary">+ Tambah Baris</button>
                                </div>
                                @error('idkode_tindakan_terapi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="detail" class="form-label">Detail Tindakan (sesuaikan per baris)</label>
                                <div id="detail-rows">
                                    <div class="detail-row mb-2">
                                        <textarea name="detail[]" class="form-control @error('detail') is-invalid @enderror" rows="2">{{ old('detail.0') }}</textarea>
                                    </div>
                                </div>
                                @error('detail')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary ms-2">Simpan Tindakan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-info">
                    <div class="card-header"><h3 class="card-title">Info</h3></div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">ID</dt>
                            <dd class="col-sm-7">{{ $rekamMedis->idrekam_medis ?? $rekamMedis->getKey() }}</dd>

                            <dt class="col-sm-5">Pet</dt>
                            <dd class="col-sm-7" id="info-pet">{{ optional($rekamMedis->pet)->nama ?? '-' }}</dd>

                            <dt class="col-sm-5">Pemilik</dt>
                            <dd class="col-sm-7" id="info-pemilik">{{ optional(optional(optional($rekamMedis->pet)->pemilik)->user)->name ?? (optional(optional($rekamMedis->pet)->pemilik)->nama ?? '-') }}</dd>

                            <dt class="col-sm-5">Ras / Jenis</dt>
                            <dd class="col-sm-7" id="info-rasjenis">{{ optional(optional($rekamMedis->pet)->rasHewan)->nama_ras ?? '-' }} / {{ optional(optional(optional($rekamMedis->pet)->rasHewan)->jenisHewan)->nama_jenis_hewan ?? '-' }}</dd>

                            {{-- Dokter info removed per request --}}

                            <dt class="col-sm-5">Dibuat</dt>
                            <dd class="col-sm-7" id="info-created">{{ $rekamMedis->created_at ? \Carbon\Carbon::parse($rekamMedis->created_at)->format('d M Y H:i') : '-' }}</dd>

                            <!-- Terakhir diubah removed as requested -->
                        </dl>
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
    const first = document.querySelector('[autofocus]'); if(first) first.focus();

    const btnAdd = document.getElementById('btn-add-row');
    const tindakanRows = document.getElementById('tindakan-rows');
    const detailRows = document.getElementById('detail-rows');

    btnAdd.addEventListener('click', function() {
        // clone a new tindakan row
        const template = document.querySelector('.tindakan-row');
        const newRow = template.cloneNode(true);
        // show remove button
        const removeBtn = newRow.querySelector('.btn-remove-row');
        removeBtn.style.display = 'inline-block';
        removeBtn.addEventListener('click', function(){ newRow.remove(); syncDetailRows(); });
        // clear select value
        const sel = newRow.querySelector('select'); sel.value = '';
        tindakanRows.appendChild(newRow);

        // add corresponding detail textarea
        const detailTemplate = document.querySelector('.detail-row');
        const newDetail = detailTemplate.cloneNode(true);
        newDetail.querySelector('textarea').value = '';
        detailRows.appendChild(newDetail);
    });

    // when removing a tindakan via its own remove button we already remove corresponding detail via sync
    function syncDetailRows(){
        // ensure number of detail rows matches tindakan rows
        const tCount = tindakanRows.querySelectorAll('.tindakan-row').length;
        const dNodes = detailRows.querySelectorAll('.detail-row');
        while (dNodes.length > tCount) { detailRows.removeChild(detailRows.lastElementChild); }
        while (dNodes.length < tCount) {
            const clone = dNodes[0].cloneNode(true);
            clone.querySelector('textarea').value = '';
            detailRows.appendChild(clone);
        }
    }
});
</script>
@endsection
