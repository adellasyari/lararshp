<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use App\Models\Dokter;
use App\Models\Perawat;
use App\Models\Pemilik;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;

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
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
            'roles' => ['nullable','array'],
            'roles.*' => ['exists:role,idrole'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
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
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,' . $user->getKey() . ',id'],
            'password' => ['nullable','string','min:6','confirmed'],
            'roles' => ['nullable','array'],
            'roles.*' => ['exists:role,idrole'],
        ]);

        try {
            $user->name = $validated['name'];
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
     * mengatur penghapusan user dan relasinya.
     */
    public function destroy(User $user)
    {
        Log::info('UserController@destroy called', ['id' => $user->id]);

        // Safety: prevent deleting currently authenticated user
        if (Auth::id() && Auth::id() == $user->id) {
            return redirect()->route('admin.user.index')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        DB::beginTransaction();
        try {
            // 1) If user is a Pemilik, delete their pets and related records first
            $pemilik = Pemilik::where('iduser', $user->id)->first();
            if ($pemilik) {
                Log::info('Found pemilik record, cleaning pets', ['idpemilik' => $pemilik->idpemilik]);

                $pets = Pet::where('idpemilik', $pemilik->idpemilik)->get();
                foreach ($pets as $pet) {
                    Log::info('Cleaning RekamMedis for pet', ['idpet' => $pet->idpet]);
                    $rekamList = RekamMedis::where('idpet', $pet->idpet)->get();
                        foreach ($rekamList as $rekam) {
                            Log::info('Deleting detail_rekam_medis for rekam', ['idrekam_medis' => $rekam->idrekam_medis]);
                            DB::table('detail_rekam_medis')->where('idrekam_medis', $rekam->idrekam_medis)->delete();

                            Log::info('Deleting rekam_medis record', ['idrekam_medis' => $rekam->idrekam_medis]);
                            $rekam->delete();
                        }

                        Log::info('Deleting TemuDokter for pet', ['idpet' => $pet->idpet]);
                        TemuDokter::where('idpet', $pet->idpet)->delete();

                        Log::info('Deleting Pet record (soft delete)', ['idpet' => $pet->idpet]);
                        $pet->delete();
                }

                Log::info('Deleting Pemilik record (soft delete)', ['idpemilik' => $pemilik->idpemilik]);
                $pemilik->delete();
            }

            // 2) If user has role_user entries (e.g. dokter), remove dependent rekam_medis and temu_dokter first
            $roleUserIds = RoleUser::where('iduser', $user->id)->pluck('idrole_user');
            if ($roleUserIds->isNotEmpty()) {
                Log::info('Found role_user ids, cleaning dependent records', ['ids' => $roleUserIds->toArray()]);

                // For each rekam_medis that references these role_user ids as dokter_pemeriksa,
                // delete detail_rekam_medis and temu_dokter entries before deleting rekam_medis
                $rekamByDokter = RekamMedis::whereIn('dokter_pemeriksa', $roleUserIds)->get();
                foreach ($rekamByDokter as $rekam) {
                    Log::info('Deleting detail_rekam_medis for rekam (dokter)', ['idrekam_medis' => $rekam->idrekam_medis]);
                    DB::table('detail_rekam_medis')->where('idrekam_medis', $rekam->idrekam_medis)->delete();

                    Log::info('Deleting rekam_medis record (dokter)', ['idrekam_medis' => $rekam->idrekam_medis]);
                    $rekam->delete();
                }

                Log::info('Deleting temu_dokter rows that reference role_user', ['ids' => $roleUserIds->toArray()]);
                TemuDokter::whereIn('idrole_user', $roleUserIds)->delete();

                Log::info('Deleting role_user rows for user', ['id' => $user->id]);
                DB::table('role_user')->where('iduser', $user->id)->delete();
            } else {
                Log::info('No role_user rows found for user', ['id' => $user->id]);
            }

            // 3) Remove any Dokter/Perawat records tied to this user (defensive)
            Dokter::where('id_user', $user->id)->delete();
            Perawat::where('id_user', $user->id)->delete();

            // 4) Finally delete the user
            Log::info('Deleting user record', ['id' => $user->id]);
            $user->delete();

            DB::commit();
            Log::info('User deleted successfully', ['iduser' => $user->iduser]);
            return redirect()->route('admin.user.index')->with('success', 'User dan relasinya berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user', ['id' => $user->id, 'error' => $e->getMessage()]);

            // Provide a specific message when FK constraints block the delete
            $msg = $e->getMessage();
            if (stripos($msg, 'rekam_medis') !== false) {
                $userMessage = 'Gagal menghapus: user ini masih dirujuk di tabel rekam_medis. Hapus atau alihkan rekam_medis terlebih dahulu.';
            } elseif (stripos($msg, 'temu_dokter') !== false) {
                $userMessage = 'Gagal menghapus: user ini masih terkait reservasi/temu_dokter. Hapus atau alihkan data temu_dokter terlebih dahulu.';
            } else {
                $userMessage = 'Gagal menghapus user: ' . $msg;
            }

            return redirect()->route('admin.user.index')->with('error', $userMessage);
        }
    }
}
