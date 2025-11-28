<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\TindakanTerapi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Provide patient queue and rekam medis list similar to Perawat view
        // Order pets so those with the most recent rekam_medis appear first.
        $orderedPetIds = DB::table('pet as p')
            ->leftJoin('rekam_medis as rm', 'p.idpet', '=', 'rm.idpet')
            ->select('p.idpet', DB::raw('MAX(rm.created_at) as last_rekam'))
            ->groupBy('p.idpet')
            ->orderByDesc('last_rekam')
            ->orderBy('p.nama')
            ->pluck('idpet')
            ->toArray();

        $petsCollection = Pet::with('pemilik.user', 'rasHewan.jenisHewan')
            ->whereIn('idpet', $orderedPetIds)
            ->get()
            ->keyBy('idpet');

        $pets = collect($orderedPetIds)->map(function($id) use ($petsCollection) {
            return $petsCollection->get($id);
        })->filter()->values();
        $rekamMediss = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.rekam-medis.index', compact('pets', 'rekamMediss'));
    }

    /**
     * Periksa (create or open) - dokter clicks "Periksa" from patient list.
     * If there is an existing rekam medis for the pet, open the latest; otherwise create a new rekam and open it.
     */
    public function periksa($idpet)
    {
        // Redirect to the create form and preselect the pet so dokter can create the rekam medis
        return redirect()->route('dokter.rekam-medis.create', ['idpet' => $idpet]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pets = Pet::with('pemilik')->orderBy('nama')->get();
        $dokters = RoleUser::whereHas('role', function($q){ $q->where('nama_role','Dokter'); })->with('user')->get();
        $selectedPet = request()->query('idpet');
        $petSelected = null;
        $rekamMedis = null;
        $detailTindakan = collect();
        if ($selectedPet) {
            $petSelected = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])->find($selectedPet);

            // if there is an existing rekam medis for this pet, load the latest and its tindakan
            $last = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])
                ->where('idpet', $selectedPet)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last) {
                $rekamMedis = $last;

                $detailTindakan = DB::table('detail_rekam_medis')
                    ->join('kode_tindakan_terapi', 'detail_rekam_medis.idkode_tindakan_terapi', '=', 'kode_tindakan_terapi.idkode_tindakan_terapi')
                    ->leftJoin('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
                    ->leftJoin('kategori_klinis', 'kode_tindakan_terapi.idkategori_klinis', '=', 'kategori_klinis.idkategori_klinis')
                    ->where('detail_rekam_medis.idrekam_medis', $rekamMedis->idrekam_medis)
                    ->select('detail_rekam_medis.iddetail_rekam_medis', 'kode_tindakan_terapi.idkode_tindakan_terapi', 'kode', 'deskripsi_tindakan_terapi', 'detail', 'kategori.nama_kategori as kategori', 'kategori_klinis.nama_kategori_klinis as kategori_klinis')
                    ->get();
            }
        }
        // Load available tindakan terapi codes for dropdown
        $tindakanTerapis = TindakanTerapi::orderBy('kode')->get();
        // pass $petSelected, $rekamMedis and $detailTindakan so the view can display basic pet info and existing rekam data when opening from Periksa
        return view('dokter.rekam-medis.create', compact('pets','dokters','selectedPet','tindakanTerapis','petSelected','rekamMedis','detailTindakan'));
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
            'idpet' => 'required|exists:pet,idpet',
            'dokter_pemeriksa' => 'nullable|exists:role_user,idrole_user',
            'idkode_tindakan_terapi' => 'nullable|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
        ]);

        $data['created_at'] = $data['tanggal'];
        unset($data['tanggal']);

        $rekam = RekamMedis::create($data);

        // If a tindakan was selected, save it to detail_rekam_medis
        if ($request->filled('idkode_tindakan_terapi')) {
            DB::table('detail_rekam_medis')->insert([
                'idrekam_medis' => $rekam->getKey(),
                'idkode_tindakan_terapi' => $request->input('idkode_tindakan_terapi'),
                'detail' => null,
            ]);
        }

        return redirect()->route('dokter.rekam-medis.show', ['rekam_medi' => $rekam->getKey()])->with('success', 'Rekam Medis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])->find($id);

        if (! $rekamMedis) {
            abort(404, 'Rekam Medis tidak ditemukan.');
        }

        if ((! $rekamMedis->relationLoaded('pet') || ! $rekamMedis->pet) && $rekamMedis->idpet) {
            $pet = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])->find($rekamMedis->idpet);
            if ($pet) {
                $rekamMedis->setRelation('pet', $pet);
            }
        }

        // fetch associated tindakan for this rekam (if any) - keep for current rekam
        $detailTindakan = DB::table('detail_rekam_medis')
            ->join('kode_tindakan_terapi', 'detail_rekam_medis.idkode_tindakan_terapi', '=', 'kode_tindakan_terapi.idkode_tindakan_terapi')
            ->leftJoin('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
            ->leftJoin('kategori_klinis', 'kode_tindakan_terapi.idkategori_klinis', '=', 'kategori_klinis.idkategori_klinis')
            ->where('detail_rekam_medis.idrekam_medis', $rekamMedis->idrekam_medis)
            ->select('detail_rekam_medis.iddetail_rekam_medis', 'detail_rekam_medis.idrekam_medis', 'kode_tindakan_terapi.idkode_tindakan_terapi', 'kode', 'deskripsi_tindakan_terapi', 'detail', 'kategori.nama_kategori as kategori', 'kategori_klinis.nama_kategori_klinis as kategori_klinis')
            ->get();

        // load ALL rekam medis for this pet (read-only list)
        // but only include records that have meaningful content (filled by Perawat/Dokter)
        // i.e. at least one of anamnesa/temuan_klinis/diagnosa is non-empty OR there
        // exists related detail_rekam_medis rows.
        // Include rekam_medis that either have tindakan/terapi (detail_rekam_medis)
        // OR have been filled by Perawat (non-empty anamnesa/temuan_klinis/diagnosa).
        $allRekamMedis = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])
            ->where('idpet', $rekamMedis->idpet)
            ->where(function($q) {
                $q->whereExists(function($sub) {
                    $sub->select(DB::raw(1))
                        ->from('detail_rekam_medis')
                        ->whereColumn('detail_rekam_medis.idrekam_medis', 'rekam_medis.idrekam_medis');
                })
                ->orWhere(function($q2){
                    $q2->whereNotNull('anamnesa')->where('anamnesa','<>','')
                       ->orWhereNotNull('temuan_klinis')->where('temuan_klinis','<>','')
                       ->orWhereNotNull('diagnosa')->where('diagnosa','<>','');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // load tindakan for all these rekam ids grouped by idrekam_medis
        $rekamIds = $allRekamMedis->pluck('idrekam_medis')->toArray();
        $allDetails = collect();
        if (!empty($rekamIds)) {
            $rows = DB::table('detail_rekam_medis')
                ->join('kode_tindakan_terapi', 'detail_rekam_medis.idkode_tindakan_terapi', '=', 'kode_tindakan_terapi.idkode_tindakan_terapi')
                ->leftJoin('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
                ->leftJoin('kategori_klinis', 'kode_tindakan_terapi.idkategori_klinis', '=', 'kategori_klinis.idkategori_klinis')
                ->whereIn('detail_rekam_medis.idrekam_medis', $rekamIds)
                ->select('detail_rekam_medis.iddetail_rekam_medis', 'detail_rekam_medis.idrekam_medis', 'kode_tindakan_terapi.idkode_tindakan_terapi', 'kode', 'deskripsi_tindakan_terapi', 'detail', 'kategori.nama_kategori as kategori', 'kategori_klinis.nama_kategori_klinis as kategori_klinis')
                ->get();

            $allDetails = $rows->groupBy('idrekam_medis');
        }

        return view('dokter.rekam-medis.show', compact('rekamMedis', 'detailTindakan', 'allRekamMedis', 'allDetails'));
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

        return view('dokter.rekam-medis.edit', compact('rekamMedis','pets','dokters','tindakanTerapis','selectedTindakan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekamMedis $rekamMedis)
    {
        // For dokter: only allow updating tindakan/detail (do not modify main rekam_medis fields)
        $data = $request->validate([
            'idkode_tindakan_terapi' => 'nullable|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        // Resolve rekam id robustly. If binding produced an empty model, try to
        // obtain an id from route parameters or request input. If we cannot
        // determine a valid rekam id, abort to avoid inserting a null FK.
        $rekamId = $rekamMedis->idrekam_medis ?? $rekamMedis->getKey() ?? null;
        if (empty($rekamId)) {
            // try common route parameter names
            $rekamId = $request->route('rekam_medi') ?? $request->route('rekam_medis') ?? $request->route('id') ?? $request->input('idrekam_medis');
        }

        if (empty($rekamId)) {
            return redirect()->back()->withInput()->with('error', 'Tidak dapat menentukan RekamMedis target. Silakan buka ulang halaman dan coba lagi.');
        }

        // Replace existing tindakan rows for this rekam_medis with the submitted one (or none)
        DB::table('detail_rekam_medis')->where('idrekam_medis', $rekamId)->delete();
        if ($request->filled('idkode_tindakan_terapi')) {
            DB::table('detail_rekam_medis')->insert([
                'idrekam_medis' => $rekamId,
                'idkode_tindakan_terapi' => $request->input('idkode_tindakan_terapi'),
                'detail' => $request->input('detail'),
            ]);
        }

        return redirect()->route('dokter.rekam-medis.show', ['rekam_medi' => $rekamId])->with('success', 'Tindakan rekam medis berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();
        return redirect()->route('dokter.rekam-medis.index')->with('success', 'Rekam Medis berhasil dihapus.');
    }
}
