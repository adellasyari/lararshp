@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Pemilik</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pemilik.index') }}">Pemilik</a></li>
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
                        <h3 class="card-title">Form Pemilik Baru</h3>
                    </div>
                    <form action="{{ route('admin.pemilik.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">User</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="create_user_check" name="create_user" {{ old('create_user') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create_user_check">Buat user baru untuk pemilik ini</label>
                                </div>

                                <div id="existing_user_block">
                                    <input type="text" id="userSearch" class="form-control mb-2" placeholder="Cari user (nama atau email)...">
                                    <select name="iduser" id="iduser" class="form-select @error('iduser') is-invalid @enderror" {{ old('create_user') ? 'disabled' : 'required' }}>
                                        <option value="">-- Pilih User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('iduser') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('iduser') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div id="new_user_block" style="display: none;">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Pengguna <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="no_wa" class="form-label">No WA <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_wa') is-invalid @enderror" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" placeholder="0812xxxx" required autofocus>
                                @error('no_wa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.pemilik.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                            <button type="submit" class="btn btn-success ms-2"><i class="bi bi-save"></i> Simpan</button>
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
    
    // Membuat daftar user untuk pencarian (gunakan kolom `id` dan `name` dari tabel `users`)
    const users = @json($users->map(function($u){ return ['id'=>$u->id, 'label'=>$u->name . ' (' . ($u->email ?? '') . ')']; }));
    const select = document.getElementById('iduser');
    const input = document.getElementById('userSearch');

    if (!select || !input) return;

    const renderOptions = function(filter) {
        const current = select.value;
        // clear existing (keep the placeholder first option)
        select.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = '-- Pilih User --';
        select.appendChild(placeholder);

        const q = (filter||'').trim().toLowerCase();
        const list = users.filter(u => {
            if (!q) return true;
            return u.label.toLowerCase().includes(q);
        });

        list.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u.id;
            opt.textContent = u.label;
            if (String(u.id) === String(current)) opt.selected = true;
            select.appendChild(opt);
        });
    };

    // initialize
    renderOptions('');

    input.addEventListener('input', function(e) {
        renderOptions(e.target.value);
    });
    // toggle create-user block
    const createCheck = document.getElementById('create_user_check');
    const newBlock = document.getElementById('new_user_block');
    const existingBlock = document.getElementById('existing_user_block');
    function refreshCreateState() {
        const creating = createCheck && createCheck.checked;
        if (creating) {
            newBlock.style.display = '';
            if (select) { select.disabled = true; select.removeAttribute('required'); }
        } else {
            newBlock.style.display = 'none';
            if (select) { select.disabled = false; select.setAttribute('required','required'); }
        }
    }
    if (createCheck) { createCheck.addEventListener('change', refreshCreateState); refreshCreateState(); }
});
</script>
@endsection
