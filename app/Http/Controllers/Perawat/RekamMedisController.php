<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\TindakanTerapi;
use App\Models\TemuDokter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Provide both patient queue and full rekam medis list so the view can show either
        // We perform a two-step query:
        // 1) Get pet ids ordered by their latest rekam_medis.created_at (desc)
        // 2) Fetch Pet models and reorder them in PHP to preserve that ordering.
        $orderedPetIds = DB::table('pet as p')
            ->leftJoin('rekam_medis as rm', 'p.idpet', '=', 'rm.idpet')
            ->select('p.idpet', DB::raw('MAX(rm.created_at) as last_rekam'))
            ->groupBy('p.idpet')
            ->orderByDesc('last_rekam')
            ->orderBy('p.nama')
            ->pluck('idpet')
            ->toArray();

        $petsCollection = Pet::with('pemilik', 'rasHewan.jenisHewan')
            ->whereIn('idpet', $orderedPetIds)
            ->get()
            ->keyBy('idpet');

        // Reorder according to $orderedPetIds (pets without rekam will appear last)
        $pets = collect($orderedPetIds)->map(function($id) use ($petsCollection) {
            return $petsCollection->get($id);
        })->filter()->values();

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
            'idpet' => 'required|exists:pet,idpet',
            'dokter_pemeriksa' => 'nullable|exists:role_user,idrole_user',
        ]);

        // legacy table uses created_at as tanggal; preserve the date but add current time
        // so records created via the Perawat form don't get a 00:00 time.
        $dateOnly = $data['tanggal'];
        $currentTime = Carbon::now()->format('H:i:s');
        $data['created_at'] = Carbon::parse($dateOnly . ' ' . $currentTime)->toDateTimeString();
        unset($data['tanggal']);

        // Ensure `dokter_pemeriksa` is set because the `rekam_medis` table
        // requires this NOT NULL value. If Perawat did not select a dokter,
        // auto-assign the first active dokter (role_user) available.
        if (empty($data['dokter_pemeriksa'])) {
            $defaultDokter = RoleUser::whereHas('role', function($q){
                $q->where('nama_role', 'Dokter');
            })->where('status', 1)->first();

            if ($defaultDokter) {
                $data['dokter_pemeriksa'] = $defaultDokter->idrole_user;
            } else {
                return redirect()->back()->withInput()->with('error', 'Tidak ada dokter aktif untuk ditetapkan. Hubungi administrator.');
            }
        }

        // Prevent duplicate records when a placeholder RekamMedis already exists.
        // If a recent placeholder (empty anamnesa or status_verifikasi=0) exists for the same pet,
        // update it instead of creating a new RekamMedis. Otherwise create a single record.
        $rekam = null;
        $placeholder = RekamMedis::where('idpet', $data['idpet'])
            ->where(function($q){
                $q->whereNull('anamnesa')->orWhere('anamnesa', '')->orWhere('status_verifikasi', 0);
            })
            ->orderByDesc('created_at')
            ->first();

        if ($placeholder) {
            // update the placeholder with the submitted data
            $placeholder->update([
                'dokter_pemeriksa' => $data['dokter_pemeriksa'],
                'anamnesa' => $data['anamnesa'] ?? '',
                'temuan_klinis' => $data['temuan_klinis'] ?? '',
                'diagnosa' => $data['diagnosa'] ?? '',
                'status_verifikasi' => $data['status_verifikasi'] ?? 0,
                'created_at' => $data['created_at'] ?? $placeholder->created_at,
            ]);
            $rekam = $placeholder;
        } else {
            // Create RekamMedis using explicit fields to avoid accidental NULLs
            $rekam = RekamMedis::create([
                'idpet' => $data['idpet'],
                'dokter_pemeriksa' => $data['dokter_pemeriksa'],
                'anamnesa' => $data['anamnesa'] ?? '',
                'temuan_klinis' => $data['temuan_klinis'] ?? '',
                'diagnosa' => $data['diagnosa'] ?? '',
                'created_at' => $data['created_at'] ?? now()->toDateTimeString(),
                'status_verifikasi' => $data['status_verifikasi'] ?? 0,
            ]);
        }

        // Jika ada pendaftaran temu dokter terkait (berdasarkan pet yang sama dan status menunggu),
        // tandai bahwa perawat telah memeriksa pasien sehingga antrean masuk ke dokter.
        // NOTE: status on `temu_dokter` should not be changed here. Perawat
        // only creates/updates RekamMedis; the appointment status remains
        // 'Menunggu' until the Dokter completes the diagnosis.

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
        // Use findOrFail to ensure a proper 404 for invalid ids and be explicit
        // that $id is the primary key for rekam_medis.
        $rekamMedis = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])
            ->findOrFail($id);

        // Fallback: if pet relation is not present, try to load Pet by idpet
        if ((! $rekamMedis->relationLoaded('pet') || ! $rekamMedis->pet) && $rekamMedis->idpet) {
            $pet = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])->find($rekamMedis->idpet);
            if ($pet) {
                $rekamMedis->setRelation('pet', $pet);
            }
        }

        // Find the latest RekamMedis for the same pet (so Perawat sees the most
        // recent anamnesa/temuan_klinis/diagnosa after they saved it).
        $displayRekam = $rekamMedis;
        if ($rekamMedis->idpet) {
            $latest = RekamMedis::with(['pet.pemilik.user', 'pet.rasHewan.jenisHewan', 'roleUser.user'])
                ->where('idpet', $rekamMedis->idpet)
                ->orderByDesc('created_at')
                ->first();

            if ($latest) {
                $displayRekam = $latest;

                // Ensure pet relation loaded for the latest record as well
                if ((! $displayRekam->relationLoaded('pet') || ! $displayRekam->pet) && $displayRekam->idpet) {
                    $pet = Pet::with(['pemilik.user','rasHewan.jenisHewan'])->find($displayRekam->idpet);
                    if ($pet) {
                        $displayRekam->setRelation('pet', $pet);
                    }
                }
            }
        }

        // fetch associated tindakan (if any) for the displayed record
        $tindakan = DB::table('detail_rekam_medis')
            ->join('kode_tindakan_terapi', 'detail_rekam_medis.idkode_tindakan_terapi', '=', 'kode_tindakan_terapi.idkode_tindakan_terapi')
            ->where('detail_rekam_medis.idrekam_medis', $displayRekam->idrekam_medis)
            ->select('kode', 'deskripsi_tindakan_terapi')
            ->first();

        // Pass the latest/display RekamMedis to the view so the detail page
        // shows the most recent data for that pet (this ensures newly saved
        // anamnesa/temuan/diagnosa appear immediately on the detail page).
        return view('perawat.rekam-medis.show', ['rekamMedis' => $displayRekam, 'tindakan' => $tindakan, 'originalRekam' => $rekamMedis]);
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
    public function update(Request $request, $id)
    {
        $rekamMedis = RekamMedis::findOrFail($id);

        $data = $request->validate([
            'anamnesa' => 'required|string',
            'diagnosa' => 'required|string',
            'temuan_klinis' => 'required|string',
        ]);

        // Only update the specified fields; do not change tanggal/idpet/dokter here.
        $rekamMedis->update([
            'anamnesa' => $data['anamnesa'],
            'temuan_klinis' => $data['temuan_klinis'],
            'diagnosa' => $data['diagnosa'],
        ]);

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
