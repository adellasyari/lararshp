<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// PASTIKAN INI MEMANGGIL MODEL YANG BENAR
use App\Models\Pemilik; 
use App\Models\User;

class PemilikController extends Controller
{
    public function index()
    {
        // PASTIKAN INI MENGAMBIL DATA PEMILIK (BUKAN JENISHEWAN::ALL())
        $pemilik = Pemilik::with('user')->get();

        // PASTIKAN INI MENGIRIM VARIABEL YANG BENAR
        return view('admin.pemilik.index', compact('pemilik'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $users = User::all();
        return view('admin.pemilik.create', compact('users'));
    }

    /**
     * Store new pemilik
     */
    public function store(Request $request)
    {
        $this->validatePemilik($request);

        try {
            $this->createPemilik($request->only('iduser','no_wa','alamat'));
            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    protected function validatePemilik(Request $request, $id = null)
    {
        $uniqueRule = '';
        return $request->validate([
            'iduser' => ['required'],
            'no_wa' => ['required','string','min:8','max:20',$uniqueRule],
            'alamat' => ['nullable','string','max:500'],
        ],[
            'iduser.required' => 'User wajib dipilih.',
            'no_wa.required' => 'No WA wajib diisi.',
        ]);
    }

    protected function createPemilik(array $data)
    {
        try {
            return Pemilik::create([
                'iduser' => $data['iduser'],
                'no_wa' => $this->formatNoWa($data['no_wa']),
                'alamat' => $data['alamat'] ?? null,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    protected function formatNoWa($no)
    {
        return trim($no);
    }

    public function edit($id)
    {
        $pemilik = Pemilik::findOrFail($id);
        $users = User::all();
        return view('admin.pemilik.edit', compact('pemilik','users'));
    }

    public function update(Request $request, $id)
    {
        $this->validatePemilik($request, $id);

        $pemilik = Pemilik::findOrFail($id);

        try {
            $pemilik->iduser = $request->input('iduser');
            $pemilik->no_wa = $this->formatNoWa($request->input('no_wa'));
            $pemilik->alamat = $request->input('alamat');
            $pemilik->save();

            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $pemilik = Pemilik::findOrFail($id);

        try {
            $pemilik->delete();
            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
