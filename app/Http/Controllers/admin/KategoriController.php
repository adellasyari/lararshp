<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori; // 1. Panggil Model

class KategoriController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data menggunakan Eloquent
        // Metode ini sederhana dan mengambil semua kolom.
        $kategori = Kategori::all();

        // 3. Kirim data ke view
        return view('admin.kategori.index', compact('kategori'));
    }
}
