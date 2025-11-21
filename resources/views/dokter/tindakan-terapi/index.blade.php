@extends('layouts.lte.main')

@section('title','Tindakan Terapi (Dinonaktifkan untuk Dokter)')

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="alert alert-info">Manajemen Tindakan Terapi dinonaktifkan untuk role Dokter. Master data Tindakan Terapi dikelola oleh Administrator. Dokter hanya bisa memilih tindakan saat membuat pemeriksaan (Rekam Medis).</div>
        <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary">Kembali ke Pemeriksaan Pasien</a>
    </div>
</div>

@endsection

