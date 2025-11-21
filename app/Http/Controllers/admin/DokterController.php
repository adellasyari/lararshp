<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Dokter;
// no Hash needed: admin no longer creates/edits user password here

class DokterController extends Controller
{
    /**
     * Display a listing of dokter users.
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q){
            $q->where('nama_role', 'Dokter');
        })->with('dokter')->paginate(15);

        return view('admin.dokter.index', compact('users'));
    }

    /**
     * Show form to create a new dokter user.
     */
    public function create()
    {
        // Ambil semua user dari tabel user
        $users = User::all();
        return view('admin.dokter.create', compact('users'));
    }

    /**
     * Store a newly created dokter user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:user,iduser|unique:dokter,id_user',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'bidang_dokter' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        try {
            Dokter::create([
                'id_dokter' => $request->id_user, // PK manual
                'id_user' => $request->id_user,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'bidang_dokter' => $request->bidang_dokter,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambah dokter: '.$e->getMessage());
        }

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Show form to edit dokter user.
     */
    public function edit($id)
    {
        $user = User::with('dokter')->findOrFail($id);
        return view('admin.dokter.edit', compact('user'));
    }

    /**
     * Update dokter profile (only profile fields). User email/password are not edited here.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'bidang_dokter' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        // update dokter profile fields
        $dokter = Dokter::firstOrNew(['id_user' => $user->iduser]);
        // Jika record baru, set PK id_dokter (karena PK manual/auto-increment)
        if (!$dokter->exists || empty($dokter->id_dokter)) {
            $dokter->id_dokter = $user->iduser;
        }
        $dokter->alamat = $request->alamat;
        $dokter->no_hp = $request->no_hp;
        $dokter->bidang_dokter = $request->bidang_dokter;
        $dokter->jenis_kelamin = $request->jenis_kelamin;
        try {
            $dokter->save();
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update data dokter: '.$e->getMessage());
        }

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified dokter user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter dihapus.');
    }
}
