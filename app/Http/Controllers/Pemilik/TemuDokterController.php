<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\TemuDokter;
use App\Models\Pemilik;

class TemuDokterController extends Controller
{
    /**
     * Display a listing of the owner's appointments (temu dokter).
     */
    public function index()
    {
        $user = auth()->user();
        // Determine the pemilik record for the authenticated user.
        $pemilik = null;
        if ($user) {
            // Prefer relation if it's defined on the User model
            if (method_exists($user, 'pemilik') && $user->pemilik) {
                $pemilik = $user->pemilik;
            } else {
                $pemilik = Pemilik::where('iduser', $user->iduser ?? $user->id)->first();
            }
        }

        $temuDokters = collect();
        if ($pemilik) {
            try {
                // Prefer selecting TemuDokter by pet IDs owned by this pemilik.
                $petIds = [];
                if (method_exists($pemilik, 'pets')) {
                    $petIds = $pemilik->pets()->pluck('idpet')->toArray();
                }

                if (!empty($petIds)) {
                    $temuDokters = TemuDokter::with(['pet', 'dokter.user'])
                        ->whereIn('idpet', $petIds)
                        ->orderBy('waktu_daftar', 'desc')
                        ->get();
                } elseif (method_exists($pemilik, 'temuDokters')) {
                    // If pet list is not available, try relation on Pemilik (if table stores idpemilik)
                    $temuDokters = $pemilik->temuDokters()->with(['pet', 'dokter.user'])->orderBy('waktu_daftar', 'desc')->get();
                } else {
                    // Fallback: query TemuDokter whose pet belongs to this pemilik
                    $temuDokters = TemuDokter::with(['pet', 'dokter.user'])
                        ->whereHas('pet', function ($q) use ($pemilik) {
                            $q->where('idpemilik', $pemilik->idpemilik);
                        })
                        ->orderBy('waktu_daftar', 'desc')
                        ->get();
                }
            } catch (QueryException $e) {
                // If ordering by 'waktu_daftar' or the whereHas query fails due to
                // schema differences, fall back to a safe in-memory filter as a last resort.
                $all = TemuDokter::with(['pet', 'dokter.user'])->get();
                $temuDokters = $all->filter(function ($t) use ($pemilik) {
                    // Try to resolve owner id via explicit column or via related pemilik/pet.
                    $ownerId = $t->idpemilik ?? ($t->pemilik->idpemilik ?? null);
                    if ($ownerId) {
                        return $ownerId == $pemilik->idpemilik;
                    }
                    // If temu_dokter doesn't store idpemilik, try pet relation
                    return optional($t->pet)->idpemilik == $pemilik->idpemilik;
                })->values();

                // Sort by best-available timestamp candidate: prefer 'waktu_daftar', then 'tanggal'+'waktu'.
                $temuDokters = $temuDokters->sortByDesc(function ($t) {
                    if (!empty($t->waktu_daftar)) {
                        return \Illuminate\Support\Carbon::parse($t->waktu_daftar);
                    }
                    if (!empty($t->tanggal) && !empty($t->waktu)) {
                        return \Illuminate\Support\Carbon::parse($t->tanggal . ' ' . $t->waktu);
                    }
                    if (!empty($t->tanggal)) {
                        return \Illuminate\Support\Carbon::parse($t->tanggal);
                    }
                    return \Illuminate\Support\Carbon::createFromTimestamp(0);
                })->values();
            }
        }

        return view('pemilik.temu-dokter.index', compact('temuDokters'));
    }
}
