<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// PASTIKAN INI MEMANGGIL MODEL YANG BENAR
use App\Models\Pemilik; 
use App\Models\User;
use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use Illuminate\Support\Facades\DB;

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

        DB::beginTransaction();
        try {
            // delete pets and their dependent records first
            $pets = Pet::where('idpemilik', $pemilik->idpemilik)->get();
            foreach ($pets as $pet) {
                // delete detail_rekam_medis linked to rekam_medis of this pet
                $rekams = RekamMedis::where('idpet', $pet->idpet)->get();
                foreach ($rekams as $rekam) {
                    DB::table('detail_rekam_medis')->where('idrekam_medis', $rekam->idrekam_medis)->delete();
                    $rekam->delete();
                }

                // delete temu_dokter for this pet
                TemuDokter::where('idpet', $pet->idpet)->delete();

                // delete pet
                $pet->delete();
            }

            // finally delete pemilik
            $pemilik->delete();

            DB::commit();
            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
