<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Pemilik;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        $pets = collect();

        if ($user) {
            // If user has a pemilik relation, prefer that
            if (method_exists($user, 'pemilik') && $user->pemilik) {
                $pemilik = $user->pemilik;
                $pets = $pemilik->pets()->with('pemilik', 'rasHewan')->get();
            } else {
                // Fallback: try to find Pemilik by iduser column (some DBs use iduser)
                $userKey = $user->iduser ?? $user->getKey();
                $pemilik = Pemilik::where('iduser', $userKey)->first();
                if ($pemilik) {
                    $pets = $pemilik->pets()->with('pemilik', 'rasHewan')->get();
                }
            }
        }

        return view('pemilik.pet.index', compact('pets'));
    }
}
