<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori; // 1. Panggil Model

class KategoriController extends Controller
{
    public function index()
    {
        // 2. Ambil semua data menggunakan Eloquent
        // Metode ini sederhana dan mengambil semua kolom.
        $kategori = Kategori::all();

        // 3. Kirim data ke view
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Show create form for Kategori
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created Kategori
     */
    public function store(Request $request)
    {
        $this->validateKategori($request);

        try {
            $this->createKategori($request->only('nama_kategori'));
            return redirect()->route('admin.kategori.index')->with('success', 'Data kategori berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Validation helper for Kategori
     */
    protected function validateKategori(Request $request, $id = null)
    {
        $uniqueRule = $id ? 'unique:kategori,nama_kategori,' . $id . ',idkategori' : 'unique:kategori,nama_kategori';
        return $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'min:3', $uniqueRule],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
            'nama_kategori.min' => 'Nama kategori minimal 3 karakter.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
        ]);
    }

    /**
     * Helper to create a Kategori record (applies formatting)
     */
    protected function createKategori(array $data)
    {
        try {
            return Kategori::create([
                'nama_kategori' => $this->formatNamaKategori($data['nama_kategori']),
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Format nama kategori: trim + ucwords(lowercase)
     */
    protected function formatNamaKategori($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update an existing Kategori
     */
    public function update(Request $request, $id)
    {
        $this->validateKategori($request, $id);

        $kategori = Kategori::findOrFail($id);

        try {
            $kategori->nama_kategori = $this->formatNamaKategori($request->input('nama_kategori'));
            $kategori->save();

            return redirect()->route('admin.kategori.index')->with('success', 'Data kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a Kategori
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        try {
            $kategori->delete();
            return redirect()->route('admin.kategori.index')->with('success', 'Data kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
