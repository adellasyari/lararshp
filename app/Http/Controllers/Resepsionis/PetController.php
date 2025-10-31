<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;

class PetController extends Controller
{
    public function index()
    {
        $pet = Pet::with('pemilik.user', 'rasHewan.jenisHewan')->get();
        return view('resepsionis.hewan.index', compact('pet'));
    }
}
