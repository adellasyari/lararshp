<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TindakanTerapi;

class TindakanTerapiController extends Controller
{
    public function index()
    {
        $tindakanTerapis = TindakanTerapi::with('kategori', 'kategoriKlinis')->get();
        return view('dokter.tindakan-terapi.index', compact('tindakanTerapis'));
    }
}
