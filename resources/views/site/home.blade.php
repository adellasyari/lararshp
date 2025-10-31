@extends('layouts.app')
@section('content')

<!-- Logo RSHP -->
<div style="display: flex; justify-content: center; align-items: center; margin: 10px 0;">
    <img src="/asset/LogoRSHP.webp" alt="Gambar RSHP" style="max-width: 50%; height: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
</div>

<!-- Profi Rshp dan Vidio -->
<div style="display: flex; justify-content: center; align-items: flex-start; gap: 36px; margin: 40px 0;">
    <!-- tabel kiri -->
    <div style="background-color: #0B2D82; padding: 48px 64px; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.10); width: 480px; max-width: 480px; display: flex; flex-direction: column; align-items: flex-start;">
        <!-- tombol kuning pertama -->
        <a href="#" style="background-color: #FFD600; color: #0B2D82; font-weight: bold; padding: 10px 24px; border-radius: 8px; text-decoration: none; margin-bottom: 18px; font-size: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">Pendaftaran Online</a>
        <!-- teks deskripsi -->
        <div style="color: #fff; font-size: 16px; margin-bottom: 22px;">
            Rumah Sakit Hewan Pendidikan Universitas Airlangga berinovasi untuk selalu meningkatkan kualitas pelayanan, maka dari itu Rumah Sakit Hewan Pendidikan Universitas Airlangga mempunyai fitur pendaftaran online yang mempermudah untuk mendaftarkan hewan kesayangan anda.
        </div>
        <!-- tombol kuning kedua -->
        <a href="#" style="background-color: #FFD700; color: #0B2D82; font-weight: bold; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-size: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">Informasi Jadwal Dokter Jaga</a>
    </div>
    <!-- tabel kanan -->
    <div style="background: #fff; padding: 0; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.10); width: 480px; max-width: 480px; display: flex; flex-direction: column; align-items: center;">
        <iframe width="440" height="260" src="https://www.youtube.com/embed/rCfvZPECZvE" title="Profil RSHP Universitas Airlangga" frameborder="0" allowfullscreen style="border-radius: 12px; margin: 24px 0;"></iframe>
    </div>
</div>

@endsection
