<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index()
    {
        $userId = session('user_role');
        $rekamMediss = RekamMedis::with('pet', 'roleUser')->whereHas('pet', function($q) use ($userId) {
            $q->where('idpemilik', $userId);
        })->get();

        return view('pemilik.rekam-medis.index', compact('rekamMediss'));
    }
}
