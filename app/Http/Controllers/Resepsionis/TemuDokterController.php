<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemuDokter;

class TemuDokterController extends Controller
{
    public function index()
    {
        $temuDokters = TemuDokter::with('pet', 'pemilik')->get();

        return view('resepsionis.temu-dokter.index', compact('temuDokters'));
    }
}
