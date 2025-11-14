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
use App\Models\TemuDokter;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('user_role');
        // Summary stats for dashboard
        $stats = [
            'users' => User::count(),
            'pets' => Pet::count(),
            'appointments' => TemuDokter::count(),
            'records' => RekamMedis::count(),
        ];

        // Recent users (latest 6) - prefer `created_at` when available,
        // otherwise fall back to ordering by the model primary key.
        $userModel = new User();
        $userTable = $userModel->getTable();
        if (Schema::hasColumn($userTable, 'created_at')) {
            $recentUsers = User::orderBy('created_at', 'desc')->limit(6)->get();
        } else {
            $recentUsers = User::orderBy($userModel->getKeyName(), 'desc')->limit(6)->get();
        }

        return view('admin.dashboard', [
            'role' => $role,
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            // keep previous collections for modules that may need them
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
