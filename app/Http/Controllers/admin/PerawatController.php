<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Perawat;
use App\Models\RoleUser;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Ambil semua user untuk ditampilkan pada dropdown seperti pada DokterController
        $users = User::all();
        return view('admin.perawat.create', compact('users'));
    }

    /**
     * Store a newly created perawat user.
     */
    public function store(Request $request)
    {
        // The perawat create form now selects an existing user via `id_user`.
        $request->validate([
            'id_user' => 'required|exists:users,id|unique:perawat,id_user',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        // Use transaction to ensure consistency
        \Illuminate\Support\Facades\Log::info('PerawatController@store called', ['id_user' => $request->id_user]);
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $user = User::find($request->id_user);
            if (! $user) {
                return back()->withInput()->with('error', 'User tidak ditemukan pada tabel `users`.');
            }

            // ensure legacy users table has a matching row for FK constraints (perawat.id_user -> users.id)
            $existsInLegacy = \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->exists();
            if (! $existsInLegacy) {
                \Illuminate\Support\Facades\DB::table('users')->insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make(\Illuminate\Support\Str::random(32)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // assign Perawat role via pivot if not already assigned
            $role = Role::where('nama_role', 'Perawat')->first();
            if ($role) {
                // use syncWithoutDetaching to avoid duplicate entries
                $user->roles()->syncWithoutDetaching([$role->idrole => ['status' => 1]]);
            }

            // create perawat record
            $perawat = Perawat::create([
                'id_user' => $user->id,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pendidikan' => $request->pendidikan,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.perawat.index')->with('success', 'Perawat berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Failed to create Perawat', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal menambah perawat: ' . $e->getMessage());
        }
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
     * Update perawat profile (admin edits profile fields only).
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate only profile fields — email/password are not edited here
        $request->validate([
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            // update or create perawat profile
            $perawat = Perawat::firstOrNew(['id_user' => $user->iduser]);
            $perawat->alamat = $request->input('alamat');
            $perawat->no_hp = $request->input('no_hp');
            $perawat->pendidikan = $request->input('pendidikan');
            $perawat->jenis_kelamin = $request->input('jenis_kelamin');
            $perawat->save();

            DB::commit();
            return redirect()->route('admin.perawat.index')->with('success', 'Data perawat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update perawat profile', ['iduser' => $user->iduser, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui data perawat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified perawat user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            Log::info('PerawatController@destroy called', ['iduser' => $user->iduser]);

            // collect role_user ids for this user
            $roleUserIds = RoleUser::where('iduser', $user->iduser)->pluck('idrole_user');

            // delete dependent rekam_medis and temu_dokter rows if any
            if ($roleUserIds->isNotEmpty()) {
                RekamMedis::whereIn('dokter_pemeriksa', $roleUserIds)->delete();
                TemuDokter::whereIn('idrole_user', $roleUserIds)->delete();
            }

            // remove role_user rows (pivot entries)
            DB::table('role_user')->where('iduser', $user->iduser)->delete();

            // delete perawat record if exists
            Perawat::where('id_user', $user->iduser)->delete();

            // finally delete user
            $user->delete();

            DB::commit();
            return redirect()->route('admin.perawat.index')->with('success', 'Perawat dan relasinya berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete perawat/user', ['iduser' => $user->iduser, 'error' => $e->getMessage()]);

            $msg = $e->getMessage();
            if (stripos($msg, 'rekam_medis') !== false) {
                $userMessage = 'Gagal hapus: user ini masih dirujuk di tabel rekam_medis. Hapus atau alihkan rekam_medis terlebih dahulu.';
            } elseif (stripos($msg, 'temu_dokter') !== false) {
                $userMessage = 'Gagal hapus: user ini masih terkait temu_dokter. Hapus atau alihkan data temu_dokter terlebih dahulu.';
            } else {
                $userMessage = 'Gagal menghapus perawat: ' . $msg;
            }

            return redirect()->route('admin.perawat.index')->with('error', $userMessage);
        }
    }
}
