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
    Route::get('/admin/jenis-hewan/create', [App\Http\Controllers\Admin\JenisHewanController::class, 'create'])->name('admin.jenis-hewan.create');
    Route::post('/admin/jenis-hewan/store', [App\Http\Controllers\Admin\JenisHewanController::class, 'store'])->name('admin.jenis-hewan.store');
    // Edit / Update / Destroy
    Route::get('/admin/jenis-hewan/{id}/edit', [App\Http\Controllers\Admin\JenisHewanController::class, 'edit'])->name('admin.jenis-hewan.edit');
    Route::put('/admin/jenis-hewan/{id}/update', [App\Http\Controllers\Admin\JenisHewanController::class, 'update'])->name('admin.jenis-hewan.update');
    Route::delete('/admin/jenis-hewan/{id}/destroy', [App\Http\Controllers\Admin\JenisHewanController::class, 'destroy'])->name('admin.jenis-hewan.destroy');

        // Pemilik
    Route::get('/admin/pemilik', [PemilikController::class, 'index'])->name('admin.pemilik.index');
    Route::get('/admin/pemilik/create', [App\Http\Controllers\Admin\PemilikController::class, 'create'])->name('admin.pemilik.create');
    Route::post('/admin/pemilik/store', [App\Http\Controllers\Admin\PemilikController::class, 'store'])->name('admin.pemilik.store');
    // Edit / Update / Destroy
    Route::get('/admin/pemilik/{id}/edit', [App\Http\Controllers\Admin\PemilikController::class, 'edit'])->name('admin.pemilik.edit');
    Route::put('/admin/pemilik/{id}/update', [App\Http\Controllers\Admin\PemilikController::class, 'update'])->name('admin.pemilik.update');
    Route::delete('/admin/pemilik/{id}/destroy', [App\Http\Controllers\Admin\PemilikController::class, 'destroy'])->name('admin.pemilik.destroy');

        // Ras Hewan
    Route::get('/admin/ras-hewan', [RasHewanController::class, 'index'])->name('admin.ras-hewan.index');
    Route::get('/admin/ras-hewan/create', [App\Http\Controllers\Admin\RasHewanController::class, 'create'])->name('admin.ras-hewan.create');
    Route::post('/admin/ras-hewan/store', [App\Http\Controllers\Admin\RasHewanController::class, 'store'])->name('admin.ras-hewan.store');
    // Edit / Update / Destroy
    Route::get('/admin/ras-hewan/{id}/edit', [App\Http\Controllers\Admin\RasHewanController::class, 'edit'])->name('admin.ras-hewan.edit');
    Route::put('/admin/ras-hewan/{id}/update', [App\Http\Controllers\Admin\RasHewanController::class, 'update'])->name('admin.ras-hewan.update');
    Route::delete('/admin/ras-hewan/{id}/destroy', [App\Http\Controllers\Admin\RasHewanController::class, 'destroy'])->name('admin.ras-hewan.destroy');

        // Kategori
        Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
    Route::get('/admin/kategori/create', [App\Http\Controllers\Admin\KategoriController::class, 'create'])->name('admin.kategori.create');
    Route::post('/admin/kategori/store', [App\Http\Controllers\Admin\KategoriController::class, 'store'])->name('admin.kategori.store');
    // Edit / Update / Destroy
    Route::get('/admin/kategori/{id}/edit', [App\Http\Controllers\Admin\KategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/admin/kategori/{id}/update', [App\Http\Controllers\Admin\KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/admin/kategori/{id}/destroy', [App\Http\Controllers\Admin\KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

        // Kategori Klinis
    Route::get('/admin/kategori-klinis', [KategoriKlinisController::class, 'index'])->name('admin.kategori-klinis.index');
    Route::get('/admin/kategori-klinis/create', [App\Http\Controllers\Admin\KategoriKlinisController::class, 'create'])->name('admin.kategori-klinis.create');
    Route::post('/admin/kategori-klinis/store', [App\Http\Controllers\Admin\KategoriKlinisController::class, 'store'])->name('admin.kategori-klinis.store');
    // Edit / Update / Destroy
    Route::get('/admin/kategori-klinis/{id}/edit', [App\Http\Controllers\Admin\KategoriKlinisController::class, 'edit'])->name('admin.kategori-klinis.edit');
    Route::put('/admin/kategori-klinis/{id}/update', [App\Http\Controllers\Admin\KategoriKlinisController::class, 'update'])->name('admin.kategori-klinis.update');
    Route::delete('/admin/kategori-klinis/{id}/destroy', [App\Http\Controllers\Admin\KategoriKlinisController::class, 'destroy'])->name('admin.kategori-klinis.destroy');

    // Tindakan Terapi
    Route::get('/admin/tindakan-terapi', [TindakanTerapiController::class, 'index'])->name('admin.tindakan-terapi.index');
    Route::get('/admin/tindakan-terapi/create', [App\Http\Controllers\Admin\TindakanTerapiController::class, 'create'])->name('admin.tindakan-terapi.create');
    Route::post('/admin/tindakan-terapi/store', [App\Http\Controllers\Admin\TindakanTerapiController::class, 'store'])->name('admin.tindakan-terapi.store');
    // Edit / Update / Destroy
    Route::get('/admin/tindakan-terapi/{tindakanTerapi}/edit', [App\Http\Controllers\Admin\TindakanTerapiController::class, 'edit'])->name('admin.tindakan-terapi.edit');
    Route::put('/admin/tindakan-terapi/{tindakanTerapi}/update', [App\Http\Controllers\Admin\TindakanTerapiController::class, 'update'])->name('admin.tindakan-terapi.update');
    Route::delete('/admin/tindakan-terapi/{tindakanTerapi}/destroy', [App\Http\Controllers\Admin\TindakanTerapiController::class, 'destroy'])->name('admin.tindakan-terapi.destroy');

        // Hewan
    Route::get('/admin/pet', [PetController::class, 'index'])->name('admin.pet.index');
    Route::get('/admin/pet/create', [App\Http\Controllers\Admin\PetController::class, 'create'])->name('admin.pet.create');
    Route::post('/admin/pet/store', [App\Http\Controllers\Admin\PetController::class, 'store'])->name('admin.pet.store');
    // Edit / Update / Destroy
    Route::get('/admin/pet/{id}/edit', [App\Http\Controllers\Admin\PetController::class, 'edit'])->name('admin.pet.edit');
    Route::put('/admin/pet/{id}/update', [App\Http\Controllers\Admin\PetController::class, 'update'])->name('admin.pet.update');
    Route::delete('/admin/pet/{id}/destroy', [App\Http\Controllers\Admin\PetController::class, 'destroy'])->name('admin.pet.destroy');

    // Role
    Route::get('/admin/role', [RoleController::class, 'index'])->name('admin.role.index');
    Route::get('/admin/role/create', [App\Http\Controllers\admin\RoleController::class, 'create'])->name('admin.role.create');
    Route::post('/admin/role/store', [App\Http\Controllers\admin\RoleController::class, 'store'])->name('admin.role.store');
    // Edit / Update / Destroy
    Route::get('/admin/role/{role}/edit', [App\Http\Controllers\admin\RoleController::class, 'edit'])->name('admin.role.edit');
    Route::put('/admin/role/{role}/update', [App\Http\Controllers\admin\RoleController::class, 'update'])->name('admin.role.update');
    Route::delete('/admin/role/{role}/destroy', [App\Http\Controllers\admin\RoleController::class, 'destroy'])->name('admin.role.destroy');

    // User
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/admin/user/create', [App\Http\Controllers\admin\UserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/user/store', [App\Http\Controllers\admin\UserController::class, 'store'])->name('admin.user.store');
    // Edit / Update / Destroy
    Route::get('/admin/user/{user}/edit', [App\Http\Controllers\admin\UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/user/{user}/update', [App\Http\Controllers\admin\UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{user}/destroy', [App\Http\Controllers\admin\UserController::class, 'destroy'])->name('admin.user.destroy');

    // Rekam Medis
    Route::get('/admin/rekam-medis', [RekamMedisController::class, 'index'])->name('admin.rekam-medis.index');
    Route::get('/admin/rekam-medis/create', [App\Http\Controllers\Admin\RekamMedisController::class, 'create'])->name('admin.rekam-medis.create');
    Route::post('/admin/rekam-medis/store', [App\Http\Controllers\Admin\RekamMedisController::class, 'store'])->name('admin.rekam-medis.store');
    // Edit / Update / Destroy
    Route::get('/admin/rekam-medis/{rekamMedis}/edit', [App\Http\Controllers\Admin\RekamMedisController::class, 'edit'])->name('admin.rekam-medis.edit');
    Route::put('/admin/rekam-medis/{rekamMedis}/update', [App\Http\Controllers\Admin\RekamMedisController::class, 'update'])->name('admin.rekam-medis.update');
    Route::delete('/admin/rekam-medis/{rekamMedis}/destroy', [App\Http\Controllers\Admin\RekamMedisController::class, 'destroy'])->name('admin.rekam-medis.destroy');
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
        Route::get('/resepsionis/temu-dokter', [App\Http\Controllers\Resepsionis\TemuDokterController::class, 'index'])->name('resepsionis.temu-dokter.index');
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

