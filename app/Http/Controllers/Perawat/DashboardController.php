<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\TindakanTerapi;
use App\Models\Pet;

class DashboardController extends Controller
{
    public function index()
    {
        $rekamMediss = RekamMedis::with('pet', 'roleUser')->get();
        $tindakans = TindakanTerapi::with('kategori', 'kategoriKlinis')->get();

        return view('perawat.dashboard', compact('rekamMediss', 'tindakans'));
    }

    /**
     * Show patients list (queue) for perawat to start examinations.
     */
    public function patients()
    {
        // Use the relationship names defined on the Pet model.
        // Pet defines `rasHewan()` and RasHewan defines `jenisHewan()` so
        // eager-load nested relation `rasHewan.jenisHewan`.
        $pets = Pet::with('pemilik', 'rasHewan.jenisHewan')->orderBy('nama')->get();
        // Reuse the existing Perawat Rekam Medis index view to show patients queue.
        return view('perawat.rekam-medis.index', compact('pets'));
    }
}
