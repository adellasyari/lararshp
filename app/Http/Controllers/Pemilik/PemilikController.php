<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Pemilik;
use App\Models\User;

class PemilikController extends Controller
{
    /**
     * Show the authenticated pemilik's profile.
     */
    public function profile()
    {
        $user = auth()->user();
        $pemilik = Pemilik::where('iduser', $user->iduser ?? $user->id)->first();

        return view('pemilik.profil-pemilik.profile', compact('pemilik', 'user'));
    }

    /**
     * Show the edit form for the authenticated pemilik.
     */
    public function edit()
    {
        $user = auth()->user();
        $pemilik = Pemilik::where('iduser', $user->iduser ?? $user->id)->first();

        return view('pemilik.profil-pemilik.edit', compact('pemilik', 'user'));
    }

    /**
     * Update the authenticated pemilik's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id ?? $user->iduser;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId, 'id')],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'no_wa' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($validated, $userId) {
            // update users table
            $u = User::find($userId);
            if ($u) {
                $u->name = $validated['name'];
                $u->email = $validated['email'];
                $u->save();
            }

            // update or create pemilik record
            $pemilik = Pemilik::firstOrNew(['iduser' => $userId]);
            $pemilik->alamat = $validated['alamat'] ?? null;
            $pemilik->no_wa = $validated['no_wa'] ?? null;
            $pemilik->save();
        });

        return redirect()->route('pemilik.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
