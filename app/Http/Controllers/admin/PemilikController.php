<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// PASTIKAN INI MEMANGGIL MODEL YANG BENAR
use App\Models\Pemilik; 

class PemilikController extends Controller
{
    public function index()
    {
        // PASTIKAN INI MENGAMBIL DATA PEMILIK (BUKAN JENISHEWAN::ALL())
        $pemilik = Pemilik::with('user')->get();

        // PASTIKAN INI MENGIRIM VARIABEL YANG BENAR
        return view('admin.pemilik.index', compact('pemilik'));
    }
}
