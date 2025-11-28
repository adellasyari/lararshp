@extends('layouts.lte.main')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Perawat</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.perawat.index') }}">Perawat</a></li>
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
                        <h3 class="card-title">Form Perawat Baru</h3>
                    </div>
                    <form action="{{ route('admin.perawat.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="mb-3">
                                <label for="id_user" class="form-label">Pilih User <span class="text-danger">*</span></label>
                                <div class="mb-2">
                                    <input type="text" id="id_user_search" class="form-control" placeholder="Cari user...">
                                </div>
                                <select name="id_user" id="id_user" class="form-select @error('id_user') is-invalid @enderror" required>
                                    <option value="">-- Pilih User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->iduser }}" {{ old('id_user') == $user->iduser ? 'selected' : '' }}>
                                            {{ $user->nama }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <hr>
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
                                <label for="pendidikan" class="form-label">Pendidikan</label>
                                <input type="text" name="pendidikan" id="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror" value="{{ old('pendidikan') }}">
                                @error('pendidikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        </div>
                        <script> //untuk fitur search pada select user
                            document.addEventListener('DOMContentLoaded', function () {
                                try {
                                    var search = document.getElementById('id_user_search');
                                    var select = document.getElementById('id_user');
                                    if (!search || !select) return;
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
                                } catch (e) {
                                    console.error(e);
                                }
                            });
                        </script>
                        <div class="card-footer">
                            <a href="{{ route('admin.perawat.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                            <button type="submit" class="btn btn-success ms-2"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
