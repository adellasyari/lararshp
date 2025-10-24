<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\admin\JenisHewanController;
use App\Http\Controllers\Admin\PemilikController; 
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\TindakanTerapiController; // Pastikan Controller Anda sudah diimpor
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Di sini tempat Anda mendaftarkan semua route web untuk aplikasi Anda.
| Route ini di-load oleh RouteServiceProvider dalam group yang berisi 
| middleware 'web'.
*/

Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi'])->name('site.cek-koneksi');

// URL UTAMA (Root URL /) - Harus mengarah ke Halaman Home
Route::get('/', [SiteController::class, 'home'])->name('site.home');

// Halaman Home (opsional, jika Anda ingin /home)
// Route::get('/home', [SiteController::class, 'home'])->name('site.home'); 

// Struktur Organisasi
Route::get('/struktur-organisasi', [SiteController::class, 'strukturOrganisasi'])->name('site.struktur_organisasi');

// Layanan Umum
Route::get('/layanan-umum', [SiteController::class, 'layanan'])->name('site.layanan_umum');

// Visi Misi & Tujuan (Menggabungkan "Visi Misi" dan asumsi "Tujuan" dari Navbar)
Route::get('/visi-misi', [SiteController::class, 'visiMisi'])->name('site.visi_misi');

// Berita Terbaru (Diasumsikan ada method 'berita' di SiteController)
Route::get('/berita', [SiteController::class, 'berita'])->name('site.berita');

// Login
Route::get('/login', [SiteController::class, 'login'])->name('auth.login');

// Jenis Hewan
Route::get('/jenis-hewan', [JenisHewanController::class, 'index'])->name('admin.jenis_hewan.index');

// Pemilik
Route::get('/pemilik', [PemilikController::class, 'index'])->name('admin.pemilik.index');

// Ras Hewan
Route::get('/ras-hewan', [RasHewanController::class, 'index'])->name('admin.ras-hewan.index');

// Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');

// Kategori Klinis
Route::get('/kategori-klinis', [KategoriKlinisController::class, 'index'])->name('admin.kategori_klinis.index');

// Tindakan Terapi
Route::get('/tindakan-terapi', [TindakanTerapiController::class, 'index'])->name('admin.tindakan_terapi.index');

// Hewan
Route::get('/pet', [PetController::class, 'index'])->name('admin.pet.index');

// Role
Route::get('/role', [RoleController::class, 'index'])->name('admin.role.index');

// User
Route::get('/user', [UserController::class, 'index'])->name('admin.user.index');
