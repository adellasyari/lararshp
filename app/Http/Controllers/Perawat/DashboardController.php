<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\TindakanTerapi;

class DashboardController extends Controller
{
    public function index()
    {
        $rekamMediss = RekamMedis::with('pet', 'roleUser')->get();
        $tindakans = TindakanTerapi::with('kategori', 'kategoriKlinis')->get();

        return view('perawat.dashboard', compact('rekamMediss', 'tindakans'));
    }
}
