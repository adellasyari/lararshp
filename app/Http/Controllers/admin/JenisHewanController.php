<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisHewan; // 1. Panggil Model

class JenisHewanController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data menggunakan Eloquent
        // Metode ini sederhana dan mengambil semua kolom.
        $jenisHewan = JenisHewan::all();

        // 3. Kirim data ke view
        return view('admin.jenis-hewan.index', compact('jenisHewan'));
    }
}