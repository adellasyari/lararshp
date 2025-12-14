<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 1. Panggil model 'Pet' yang baru
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\RasHewan;

// 2. Ubah nama class
class PetController extends Controller
{
    public function index()
    {
        // 3. Ambil data dari model 'Pet'
        // 4. Perbaiki Eager Loading:
        //    Kita ambil 'rasHewan' dan 'jenisHewan' yang ada DI DALAM 'rasHewan'
        $pet = Pet::with('pemilik.user', 'rasHewan.jenisHewan')->get();

        // 5. Kirim data ke view 'admin.pet.index' dengan variabel 'pet'
        return view('admin.pet.index', compact('pet'));
    }

    /**
     * Show create form for Pet
     */
    public function create()
    {
        $pemilik = Pemilik::with('user')->get();
        $ras = RasHewan::with('jenisHewan')->get();
        return view('admin.pet.create', compact('pemilik','ras'));
    }

    /**
     * Store newly created Pet
     */
    public function store(Request $request)
    {
        $this->validatePet($request);

        try {
            $this->createPet($request->only(['nama','tanggal_lahir','warna_tanda','jenis_kelamin','idpemilik','idras_hewan']));
            return redirect()->route('admin.pet.index')->with('success', 'Data hewan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    protected function validatePet(Request $request, $id = null)
    {
        return $request->validate([
            'nama' => ['required','string','min:2','max:255'],
            'tanggal_lahir' => ['nullable','date'],
            'warna_tanda' => ['nullable','string','max:255'],
            // Accept new J/B codes for animals; keep legacy L/P during transition
            'jenis_kelamin' => ['required','in:J,B,L,P'],
            'idpemilik' => ['required','exists:pemilik,idpemilik'],
            'idras_hewan' => ['required','exists:ras_hewan,idras_hewan'],
        ],[
            'nama.required' => 'Nama hewan wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'idpemilik.required' => 'Pemilik wajib dipilih.',
            'idras_hewan.required' => 'Ras hewan wajib dipilih.',
        ]);
    }

    protected function createPet(array $data)
    {
        try {
            return Pet::create([
                'nama' => trim($data['nama']),
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'warna_tanda' => $data['warna_tanda'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'idpemilik' => $data['idpemilik'],
                'idras_hewan' => $data['idras_hewan'],
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $pemilik = Pemilik::with('user')->get();
        $ras = RasHewan::with('jenisHewan')->get();
        return view('admin.pet.edit', compact('pet','pemilik','ras'));
    }

    public function update(Request $request, $id)
    {
        $this->validatePet($request, $id);

        $pet = Pet::findOrFail($id);

        try {
            $pet->nama = trim($request->input('nama'));
            $pet->tanggal_lahir = $request->input('tanggal_lahir');
            $pet->warna_tanda = $request->input('warna_tanda');
            $pet->jenis_kelamin = $request->input('jenis_kelamin');
            $pet->idpemilik = $request->input('idpemilik');
            $pet->idras_hewan = $request->input('idras_hewan');
            $pet->save();

            return redirect()->route('admin.pet.index')->with('success', 'Data hewan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);

        try {
            $pet->delete();
            return redirect()->route('admin.pet.index')->with('success', 'Data hewan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}