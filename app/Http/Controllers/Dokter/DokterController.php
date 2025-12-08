<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokter;

use Illuminate\Validation\Rule;

class DokterController extends Controller
{
    /** Show form to complete dokter profile */
    public function create()
    {
        $user = auth()->user();
        // find dokter record linked to this user (id_user column)
        $dokter = Dokter::where('id_user', $user->iduser ?? $user->id)->first();
        return view('dokter.profil-dokter.profile', compact('user','dokter'));
    }

    /** Store dokter profile */
    public function store(Request $request)
    {
        $data = $request->validate([
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:30'],
            'bidang_dokter' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L','P'])],
        ]);

        $user = auth()->user();
        $idUser = $user->iduser ?? $user->id;

        $data['id_user'] = $idUser;

        // Use updateOrCreate so the same user can update their profile via the same endpoint
        Dokter::updateOrCreate(
            ['id_user' => $idUser],
            [
                'alamat' => $data['alamat'],
                'no_hp' => $data['no_hp'],
                'bidang_dokter' => $data['bidang_dokter'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'id_user' => $idUser,
            ]
        );

        return redirect()->route('dokter.create')->with('success', 'Data dokter berhasil disimpan.');
    }

    /** Show edit form for Dokter profile (or empty form if not exists) */
    public function edit(Request $request, $id = null)
    {
        $user = auth()->user();
        $dokter = Dokter::where('id_user', $user->iduser ?? $user->id)->first();

        // If dokter data exists, show edit with data; otherwise show form (empty) so user can complete profile
        return view('dokter.profil-dokter.edit', compact('user', 'dokter'));
    }

    /** Update (or create) dokter profile */
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'alamat' => ['required', 'string', 'max:1000'],
            'no_hp' => ['required', 'string', 'max:30'],
            'bidang_dokter' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L','P'])],
        ]);

        // save user name and email first
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $idUser = $user->iduser ?? $user->id;

        Dokter::updateOrCreate(
            ['id_user' => $idUser],
            [
                'alamat' => $data['alamat'],
                'no_hp' => $data['no_hp'],
                'bidang_dokter' => $data['bidang_dokter'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'id_user' => $idUser,
            ]
        );

        return redirect()->route('dokter.create')->with('success', 'Profil dokter berhasil diperbarui.');
    }
}
