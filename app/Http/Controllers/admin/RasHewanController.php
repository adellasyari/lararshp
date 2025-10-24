<?php

// 1. PASTIKAN 'A' di 'Admin' adalah huruf besar
namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RasHewan; 

class RasHewanController extends Controller
{
    public function index()
    {
        // 2. Baris ini sudah benar
        $list_ras = RasHewan::with('jenisHewan')->get();

        // 3. Baris ini juga sudah benar
        return view('admin.ras-hewan.index', compact('list_ras'));
    }
}
