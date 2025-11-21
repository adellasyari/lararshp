<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;

class PemilikController extends Controller
{
    //
    /**
     * Show the authenticated pemilik's profile.
     */
    public function profile()
    {
        $user = auth()->user();
        $pemilik = Pemilik::where('iduser', $user->iduser ?? $user->id)->first();

        return view('pemilik.profil-pemilik.profile', compact('pemilik', 'user'));
    }
}
