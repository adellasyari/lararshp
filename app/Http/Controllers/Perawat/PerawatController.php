<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perawat;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class PerawatController extends Controller
{
    /** Show form to complete perawat profile */
    public function create()
    {
        return view('perawat.create');
    }

    /**
     * Show perawat profile
     */
    public function profile()
    {
        $user = Auth::user();
        $perawat = Perawat::where('id_user', $user->id)->first();
        // Prefer canonical view 'perawat.profile' if present, fall back to existing folder
        if (View::exists('perawat.profile')) {
            return view('perawat.profile', compact('user','perawat'));
        }

        if (View::exists('perawat.profil-perawat.profile')) {
            return view('perawat.profil-perawat.profile', compact('user','perawat'));
        }

        abort(500, 'Perawat profile view not found.');
    }

    /** Store perawat profile */
    public function store(Request $request)
    {
        $data = $request->validate([
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:30'],
            'pendidikan' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L','P'])],
        ]);

        $user = Auth::user();
        $idUser = $user->iduser ?? $user->id;

        $data['id_user'] = $idUser;

        // Use updateOrCreate so calling store will also update if profile exists
        Perawat::updateOrCreate(
            ['id_user' => $idUser],
            [
                'alamat' => $data['alamat'],
                'no_hp' => $data['no_hp'],
                'pendidikan' => $data['pendidikan'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'id_user' => $idUser,
            ]
        );

        return redirect()->route('perawat.profile')->with('success', 'Data perawat berhasil disimpan.');
    }

    /** Show edit form for Perawat profile (or empty form if not exists) */
    public function edit(Request $request, $id = null)
    {
        $user = Auth::user();
        $perawat = Perawat::where('id_user', $user->iduser ?? $user->id)->first();

        return view('perawat.profil-perawat.edit', compact('user', 'perawat'));
    }

    /** Update (or create) perawat profile */
    public function update(Request $request)
    {
        $data = $request->validate([
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:30'],
            'pendidikan' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L','P'])],
        ]);

        $user = Auth::user();
        $idUser = $user->iduser ?? $user->id;

        Perawat::updateOrCreate(
            ['id_user' => $idUser],
            [
                'alamat' => $data['alamat'],
                'no_hp' => $data['no_hp'],
                'pendidikan' => $data['pendidikan'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'id_user' => $idUser,
            ]
        );

        return redirect()->route('perawat.profile')->with('success', 'Profil perawat berhasil diperbarui.');
    }
}
