<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Dokter;
use App\Models\RoleUser;
use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
        // validation rules: either use existing user or create new user
        $rules = [
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'bidang_dokter' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ];

        $creatingUser = $request->has('create_user') && $request->create_user;
        if ($creatingUser) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:6|confirmed';
        } else {
            $rules['id_user'] = 'required|exists:users,id|unique:dokter,id_user';
        }

        $validated = $request->validate($rules);

        \Illuminate\Support\Facades\Log::info('DokterController@store called', ['creatingUser' => $creatingUser, 'id_user' => $request->id_user ?? null]);

        DB::beginTransaction();
        try {
            if ($creatingUser) {
                // create the user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // ensure role_user entry for Dokter (idrole = 2)
                RoleUser::firstOrCreate([
                    'iduser' => $user->id,
                    'idrole' => 2,
                ], [
                    'status' => 1,
                ]);
            } else {
                $user = User::find($request->id_user);
                if (! $user) {
                    return back()->withInput()->with('error', 'User tidak ditemukan pada tabel `users`.');
                }
            }

            // Ensure legacy `users` row exists for FK (if project still needs it)
            $existsInLegacy = DB::table('users')->where('id', $user->id)->exists();
            if (! $existsInLegacy) {
                DB::table('users')->insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make(Str::random(32)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Illuminate\Support\Facades\Log::info('Inserted legacy users row for FK', ['id' => $user->id]);
            }

            // create dokter row, set id_dokter to user's id (consistent with existing app conventions)
            $dokter = Dokter::create([
                'id_dokter' => $user->id,
                'id_user' => $user->id,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'bidang_dokter' => $request->bidang_dokter,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();
            \Illuminate\Support\Facades\Log::info('Dokter created', ['id_dokter' => $dokter->id_dokter ?? null]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Failed to create Dokter', ['error' => $e->getMessage()]);
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
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:50',
            'bidang_dokter' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
        ]);

        // Update user's name first
        $user->name = $request->input('name');
        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update nama pengguna: '.$e->getMessage());
        }

        // update dokter profile fields
        $dokter = Dokter::firstOrNew(['id_user' => $user->id]);
        // Jika record baru, set PK id_dokter (karena PK manual/auto-increment)
        if (!$dokter->exists || empty($dokter->id_dokter)) {
            $dokter->id_dokter = $user->id;
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

        // safety: prevent deleting currently authenticated user
        if (auth()->check() && auth()->id() == $user->id) {
            return redirect()->route('admin.dokter.index')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        DB::beginTransaction();
        try {
            // 1) Remove rekam_medis and its details that reference this user's role_user ids
            $roleUserIds = \App\Models\RoleUser::where('iduser', $user->id)->pluck('idrole_user');
            if ($roleUserIds->isNotEmpty()) {
                // Find rekam_medis referencing these role_user ids as dokter_pemeriksa
                $rekamList = \App\Models\RekamMedis::whereIn('dokter_pemeriksa', $roleUserIds)->get();
                foreach ($rekamList as $rekam) {
                    // delete detail_rekam_medis
                    DB::table('detail_rekam_medis')->where('idrekam_medis', $rekam->idrekam_medis)->delete();
                    // delete rekam_medis
                    $rekam->delete();
                }

                // delete temu_dokter rows referencing role_user
                \App\Models\TemuDokter::whereIn('idrole_user', $roleUserIds)->delete();

                // finally delete role_user rows for this user
                DB::table('role_user')->where('iduser', $user->id)->delete();
            }

            // 2) delete dokter record (if exists)
            \App\Models\Dokter::where('id_user', $user->id)->delete();

            // 3) If user is also a Pemilik, delete their pemilik record and related pets/rekam_medis
            $pemilik = Pemilik::where('iduser', $user->id)->first();
            if ($pemilik) {
                $pets = Pet::where('idpemilik', $pemilik->idpemilik)->get();
                foreach ($pets as $pet) {
                    // delete detail_rekam_medis -> rekam_medis
                    $rekams = RekamMedis::where('idpet', $pet->idpet)->get();
                    foreach ($rekams as $r) {
                        DB::table('detail_rekam_medis')->where('idrekam_medis', $r->idrekam_medis)->delete();
                        $r->delete();
                    }

                    // delete temu_dokter rows for this pet
                    TemuDokter::where('idpet', $pet->idpet)->delete();

                    // delete pet
                    $pet->delete();
                }

                // delete pemilik
                $pemilik->delete();
            }

            // 3) delete the user
            $user->delete();

            DB::commit();
            return redirect()->route('admin.dokter.index')->with('success', 'Dokter dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Failed to delete dokter user', ['id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.dokter.index')->with('error', 'Gagal menghapus dokter: ' . $e->getMessage());
        }
    }
}
