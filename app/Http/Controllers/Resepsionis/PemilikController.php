<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PemilikController extends Controller
{
    public function index()
    {
        // Ambil data pemilik seperti pada admin: gunakan model Pemilik dan relasi user
        $pemilik = Pemilik::with('user')->get();

        return view('resepsionis.pemilik.index', compact('pemilik'));
    }

    public function create()
    {
        $users = User::all();
            // Show typed-create form (resepsionis can input name/email/password)
            return view('resepsionis.pemilik.create');
    }

    public function store(Request $request)
    {
        // New flow: create or link a user based on email, mirror to admin `users` table, assign Pemilik role, then create pemilik
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'password' => ['required','string','min:6','confirmed'],
            'no_wa' => ['required','string','min:8','max:20'],
            'alamat' => ['nullable','string','max:500'],
        ],[
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_wa.required' => 'No WA wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            // Try to find existing user in `users` table by email
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                // Create in `users` table
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
            } else {
                // If user exists, optionally update name/password (skip password update by default)
                // $user->update(['name'=>$data['name']]);
            }

            // Mirror into legacy `users` table used by admin/dokter/perawat (avoid FK issues)
            $now = now();
            DB::table('users')->insertOrIgnore([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Ensure role_user entry for Pemilik (idrole = 5)
            $pemilikRoleId = 5;
            RoleUser::firstOrCreate([
                'iduser' => $user->id,
                'idrole' => $pemilikRoleId,
            ], ['status' => 1]);

            // Create pemilik record
            Pemilik::create([
                'iduser' => $user->id,
                'no_wa' => trim($data['no_wa']),
                'alamat' => $data['alamat'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }

        // old select-flow removed; store logic completed above using transaction
    }

    public function edit($id)
    {
        $pemilik = Pemilik::findOrFail($id);
        $users = User::all();
        return view('resepsionis.pemilik.edit', compact('pemilik','users'));
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);

        $data = $request->validate([
            'iduser' => ['required'],
            'no_wa' => ['required','string','min:8','max:20'],
            'alamat' => ['nullable','string','max:500'],
        ],[
            'iduser.required' => 'User wajib dipilih.',
            'no_wa.required' => 'No WA wajib diisi.',
        ]);

        try {
            $pemilik->iduser = $data['iduser'];
            $pemilik->no_wa = trim($data['no_wa']);
            $pemilik->alamat = $data['alamat'] ?? null;
            $pemilik->save();

            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        // Hapus tuntas: mulai transaksi, hapus semua data terkait pet, hapus pemilik, lalu hapus akun user
        DB::beginTransaction();
        try {
            $pemilik = Pemilik::findOrFail($id);

            // Simpan id user pemilik untuk dihapus nanti
            $idUser = $pemilik->iduser;

            // Ambil semua pet milik pemilik ini
            $petIds = $pemilik->pets()->pluck('idpet')->toArray();

            if (!empty($petIds)) {
                // Hapus detail_rekam_medis yang terkait dengan rekam_medis milik pet ini
                $rekamIds = RekamMedis::whereIn('idpet', $petIds)->pluck('idrekam_medis')->toArray();
                if (!empty($rekamIds)) {
                    DB::table('detail_rekam_medis')->whereIn('idrekam_medis', $rekamIds)->delete();
                }

                // Hapus rekam_medis untuk pet ini
                RekamMedis::whereIn('idpet', $petIds)->delete();

                // Hapus temu_dokter untuk pet ini
                TemuDokter::whereIn('idpet', $petIds)->delete();

                // Hapus data pet
                DB::table('pet')->whereIn('idpet', $petIds)->delete();
            }

            // Hapus pemilik
            $pemilik->delete();

            // Hapus akun login terkait jika ada
            if (!empty($idUser)) {
                // Hapus entri role_user terkait
                RoleUser::where('iduser', $idUser)->delete();

                // Hapus dari tabel users legacy jika ada
                DB::table('users')->where('id', $idUser)->delete();

                // Hapus user
                $user = User::find($idUser);
                if ($user) {
                    $user->delete();
                }
            }

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik dan data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
