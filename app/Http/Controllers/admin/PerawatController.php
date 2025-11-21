<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Perawat;
use Illuminate\Support\Facades\Hash;

class PerawatController extends Controller
{
    /**
     * Display a listing of perawat users.
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q){
            $q->where('nama_role', 'Perawat');
        })->with('perawat')->paginate(15);

        return view('admin.perawat.index', compact('users'));
    }

    /**
     * Show form to create a new perawat user.
     */
    public function create()
    {
        return view('admin.perawat.create');
    }

    /**
     * Store a newly created perawat user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:6',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // assign Perawat role via pivot
        $role = Role::where('nama_role', 'Perawat')->first();
        if($role) {
            $user->roles()->attach($role->idrole, ['status' => 1]);
        }

        // create initial perawat record with provided fields
        try {
            Perawat::create([
                'id_user' => $user->iduser,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pendidikan' => $request->pendidikan,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        } catch (\Exception $e) {
            // ignore for now
        }

        return redirect()->route('admin.perawat.index')->with('success', 'Perawat berhasil ditambahkan.');
    }

    /**
     * Show form to edit perawat user.
     */
    public function edit($id)
    {
        $user = User::with('perawat')->findOrFail($id);
        return view('admin.perawat.edit', compact('user'));
    }

    /**
     * Update perawat user (email/password).
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'email' => 'required|email|max:255|unique:user,email,'.$user->iduser.',iduser',
            'password' => 'nullable|string|min:6',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        $user->email = $request->email;
        if($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // update perawat profile
        $perawat = Perawat::firstOrNew(['id_user' => $user->iduser]);
        $perawat->alamat = $request->alamat;
        $perawat->no_hp = $request->no_hp;
        $perawat->pendidikan = $request->pendidikan;
        $perawat->jenis_kelamin = $request->jenis_kelamin;
        try {
            $perawat->save();
        } catch (\Exception $e) {
            // ignore
        }

        return redirect()->route('admin.perawat.index')->with('success', 'Data perawat berhasil diperbarui.');
    }

    /**
     * Remove the specified perawat user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.perawat.index')->with('success', 'Perawat dihapus.');
    }
}
