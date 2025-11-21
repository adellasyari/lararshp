<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\TindakanTerapi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // find the role_user id for this authenticated doctor
        $roleUser = \App\Models\RoleUser::where('iduser', $user->iduser ?? $user->id)
            ->whereHas('role', function($q){ $q->where('nama_role', 'Dokter'); })
            ->where('status', 1)
            ->first();

        $roleUserId = $roleUser->idrole_user ?? null;

        // Rekam medis assigned to this dokter (by role_user id)
        $rekamMediss = RekamMedis::with(['pet', 'roleUser'])
            ->when($roleUserId, function($q) use ($roleUserId) {
                $q->where('dokter_pemeriksa', $roleUserId);
            })
            ->orderBy('created_at','desc')
            ->get();

        // TindakanTerapi listing (same as perawat summary)
        $tindakans = TindakanTerapi::with('kategori', 'kategoriKlinis')->get();

        return view('dokter.dashboard', compact('rekamMediss', 'tindakans'));
    }
}
