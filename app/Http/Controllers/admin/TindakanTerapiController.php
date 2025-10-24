<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TindakanTerapi; // 1. Panggil Model

class TindakanTerapiController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data menggunakan Eloquent
        // Metode ini sederhana dan mengambil semua kolom.
        $tindakanTerapi = TindakanTerapi::all();

        // 3. Kirim data ke view
        return view('admin.tindakan-terapi.index', compact('tindakanTerapi'));
    }
}
