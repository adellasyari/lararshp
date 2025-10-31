<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekamMediss = RekamMedis::with(['pet', 'roleUser'])->get();
        return view('dokter.rekam-medis.index', compact('rekamMediss'));
    }
}
