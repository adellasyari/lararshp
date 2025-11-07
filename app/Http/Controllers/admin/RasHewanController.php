<?php

// 1. PASTIKAN 'A' di 'Admin' adalah huruf besar
namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RasHewan; 
use App\Models\JenisHewan;

class RasHewanController extends Controller
{
    public function index()
    {
        // 2. Baris ini sudah benar
        $list_ras = RasHewan::with('jenisHewan')->get();

        // 3. Baris ini juga sudah benar
        return view('admin.ras-hewan.index', compact('list_ras'));
    }

    public function create()
    {
        $jenis = JenisHewan::all();
        return view('admin.ras-hewan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $this->validateRas($request);

        try {
            RasHewan::create([
                'nama_ras' => trim($request->input('nama_ras')),
                'idjenis_hewan' => $request->input('idjenis_hewan'),
            ]);

            return redirect()->route('admin.ras-hewan.index')->with('success', 'Data ras hewan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: '.$e->getMessage())->withInput();
        }
    }

    protected function validateRas(Request $request, $id = null)
    {
        return $request->validate([
            'nama_ras' => ['required','string','min:2','max:255'],
            'idjenis_hewan' => ['required','exists:jenis_hewan,idjenis_hewan'],
        ],[
            'nama_ras.required' => 'Nama ras wajib diisi.',
            'idjenis_hewan.required' => 'Jenis hewan wajib dipilih.',
        ]);
    }

    public function edit($id)
    {
        $ras = RasHewan::findOrFail($id);
        $jenis = JenisHewan::all();
        return view('admin.ras-hewan.edit', compact('ras','jenis'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRas($request, $id);

        $ras = RasHewan::findOrFail($id);

        try {
            $ras->nama_ras = trim($request->input('nama_ras'));
            $ras->idjenis_hewan = $request->input('idjenis_hewan');
            $ras->save();

            return redirect()->route('admin.ras-hewan.index')->with('success', 'Data ras berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $ras = RasHewan::findOrFail($id);
        try {
            $ras->delete();
            return redirect()->route('admin.ras-hewan.index')->with('success', 'Data ras berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
