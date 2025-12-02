<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\Pemilik;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rekamMediss = collect();

        if ($user) {
            // Prefer the pemilik relation on the User model
            if (method_exists($user, 'pemilik') && $user->pemilik) {
                $pemilik = $user->pemilik;
                $petIds = $pemilik->pets()->pluck('idpet');

                if ($petIds->isNotEmpty()) {
                    $rekamMediss = RekamMedis::with(['pet.pemilik.user', 'detail_rekam_medis.tindakan', 'roleUser.user'])
                        ->whereIn('idpet', $petIds)
                        ->get();
                }
            } else {
                // Fallback: try to find Pemilik by iduser column
                $userKey = $user->iduser ?? $user->getKey();
                $pemilik = Pemilik::where('iduser', $userKey)->first();
                if ($pemilik) {
                    $petIds = $pemilik->pets()->pluck('idpet');
                    if ($petIds->isNotEmpty()) {
                        $rekamMediss = RekamMedis::with(['pet.pemilik.user', 'detail_rekam_medis.tindakan', 'roleUser.user'])
                            ->whereIn('idpet', $petIds)
                            ->get();
                    }
                }
            }
        }

        return view('pemilik.rekam-medis.index', compact('rekamMediss'));
    }
}
