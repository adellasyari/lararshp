<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisHewan; // 1. Panggil Model

class JenisHewanController extends Controller
{
    public function index()
    {
        // Query Builder
        $jenisHewan = \DB::table('jenis_hewan')
            ->select('idjenis_hewan', 'nama_jenis_hewan')
            ->get();

        return view('admin.jenis-hewan.index', compact('jenisHewan'));
    }

    /**
     * Show create form for Jenis Hewan
     */
    public function create()
    {
        return view('admin.jenis-hewan.create');
    }

    /**
     * Store a newly created Jenis Hewan
     */
    public function store(Request $request)
    {
        // Validasi input
        $this->validateJenisHewan($request);

        try {
            $this->createJenisHewan($request->only('nama_jenis_hewan'));

            return redirect()->route('admin.jenis-hewan.index')->with('success', 'Data jenis hewan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $jenisHewan = JenisHewan::findOrFail($id);
        return view('admin.jenis-hewan.edit', compact('jenisHewan'));
    }

    /**
     * Update an existing Jenis Hewan
     */
    public function update(Request $request, $id)
    {
        // Validasi input (mengabaikan record saat ini untuk unique)
        $this->validateJenisHewan($request, $id);

        $jenisHewan = JenisHewan::findOrFail($id);

        try {
            $jenisHewan->nama_jenis_hewan = $this->formatNamaJenisHewan($request->input('nama_jenis_hewan'));
            $jenisHewan->save();

            return redirect()->route('admin.jenis-hewan.index')->with('success', 'Data jenis hewan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a Jenis Hewan
     */
    public function destroy($id)
    {
        $jenisHewan = JenisHewan::findOrFail($id);

        try {
            $jenisHewan->delete();
            return redirect()->route('admin.jenis-hewan.index')->with('success', 'Data jenis hewan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Validation helper for Jenis Hewan
     */
    protected function validateJenisHewan(Request $request, $id = null)
    {
        $uniqueRule = $id ? 'unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan' : 'unique:jenis_hewan,nama_jenis_hewan';
        return $request->validate([
            'nama_jenis_hewan' => ['required', 'string', 'max:255', 'min:3', $uniqueRule],
        ], [
            'nama_jenis_hewan.required' => 'Nama jenis hewan wajib diisi.',
            'nama_jenis_hewan.unique' => 'Nama jenis hewan sudah ada.',
            'nama_jenis_hewan.min' => 'Nama jenis hewan minimal 3 karakter.',
            'nama_jenis_hewan.max' => 'Nama jenis hewan maksimal 255 karakter.',
        ]);
    }

    /**
     * Helper to create a JenisHewan record (applies formatting)
     */
    protected function createJenisHewan(array $data)
    {
        try {
            // Query Builder
            $jenisHewan = \DB::table('jenis_hewan')->insert([
                'nama_jenis_hewan' => $this->formatNamaJenisHewan($data['nama_jenis_hewan']),
            ]);
            return $jenisHewan;
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Format nama jenis hewan: trim + ucwords(lowercase)
     */
    protected function formatNamaJenisHewan($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}