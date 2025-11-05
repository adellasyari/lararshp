<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;

class DashboardController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik::with('user')->get();
        $pets = Pet::with('pemilik', 'rasHewan')->get();
        $temuDokters = TemuDokter::all();

        return view('resepsionis.dashboard', compact('pemiliks', 'pets', 'temuDokters'));
    }
}
