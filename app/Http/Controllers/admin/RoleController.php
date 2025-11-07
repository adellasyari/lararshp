<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::all();

        return view('admin.role.index', compact('role'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $validated = $this->validateRole($request);

        try {
            $this->createRole($validated);
            return redirect()->route('admin.role.index')->with('success', 'Role berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit(Role $role)
    {
        return view('admin.role.edit', compact('role'));
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $this->validateRole($request, $role->getKey());

        try {
            $role->update([
                'nama_role' => $validated['nama_role']
            ]);

            return redirect()->route('admin.role.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete role
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.role.index')->with('success', 'Role berhasil dihapus.');
    }

    /**
     * Validate Role input
     */
    protected function validateRole(Request $request, $id = null)
    {
        $rules = [
            'nama_role' => ['required','string','max:255']
        ];

        return $request->validate($rules, [
            'nama_role.required' => 'Nama role wajib diisi.'
        ]);
    }

    /**
     * Create Role helper
     */
    protected function createRole(array $data)
    {
        return Role::create([
            'nama_role' => $data['nama_role']
        ]);
    }
}
