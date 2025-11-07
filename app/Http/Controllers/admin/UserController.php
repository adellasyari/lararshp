<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:user,email'],
            'password' => ['required','string','min:6','confirmed'],
            'roles' => ['nullable','array'],
            'roles.*' => ['exists:role,idrole'],
        ]);

        try {
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            if (!empty($validated['roles'])) {
                $user->roles()->sync($validated['roles']);
            }

            return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        // Use the loaded relation collection to avoid ambiguous column names in the generated SQL
        // (pluck() on the relation query produces a JOIN that selects `idrole` from both tables)
        $selectedRoles = $user->roles->pluck('idrole')->toArray();
        return view('admin.user.edit', compact('user','roles','selectedRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:user,email,' . $user->getKey() . ',iduser'],
            'password' => ['nullable','string','min:6','confirmed'],
            'roles' => ['nullable','array'],
            'roles.*' => ['exists:role,idrole'],
        ]);

        try {
            $user->nama = $validated['nama'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = $validated['password'];
            }
            $user->save();

            $user->roles()->sync($validated['roles'] ?? []);

            return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
}
