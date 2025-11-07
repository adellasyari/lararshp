<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TindakanTerapi; // 1. Panggil Model
use App\Models\Kategori;
use App\Models\KategoriKlinis;

class TindakanTerapiController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data menggunakan Eloquent
        // Metode ini sederhana dan mengambil semua kolom.
        $tindakanTerapi = TindakanTerapi::all();

        // 3. Kirim data ke view
        return view('admin.tindakan-terapi.index', compact('tindakanTerapi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.tindakan-terapi.create', compact('kategori','kategoriKlinis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateTindakan($request);

        try {
            $this->createTindakan($validated);
            return redirect()->route('admin.tindakan-terapi.index')->with('success', 'Tindakan terapi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan tindakan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TindakanTerapi $tindakanTerapi)
    {
        $kategori = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.tindakan-terapi.edit', compact('tindakanTerapi','kategori','kategoriKlinis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TindakanTerapi $tindakanTerapi)
    {
        $validated = $this->validateTindakan($request, $tindakanTerapi->getKey());

        try {
            $tindakanTerapi->update([
                'kode' => $validated['kode'],
                'deskripsi_tindakan_terapi' => $validated['deskripsi_tindakan_terapi'],
                'idkategori' => $validated['idkategori'],
                'idkategori_klinis' => $validated['idkategori_klinis'] ?? null,
            ]);

            return redirect()->route('admin.tindakan-terapi.index')->with('success', 'Tindakan terapi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui tindakan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TindakanTerapi $tindakanTerapi)
    {
        $tindakanTerapi->delete();
        return redirect()->route('admin.tindakan-terapi.index')->with('success', 'Tindakan terapi berhasil dihapus.');
    }

    /**
     * Validate Tindakan Terapi input
     */
    protected function validateTindakan(Request $request, $id = null)
    {
        return $request->validate([
            'kode' => ['required','string','max:50'],
            'deskripsi_tindakan_terapi' => ['required','string','max:1000'],
            'idkategori' => ['required','exists:kategori,idkategori'],
            'idkategori_klinis' => ['nullable','exists:kategori_klinis,idkategori_klinis'],
        ],[
            'kode.required' => 'Kode tindakan wajib diisi.',
            'deskripsi_tindakan_terapi.required' => 'Deskripsi tindakan wajib diisi.',
            'idkategori.required' => 'Kategori wajib dipilih.',
        ]);
    }

    /**
     * Create Tindakan helper
     */
    protected function createTindakan(array $data)
    {
        return TindakanTerapi::create([
            'kode' => $data['kode'],
            'deskripsi_tindakan_terapi' => $data['deskripsi_tindakan_terapi'],
            'idkategori' => $data['idkategori'],
            'idkategori_klinis' => $data['idkategori_klinis'] ?? null,
        ]);
    }
}
