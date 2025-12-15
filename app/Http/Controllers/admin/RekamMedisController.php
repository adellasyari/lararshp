<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\Pet;
use App\Models\RoleUser;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekamMedis = RekamMedis::with([
            'pet.pemilik.user', // pet -> pemilik -> user
            'pet.rasHewan',     // pet -> rasHewan
            'roleUser.user'     // dokter via roleUser -> user
        ])->orderByDesc('idrekam_medis')->get();

        return view('admin.rekam-medis.index', compact('rekamMedis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data pets beserta pemilik->user untuk dropdown
        $pets = Pet::with('pemilik.user')->get();

        // Ambil daftar dokter. Project stores roles in role_user so we keep RoleUser
        $doctors = RoleUser::with('user','role')
            ->where('status', 1)
            ->where('idrole', 2) // asumsi idrole 2 = Dokter
            ->get();

        return view('admin.rekam-medis.create', compact('pets','doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRekamMedis($request);

        try {
            $this->createRekamMedis($validated);
            return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RekamMedis $rekamMedis)
    {
        return view('admin.rekam-medis.show', compact('rekamMedis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekamMedis $rekamMedis)
    {
        $pets = Pet::with('pemilik.user')->get();

        $doctors = RoleUser::with('user','role')
            ->where('status', 1)
            ->where('idrole', 2)
            ->get();

        return view('admin.rekam-medis.edit', compact('rekamMedis','pets','doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $this->validateRekamMedis($request, $rekamMedis->getKey());

        try {
            $rekamMedis->update([
                'created_at' => $validated['tanggal'],
                'anamnesa' => $validated['anamnesa'],
                'diagnosa' => $validated['diagnosa'],
                'temuan_klinis' => $validated['temuan_klinis'] ?? null,
                'idpet' => $validated['idpet'],
                'dokter_pemeriksa' => $validated['dokter_pemeriksa'],
            ]);

            return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui rekam medis: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();

        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil dihapus.');
    }

    /**
     * Validate Rekam Medis input
     */
    protected function validateRekamMedis(Request $request, $id = null)
    {
        return $request->validate([
            'tanggal' => ['required','date'],
            'anamnesa' => ['required','string','max:1000'],
            'diagnosa' => ['required','string','max:1000'],
            'temuan_klinis' => ['nullable','string','max:1000'],
            'idpet' => ['required','exists:pet,idpet'],
            'dokter_pemeriksa' => ['required','exists:role_user,idrole_user'],
        ],[
            'idpet.required' => 'Pet wajib dipilih.',
            'dokter_pemeriksa.required' => 'Dokter wajib dipilih.',
            'tanggal.required' => 'Tanggal periksa wajib diisi.',
            'anamnesa.required' => 'Anamnesa wajib diisi.',
            'diagnosa.required' => 'Diagnosa wajib diisi.',
        ]);
    }

    /**
     * Create Rekam Medis helper
     */
    protected function createRekamMedis(array $data)
    {
        try {
            return RekamMedis::create([
                'created_at' => $data['tanggal'],
                'anamnesa' => $data['anamnesa'],
                'diagnosa' => $data['diagnosa'],
                'temuan_klinis' => $data['temuan_klinis'] ?? null,
                'idpet' => $data['idpet'],
                'dokter_pemeriksa' => $data['dokter_pemeriksa'],
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan rekam medis: ' . $e->getMessage());
        }
    }
}
