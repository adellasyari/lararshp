<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 1. Panggil model 'Pet' yang baru
use App\Models\Pet;

// 2. Ubah nama class
class PetController extends Controller
{
    public function index()
    {
        // 3. Ambil data dari model 'Pet'
        // 4. Perbaiki Eager Loading:
        //    Kita ambil 'rasHewan' dan 'jenisHewan' yang ada DI DALAM 'rasHewan'
        $pet = Pet::with('pemilik.user', 'rasHewan.jenisHewan')->get();

        // 5. Kirim data ke view 'admin.pet.index' dengan variabel 'pet'
        return view('admin.pet.index', compact('pet'));
    }
}