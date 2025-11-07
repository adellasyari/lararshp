<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriKlinis; // 1. Panggil Model

class KategoriKlinisController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data menggunakan Eloquent
        // Metode ini sederhana dan mengambil semua kolom.
        $kategoriKlinis = KategoriKlinis::all();

        // 3. Kirim data ke view
        return view('admin.kategori-klinis.index', compact('kategoriKlinis'));
    }

    /**
     * Show create form for Kategori Klinis
     */
    public function create()
    {
        return view('admin.kategori-klinis.create');
    }

    /**
     * Store a newly created Kategori Klinis
     */
    public function store(Request $request)
    {
        $this->validateKategoriKlinis($request);

        try {
            $this->createKategoriKlinis($request->only('nama_kategori_klinis'));
            return redirect()->route('admin.kategori-klinis.index')->with('success', 'Data kategori klinis berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Validation helper for Kategori Klinis
     */
    protected function validateKategoriKlinis(Request $request, $id = null)
    {
    $uniqueRule = $id ? 'unique:kategori_klinis,nama_kategori_klinis,' . $id . ',idkategori_klinis' : 'unique:kategori_klinis,nama_kategori_klinis';
        return $request->validate([
            'nama_kategori_klinis' => ['required', 'string', 'max:255', 'min:3', $uniqueRule],
        ], [
            'nama_kategori_klinis.required' => 'Nama kategori klinis wajib diisi.',
            'nama_kategori_klinis.unique' => 'Nama kategori klinis sudah ada.',
            'nama_kategori_klinis.min' => 'Nama kategori klinis minimal 3 karakter.',
            'nama_kategori_klinis.max' => 'Nama kategori klinis maksimal 255 karakter.',
        ]);
    }

    /**
     * Helper to create a KategoriKlinis record (applies formatting)
     */
    protected function createKategoriKlinis(array $data)
    {
        try {
            return KategoriKlinis::create([
                'nama_kategori_klinis' => $this->formatNamaKategoriKlinis($data['nama_kategori_klinis']),
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Format nama kategori klinis: trim + ucwords(lowercase)
     */
    protected function formatNamaKategoriKlinis($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $kategoriKlinis = KategoriKlinis::findOrFail($id);
        return view('admin.kategori-klinis.edit', compact('kategoriKlinis'));
    }

    /**
     * Update an existing Kategori Klinis
     */
    public function update(Request $request, $id)
    {
        $this->validateKategoriKlinis($request, $id);

        $kategoriKlinis = KategoriKlinis::findOrFail($id);

        try {
            $kategoriKlinis->nama_kategori_klinis = $this->formatNamaKategoriKlinis($request->input('nama_kategori_klinis'));
            $kategoriKlinis->save();

            return redirect()->route('admin.kategori-klinis.index')->with('success', 'Data kategori klinis berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a Kategori Klinis
     */
    public function destroy($id)
    {
        $kategoriKlinis = KategoriKlinis::findOrFail($id);

        try {
            $kategoriKlinis->delete();
            return redirect()->route('admin.kategori-klinis.index')->with('success', 'Data kategori klinis berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
