<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    // Fungsi untuk View: home.blade.php
    public function home()
    {
        return view('site.home'); 
    }

    // Fungsi untuk View: struktur_organisasi.blade.php
    public function strukturOrganisasi()
    {
        return view('site.struktur_organisasi');
    }

    // Fungsi untuk View: layanan.blade.php
    public function layanan()
    {
        return view('site.layanan_umum');
    }

    // Fungsi untuk View: visi_misi.blade.php
    public function visiMisi()
    {
        return view('site.visi_misi');
    }

    
    // Fungsi untuk View: login.blade.php
    public function berita()
    {
        return view('site.berita');
    }


    // Fungsi untuk View: login.blade.php
    public function login()
    {
        return view('auth.login');
    }

    // Fungsi untuk cek koneksi ke database
    public function cekKoneksi()
    {
        try {
            DB::connection()->getPdo();
            return 'Koneksi ke database berhasil!';
        } catch (\Exception $e) {
            return 'Koneksi ke database gagal: ' . $e->getMessage();
        }
    }
    
    // Catatan: Jika ada halaman 'Tujuan & Berita Terbaru' (yang belum Anda buat View-nya),
    // Anda bisa tambahkan method dan View-nya nanti.
}
