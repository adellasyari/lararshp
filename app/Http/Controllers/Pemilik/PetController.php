<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    
    public function index()
    {
        $userId = session('user_role');
        
        $pets = Pet::with('pemilik', 'rasHewan')->where('idpemilik', $userId)->get();

        return view('pemilik.pet.index', compact('pets'));
    }
}
