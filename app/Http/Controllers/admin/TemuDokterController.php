<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemuDokter;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TemuDokterController extends Controller
{
    public function index()
    {
        $temuDokters = TemuDokter::with(['dokter.user', 'pet', 'pemilik'])
            ->orderBy('waktu_daftar', 'desc')
            ->get();

        return view('admin.temu-dokter.index', compact('temuDokters'));
    }

    public function create()
    {
        // daftar dokter (role_user entries yang berperan sebagai Dokter)
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();

        $pets = Pet::with('pemilik.user')->get();

        return view('admin.temu-dokter.create', compact('dokters','pets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idrole_user' => ['required','integer','exists:role_user,idrole_user'],
            'idpet' => ['required','integer','exists:pet,idpet'],
            'tanggal' => ['required','date'],
            'waktu' => ['required','date_format:H:i'],
        ]);

        $pet = Pet::findOrFail($data['idpet']);
        $data['idpemilik'] = $pet->idpemilik;

        // combine date + time into waktu_daftar (timestamp)
        $waktu_daftar = $data['tanggal'] . ' ' . $data['waktu'];

        // compute next no_urut (simple global increment)
        $nextNo = (int) TemuDokter::max('no_urut') + 1;

        DB::transaction(function() use ($data, $waktu_daftar, $nextNo) {
            TemuDokter::create([
                'idpet' => $data['idpet'],
                'idpemilik' => $data['idpemilik'],
                'waktu_daftar' => $waktu_daftar,
                'idrole_user' => $data['idrole_user'],
                'no_urut' => $nextNo,
                'status' => 0,
            ]);
        });

        return redirect()->route('admin.temu-dokter.index')->with('success', 'Pendaftaran temu dokter berhasil disimpan.');
    }

    public function edit($id)
    {
        $temu = TemuDokter::findOrFail($id);
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();
        $pets = Pet::with('pemilik.user')->get();

        return view('admin.temu-dokter.edit', compact('temu','dokters','pets'));
    }

    public function update(Request $request, $id)
    {
        $temu = TemuDokter::findOrFail($id);

        $data = $request->validate([
            'idrole_user' => ['required','integer','exists:role_user,idrole_user'],
            'idpet' => ['required','integer','exists:pet,idpet'],
            'tanggal' => ['required','date'],
            'waktu' => ['required','date_format:H:i'],
            'status' => ['nullable','integer'],
        ]);

        $pet = Pet::findOrFail($data['idpet']);
        $data['idpemilik'] = $pet->idpemilik;

        // combine date + time into waktu_daftar
        $waktu_daftar = $data['tanggal'] . ' ' . $data['waktu'];

        DB::transaction(function() use ($temu, $data, $waktu_daftar) {
            $temu->update([
                'idrole_user' => $data['idrole_user'],
                'idpet' => $data['idpet'],
                'idpemilik' => $data['idpemilik'],
                'waktu_daftar' => $waktu_daftar,
                'status' => $data['status'] ?? $temu->status,
            ]);
        });

        return redirect()->route('admin.temu-dokter.index')->with('success', 'Data temu dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $temu = TemuDokter::findOrFail($id);
        $temu->delete();

        return redirect()->route('admin.temu-dokter.index')->with('success', 'Pendaftaran temu dokter berhasil dihapus.');
    }
}
