<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\RekamMedis;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = session('user_role');
        $pets = Pet::with('pemilik', 'rasHewan')->where('idpemilik', $userId)->get();
        $rekamMediss = RekamMedis::with('pet', 'roleUser')->whereHas('pet', function($q) use ($userId) {
            $q->where('idpemilik', $userId);
        })->get();

        return view('pemilik.dashboard', compact('pets', 'rekamMediss'));
    }
}
