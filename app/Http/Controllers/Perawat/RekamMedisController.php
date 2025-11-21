<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\TindakanTerapi;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Provide both patient queue and full rekam medis list so the view can show either
        $pets = Pet::with('pemilik', 'rasHewan.jenisHewan')->orderBy('nama')->get();

        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('perawat.rekam-medis.index', compact('pets', 'rekamMedis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pets = Pet::with('pemilik')->orderBy('nama')->get();
        // RoleUser model defines `role()` (singular) relation — use that to filter doctors
        $dokters = RoleUser::whereHas('role', function($q){ $q->where('nama_role','Dokter'); })->with('user')->get();
        $selectedPet = request()->query('idpet');
        $tindakanTerapis = TindakanTerapi::orderBy('kode')->get();
        return view('perawat.rekam-medis.create', compact('pets','dokters','selectedPet','tindakanTerapis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'anamnesa' => 'required|string',
            'diagnosa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'idpet' => 'required|exists:pets,idpet',
            'dokter_pemeriksa' => 'nullable|exists:role_users,idrole_user',
        ]);

        // legacy table uses created_at as tanggal
        $data['created_at'] = $data['tanggal'];
        unset($data['tanggal']);

        $rekam = RekamMedis::create($data);

        // persist selected tindakan if provided
        if ($request->filled('idkode_tindakan_terapi')) {
            DB::table('detail_rekam_medis')->insert([
                'idrekam_medis' => $rekam->getKey(),
                'idkode_tindakan_terapi' => $request->input('idkode_tindakan_terapi'),
                'detail' => null,
            ]);
        }

        return redirect()->route('perawat.rekam-medis.index')->with('success', 'Rekam Medis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Eager-load pet, owner->user, ras/jenis, and dokter (roleUser->user)
        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])->find($id);

        if (! $rekamMedis) {
            abort(404, 'Rekam Medis tidak ditemukan.');
        }

        // Fallback: if pet relation is not present, try to load Pet by idpet
        if ((! $rekamMedis->relationLoaded('pet') || ! $rekamMedis->pet) && $rekamMedis->idpet) {
            // ensure we load pemilik->user as well in the fallback
            $pet = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])->find($rekamMedis->idpet);
            if ($pet) {
                $rekamMedis->setRelation('pet', $pet);
            }
        }

        // fetch associated tindakan (if any)
        $tindakan = DB::table('detail_rekam_medis')
            ->join('kode_tindakan_terapi', 'detail_rekam_medis.idkode_tindakan_terapi', '=', 'kode_tindakan_terapi.idkode_tindakan_terapi')
            ->where('detail_rekam_medis.idrekam_medis', $rekamMedis->idrekam_medis)
            ->select('kode', 'deskripsi_tindakan_terapi')
            ->first();

        return view('perawat.rekam-medis.show', compact('rekamMedis', 'tindakan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Eager-load required relations so the view can access nested user/pemilik/ras data
        $rekamMedis = RekamMedis::with([
            'pet.pemilik.user',
            'pet.rasHewan.jenisHewan',
            'roleUser.user'
        ])->findOrFail($id);

        // Load supporting lists for the form
        $pets = Pet::with('pemilik.user', 'rasHewan.jenisHewan')->orderBy('nama')->get();
        $dokters = RoleUser::whereHas('role', function($q){ $q->where('nama_role','Dokter'); })->with('user')->get();

        // load tindakan list and current selected tindakan (if any)
        $tindakanTerapis = TindakanTerapi::orderBy('kode')->get();
        $selectedTindakan = DB::table('detail_rekam_medis')->where('idrekam_medis', $rekamMedis->idrekam_medis)->value('idkode_tindakan_terapi');

        return view('perawat.rekam-medis.edit', compact('rekamMedis','pets','dokters','tindakanTerapis','selectedTindakan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'anamnesa' => 'required|string',
            'diagnosa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'idpet' => 'required|exists:pets,idpet',
            'dokter_pemeriksa' => 'nullable|exists:role_users,idrole_user',
        ]);

        $data['created_at'] = $data['tanggal'];
        unset($data['tanggal']);

        $rekamMedis->update($data);

        // update detail_rekam_medis: remove existing and insert new if provided
        DB::table('detail_rekam_medis')->where('idrekam_medis', $rekamMedis->idrekam_medis)->delete();
        if ($request->filled('idkode_tindakan_terapi')) {
            DB::table('detail_rekam_medis')->insert([
                'idrekam_medis' => $rekamMedis->idrekam_medis,
                'idkode_tindakan_terapi' => $request->input('idkode_tindakan_terapi'),
                'detail' => null,
            ]);
        }

        return redirect()->route('perawat.rekam-medis.index')->with('success', 'Rekam Medis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();

        return redirect()->route('perawat.rekam-medis.index')->with('success', 'Rekam Medis berhasil dihapus.');
    }
}
