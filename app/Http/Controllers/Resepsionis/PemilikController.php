<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\Role;
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
        return view('resepsionis.pemilik.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Align with admin store: expect an existing user selection
        $data = $request->validate([
            'iduser' => ['required'],
            'no_wa' => ['required','string','min:8','max:20'],
            'alamat' => ['nullable','string','max:500'],
        ],[
            'iduser.required' => 'User wajib dipilih.',
            'no_wa.required' => 'No WA wajib diisi.',
        ]);

        try {
            Pemilik::create([
                'iduser' => $data['iduser'],
                'no_wa' => trim($data['no_wa']),
                'alamat' => $data['alamat'] ?? null,
            ]);
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
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
        $pemilik = Pemilik::findOrFail($id);

        try {
            $pemilik->delete();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
