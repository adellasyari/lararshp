<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSHP - Rumah Sakit Hewan Pendidikan</title>
    
    {{-- Menautkan file CSS global dan spesifik situs --}}
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>

    {{-- =============================================== --}}
    {{--                BAGIAN NAVBAR                  --}}
    {{-- =============================================== --}}
    <nav class="site-nav">
        <ul class="site-nav-list">
            <li class="site-nav-item">
                <a href="{{ route('site.home') }}" class="site-nav-link">Home</a>
            </li>
            <li class="site-nav-item">
                <a href="{{ route('site.struktur_organisasi') }}" class="site-nav-link">Struktur Organisasi</a>
            </li>
            <li class="site-nav-item">
                <a href="{{ route('site.layanan_umum') }}" class="site-nav-link">Layanan Umum</a>
            </li>
            <li class="site-nav-item">
                <a href="{{ route('site.visi_misi') }}" class="site-nav-link">Visi Misi & Tujuan</a>
            </li>
            <li class="site-nav-item">
                <a href="{{ route('site.berita') }}" class="site-nav-link">Berita Terbaru</a>
            </li>
            <li class="site-nav-item">
                <a href="{{ route('auth.login') }}" class="site-nav-link">Login</a>
            </li>
        </ul>
    </nav>

    <div class="logo-container">
        <img src="{{ asset('asset/LogoRSHP.webp')}}" alt="Gambar RSHP" class="logo-image">
    </div>

    {{-- =============================================== --}}
    {{--         SLOT UNTUK KONTEN HALAMAN             --}}
    {{-- =============================================== --}}
    <main>
        @yield('content')
    </main>

    {{-- =============================================== --}}
    {{--                BAGIAN FOOTER                  --}}
    {{-- =============================================== --}}
    <footer class="site-footer">
        <div class="footer-heading">
            Terima Kasih Telah Mengunjungi Rumah Sakit Hewan Pendidikan Universitas Airlangga
        </div>
        <div class="footer-subheading">
            Bersama RSHP UNAIR, wujudkan kesehatan hewan yang lebih baik untuk masa depan yang cerah.
        </div>
        <div class="footer-copyright">
            &copy; {{ date('Y') }} Rumah Sakit Hewan Pendidikan Universitas Airlangga. All rights reserved.
        </div>
    </footer>

    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

</body>
</html>