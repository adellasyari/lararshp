<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\KategoriKlinis;
use App\Models\JenisHewan;
use App\Models\RasHewan;
use App\Models\Role;
use App\Models\User;
use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TindakanTerapi;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('user_role');

        return view('admin.dashboard', [
            'role' => $role,
            'kategoris' => Kategori::all(),
            'kategoriKliniss' => KategoriKlinis::all(),
            'jenisHewans' => JenisHewan::all(),
            'rasHewans' => RasHewan::with('jenisHewan')->get(),
            'roles' => Role::all(),
            'users' => User::with('roles', 'pemilik')->get(),
            'pemiliks' => Pemilik::with('user')->get(),
            'pets' => Pet::with('pemilik', 'rasHewan')->get(),
            'rekamMediss' => RekamMedis::with('pet', 'roleUser')->get(),
            'tindakans' => TindakanTerapi::with('kategori', 'kategoriKlinis')->get()
        ]);
    }
}
