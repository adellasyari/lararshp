@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Dokter</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dokter.index') }}">Dokter</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Dokter Baru</h3>
                    </div>
                    <form action="{{ route('admin.dokter.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Akun Pengguna</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="create_user_check" name="create_user" {{ old('create_user') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create_user_check">Buat user baru untuk dokter ini</label>
                                </div>

                                <div id="existing_user_block">
                                    <div class="mb-2">
                                        <input type="text" id="id_user_search" class="form-control" placeholder="Cari user...">
                                    </div>
                                    <select name="id_user" id="id_user" class="form-select @error('id_user') is-invalid @enderror" {{ old('create_user') ? 'disabled' : 'required' }}>
                                        <option value="">-- Pilih User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->iduser }}" {{ old('id_user') == $user->iduser ? 'selected' : '' }}>
                                                {{ $user->nama }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}">
                                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bidang_dokter" class="form-label">Bidang Dokter</label>
                                <input type="text" name="bidang_dokter" id="bidang_dokter" class="form-control @error('bidang_dokter') is-invalid @enderror" value="{{ old('bidang_dokter') }}">
                                @error('bidang_dokter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Email dan password dihilangkan, karena user sudah ada -->
                        </div>
                        <script>
                            // fitur search pada select user dan toggle create-new-user
                            document.addEventListener('DOMContentLoaded', function () {
                                try {
                                    var search = document.getElementById('id_user_search');
                                    var select = document.getElementById('id_user');
                                    var createCheck = document.getElementById('create_user_check');
                                    var newBlock = document.getElementById('new_user_block');
                                    var existingBlock = document.getElementById('existing_user_block');

                                    function refreshCreateState() {
                                        var creating = createCheck && createCheck.checked;
                                        if (creating) {
                                            newBlock.style.display = '';
                                            // disable existing user select
                                            if (select) { select.disabled = true; select.removeAttribute('required'); }
                                        } else {
                                            newBlock.style.display = 'none';
                                            if (select) { select.disabled = false; select.setAttribute('required','required'); }
                                        }
                                    }

                                    if (createCheck) {
                                        createCheck.addEventListener('change', refreshCreateState);
                                        // initial state
                                        refreshCreateState();
                                    }

                                    if (search && select) {
                                        search.addEventListener('input', function () {
                                            var filter = this.value.toLowerCase();
                                            Array.from(select.options).forEach(function (opt) {
                                                if (!opt.value) { opt.hidden = false; return; }
                                                var matches = opt.text.toLowerCase().includes(filter);
                                                opt.hidden = !matches;
                                            });
                                            if (select.selectedIndex >= 0 && select.options[select.selectedIndex].hidden) {
                                                select.value = '';
                                            }
                                        });
                                    }
                                } catch (e) {
                                    console.error(e);
                                }
                            });
                        </script>
                        <div class="card-footer">
                            <a href="{{ route('admin.dokter.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                            <button type="submit" class="btn btn-success ms-2"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
