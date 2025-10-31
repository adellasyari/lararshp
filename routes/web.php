<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\admin\JenisHewanController;
use App\Http\Controllers\Admin\PemilikController; 
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\TindakanTerapiController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RekamMedisController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini tempat Anda mendaftarkan semua route web untuk aplikasi Anda.
|
*/

// =========================================================================
// RUTE PUBLIK (Bisa diakses tanpa login)
// =========================================================================

Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi'])->name('site.cek-koneksi');

// URL UTAMA (Root URL /)
Route::get('/', [SiteController::class, 'home'])->name('site.home');

// Halaman Publik Lainnya
Route::get('/struktur-organisasi', [SiteController::class, 'strukturOrganisasi'])->name('site.struktur_organisasi');
Route::get('/layanan-umum', [SiteController::class, 'layanan'])->name('site.layanan_umum');
Route::get('/visi-misi', [SiteController::class, 'visiMisi'])->name('site.visi_misi');
Route::get('/berita', [SiteController::class, 'berita'])->name('site.berita');

// Removed incorrect POST /login route that pointed to non-existent HomeController login method


// =========================================================================
// RUTE AUTENTIKASI (Menangani /login, /register, /logout)
// =========================================================================

Auth::routes();

    // =========================================================================
    // RUTE ADMINISTRATOR
    // =========================================================================
    Route::middleware('isAdministrator')->group(function () {
        Route::get('/admin/dashboard', [App\Http\Controllers\admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Jenis Hewan
        Route::get('/admin/jenis-hewan', [JenisHewanController::class, 'index'])->name('admin.jenis-hewan.index');

        // Pemilik
        Route::get('/admin/pemilik', [PemilikController::class, 'index'])->name('admin.pemilik.index');

        // Ras Hewan
        Route::get('/admin/ras-hewan', [RasHewanController::class, 'index'])->name('admin.ras-hewan.index');

        // Kategori
        Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');

        // Kategori Klinis
        Route::get('/admin/kategori-klinis', [KategoriKlinisController::class, 'index'])->name('admin.kategori-klinis.index');

        // Tindakan Terapi
        Route::get('/admin/tindakan-terapi', [TindakanTerapiController::class, 'index'])->name('admin.tindakan-terapi.index');

        // Hewan
        Route::get('/admin/pet', [PetController::class, 'index'])->name('admin.pet.index');

        // Role
        Route::get('/admin/role', [RoleController::class, 'index'])->name('admin.role.index');

        // User
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');

        // Rekam Medis
        Route::get('/admin/rekam-medis', [RekamMedisController::class, 'index'])->name('admin.rekam-medis.index');
    });

    // =========================================================================
    // RUTE DOKTER
    // =========================================================================
    Route::middleware('isDokter')->group(function () {
        Route::get('/dokter/dashboard', [App\Http\Controllers\Dokter\DashboardController::class, 'index'])->name('dokter.dashboard');
        Route::resource('/dokter/rekam-medis', App\Http\Controllers\Dokter\RekamMedisController::class, ['names' => 'dokter.rekam-medis']);
        Route::get('/dokter/tindakan-terapi', [App\Http\Controllers\Dokter\TindakanTerapiController::class, 'index'])->name('dokter.tindakan-terapi.index');
    });

    // =========================================================================
    // RUTE PERAWAT
    // =========================================================================
    Route::middleware('isPerawat')->group(function () {
        Route::get('/perawat/dashboard', [App\Http\Controllers\Perawat\DashboardController::class, 'index'])->name('perawat.dashboard');
        Route::resource('/rekam-medis', App\Http\Controllers\Perawat\RekamMedisController::class, ['names' => 'perawat.rekam-medis']);
        Route::get('/perawat/tindakan-terapi', [App\Http\Controllers\Perawat\TindakanTerapiController::class, 'index'])->name('perawat.tindakan-terapi.index');
    });

    // =========================================================================
    // RUTE RESEPSIONIS
    // =========================================================================
    Route::middleware('isResepsionis')->group(function () {
        Route::get('/resepsionis/dashboard', [App\Http\Controllers\Resepsionis\DashboardController::class, 'index'])->name('resepsionis.dashboard');
        Route::get('/resepsionis/pemilik', [App\Http\Controllers\Resepsionis\PemilikController::class, 'index'])->name('resepsionis.pemilik.index');
        Route::get('/resepsionis/hewan', [App\Http\Controllers\Resepsionis\PetController::class, 'index'])->name('resepsionis.hewan.index');
        Route::get('/resepsionis/rekam-medis', [App\Http\Controllers\Resepsionis\RekamMedisController::class, 'index'])->name('resepsionis.rekam-medis.index');
    });

    // =========================================================================
    // RUTE PEMILIK
    // =========================================================================
    Route::middleware('isPemilik')->group(function () {
        Route::get('/pemilik/dashboard', [App\Http\Controllers\Pemilik\DashboardController::class, 'index'])->name('pemilik.dashboard');
        Route::get('/pemilik/pet', [App\Http\Controllers\Pemilik\PetController::class, 'index'])->name('pemilik.pet.index');
        Route::get('/pemilik/rekam-medis', [App\Http\Controllers\Pemilik\RekamMedisController::class, 'index'])->name('pemilik.rekam-medis.index');
    });

// =========================================================================);

