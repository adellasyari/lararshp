@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Pemilik</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('resepsionis.pemilik.index') }}">Pemilik</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h3 class="card-title">Ubah Data Pemilik</h3>
                    </div>
                    <form action="{{ route('resepsionis.pemilik.update', $pemilik->getKey()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label for="iduser" class="form-label">User <span class="text-danger">*</span></label>
                                <select name="iduser" id="iduser" class="form-select @error('iduser') is-invalid @enderror" required>
                                    <option value="">-- Pilih User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('iduser', $pemilik->iduser) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('iduser') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_wa" class="form-label">No WA <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_wa') is-invalid @enderror" id="no_wa" name="no_wa" value="{{ old('no_wa', $pemilik->no_wa) }}" placeholder="0812xxxx" required autofocus>
                                @error('no_wa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $pemilik->alamat) }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Info</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">ID</dt>
                            <dd class="col-sm-7">{{ $pemilik->getKey() }}</dd>

                            <dt class="col-sm-5">User</dt>
                            <dd class="col-sm-7">{{ $pemilik->user->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-5">Dibuat</dt>
                            <dd class="col-sm-7">{{ optional($pemilik->created_at)->format('d M Y H:i') ?? '-' }}</dd>

                            <dt class="col-sm-5">Terakhir diubah</dt>
                            <dd class="col-sm-7">{{ optional($pemilik->updated_at)->format('d M Y H:i') ?? '-' }}</dd>
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
});
</script>
@endsection
