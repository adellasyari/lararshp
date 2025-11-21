<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\RasHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with('pemilik.user', 'rasHewan.jenisHewan')->get();
        return view('resepsionis.hewan.index', compact('pets'));
    }

    public function create()
    {
        // daftar pemilik seperti admin: gunakan model Pemilik
        $pemilik = Pemilik::with('user')->get();
        $ras = RasHewan::all();

        return view('resepsionis.hewan.create', compact('pemilik', 'ras'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'tanggal_lahir' => ['nullable','date'],
            'warna_tanda' => ['nullable','string','max:255'],
            'jenis_kelamin' => ['required', Rule::in(['J','B','L','P'])],
            'idpemilik' => ['required','integer','exists:pemilik,idpemilik'],
            'idras_hewan' => ['required','integer','exists:ras_hewan,idras_hewan'],
        ]);

        DB::transaction(function() use ($data) {
            Pet::create($data);
        });

        return redirect()->route('resepsionis.hewan.index')->with('success', 'Data hewan berhasil disimpan.');
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $pemilik = Pemilik::with('user')->get();
        $ras = RasHewan::all();

        return view('resepsionis.hewan.edit', compact('pet','pemilik','ras'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $data = $request->validate([
            'nama' => ['required','string','max:255'],
            'tanggal_lahir' => ['nullable','date'],
            'warna_tanda' => ['nullable','string','max:255'],
            'jenis_kelamin' => ['required', Rule::in(['J','B','L','P'])],
            'idpemilik' => ['required','integer','exists:pemilik,idpemilik'],
            'idras_hewan' => ['required','integer','exists:ras_hewan,idras_hewan'],
        ]);

        DB::transaction(function() use ($pet, $data) {
            $pet->update($data);
        });

        return redirect()->route('resepsionis.hewan.index')->with('success', 'Data hewan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);
        $pet->delete();

        return redirect()->route('resepsionis.hewan.index')->with('success', 'Data hewan berhasil dihapus.');
    }
}
