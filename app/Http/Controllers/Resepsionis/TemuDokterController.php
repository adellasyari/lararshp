<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemuDokter;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\RekamMedis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TemuDokterController extends Controller
{
    public function index()
    {
        // Antrian hari ini: whereDate on waktu_daftar equals today(), order by waktu_daftar ascending
        // Eager-load pet with its pemilik (nested) and dokter->user. Do NOT eager-load `pemilik` on TemuDokter
        // because the `temu_dokter` table does not have an `idpemilik` column in this schema.
        // Only show appointments whose waktu_daftar is today.
        // Use Carbon::today() to ensure date comparison is explicit and compatible with datetime columns.
        // Filter using the database's date values directly (show times exactly as stored in DB)
        $antrianHariIni = TemuDokter::with(['dokter.user', 'pet.pemilik.user', 'pet.rasHewan'])
            ->whereDate('waktu_daftar', Carbon::today())
            ->orderBy('waktu_daftar', 'asc')
            ->orderBy('no_urut', 'asc')
            ->get();

        Log::info('[TemuDokter@index] antrianHariIni fetched (db-date)', [
            'count' => $antrianHariIni->count(),
            'date' => Carbon::today()->toDateString(),
            'ids' => $antrianHariIni->pluck('idreservasi_dokter')->all(),
        ]);

        // Riwayat lengkap (latest first)
        $riwayat = TemuDokter::with(['dokter.user', 'pet.pemilik'])
            ->latest('waktu_daftar')
            ->get();

        // Also provide lists of pets and dokters so the resepsionis dashboard can show/select them immediately
        $pets = Pet::with('pemilik.user')->get();
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();

        // Prefer a fresh_id passed as a query parameter (more reliable across redirects),
        // otherwise fall back to session flash.
        $freshId = request()->query('fresh_id') ?? session('fresh_id') ?? null;
        if ($freshId) {
            Log::info('[TemuDokter@index] fresh_id present', ['fresh_id' => $freshId]);
            $fresh = TemuDokter::with(['dokter.user','pet.pemilik.user'])->find($freshId);
            if ($fresh) {
                Log::info('[TemuDokter@index] fresh found', ['id' => $fresh->getKey(), 'waktu_daftar' => $fresh->waktu_daftar]);
                // if it's not already present in the today's collection, prepend it
                if (!$antrianHariIni->contains(function($v) use ($fresh) { return $v->getKey() == $fresh->getKey(); })) {
                    $antrianHariIni->prepend($fresh);
                    Log::info('[TemuDokter@index] fresh prepended');
                } else {
                    Log::info('[TemuDokter@index] fresh already present');
                }
            } else {
                Log::warning('[TemuDokter@index] fresh_id provided but no record found', ['fresh_id' => $freshId]);
            }
            // remove from session so it doesn't persist on refresh
            session()->forget('fresh_id');
        }

        return view('resepsionis.temu-dokter.index', compact('antrianHariIni', 'riwayat', 'pets', 'dokters'));
    }

    public function create()
    {
        // daftar dokter (role_user entries yang berperan sebagai Dokter)
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();

        $pets = Pet::with('pemilik.user')->get();

        return view('resepsionis.temu-dokter.create', compact('dokters','pets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idrole_user' => ['required','integer','exists:role_user,idrole_user'],
            'idpet' => ['required','integer','exists:pet,idpet'],
        ]);

        // Determine timezone to use for 'local' times. Default to Jakarta if app timezone unset.
        $tz = config('app.timezone') ?: 'Asia/Jakarta';

        // Fill waktu_daftar with current server/app time (local to $tz). Store as UTC in DB
        // so stored timestamps are consistent regardless of server timezone.
        $waktu_daftar_dt = Carbon::now($tz);
        $waktu_daftar_db = $waktu_daftar_dt->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');
        // Keep the local datetime object for computing the per-day sequence
        $waktu_daftar = $waktu_daftar_dt->format('Y-m-d H:i:s');

        // compute next no_urut for the same date (per-day sequence)
        $dateOnly = $waktu_daftar_dt->toDateString();
        $maxForDate = TemuDokter::whereDate('waktu_daftar', $dateOnly)->max('no_urut');
        $nextNo = ($maxForDate ? (int)$maxForDate : 0) + 1;

        // Prevent duplicate open registration: if the same pet already has a waiting
        // appointment on the same date, do not create another one. This avoids
        // accidental double registrations from the UI.
        $existing = TemuDokter::where('idpet', $data['idpet'])
            ->whereDate('waktu_daftar', $dateOnly)
            ->where(function($q){
                $q->whereNull('status')->orWhere('status', '0')->orWhere('status', '');
            })
            ->exists();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Pasien ini sudah terdaftar pada tanggal tersebut. Periksa daftar antrian.');
        }

        $created = null;
        DB::transaction(function() use ($data, $waktu_daftar, $waktu_daftar_db, $nextNo, &$created) {
            // ensure MySQL session timezone is UTC so TIMESTAMP values are stored consistently
            try {
                DB::statement("SET time_zone = '+00:00'");
            } catch (\Exception $e) {
                // Log but continue; some environments may not allow changing session timezone
                Log::warning('[TemuDokter@store] could not set session time_zone', ['err' => $e->getMessage()]);
            }
            // Note: the `temu_dokter` table in the current DB schema does NOT have an `idpemilik` column,
            // so we only insert `idpet` and derive owner from the pet relationship when needed.
            $created = TemuDokter::create([
                'idpet' => $data['idpet'],
                // store UTC timestamp in DB
                'waktu_daftar' => $waktu_daftar_db,
                'idrole_user' => $data['idrole_user'],
                'no_urut' => $nextNo,
                'status' => 0,
            ]);
        });

        // Log created info for debugging
        if ($created) {
            // reload the record to see what DB actually stored (converted by MySQL)
            $fresh = TemuDokter::find($created->getKey());
            Log::info('[TemuDokter@store] created', ['id' => $created->getKey(), 'waktu_daftar_local' => $waktu_daftar, 'waktu_daftar_db' => $fresh ? $fresh->waktu_daftar : null]);
        }

        // create a RekamMedis record so Perawat can pick it up for triage/diagnosa
        if ($created) {
            try {
                // RekamMedis.dokter_pemeriksa is NOT NULL in the schema; prefer to
                // set it to the selected dokter (idrole_user) from the form. If not
                // available, fallback to first active dokter.
                $dokterForRekam = $data['idrole_user'] ?? null;
                if (empty($dokterForRekam)) {
                    $defaultDokter = RoleUser::whereHas('role', function($q){
                        $q->where('nama_role', 'Dokter');
                    })->where('status', 1)->first();
                    $dokterForRekam = $defaultDokter ? $defaultDokter->idrole_user : null;
                }

                RekamMedis::create([
                    // use the same logical timestamp for the record
                    'created_at' => $waktu_daftar,
                    'idpet' => $data['idpet'],
                    // store empty strings instead of NULL so fields are not NULL
                    'anamnesa' => '',
                    'temuan_klinis' => '',
                    'diagnosa' => '',
                    'dokter_pemeriksa' => $dokterForRekam,
                    'status_verifikasi' => 0,
                ]);
            } catch (\Exception $e) {
                // do not break the flow if RekamMedis creation fails; just log
                Log::warning('[TemuDokter@store] failed to create RekamMedis', ['err' => $e->getMessage()]);
            }

            $url = route('resepsionis.temu-dokter.index') . '?fresh_id=' . $created->getKey();
            return redirect()->to($url)->with('success', 'Pendaftaran temu dokter berhasil disimpan.');
        }

        return redirect()->route('resepsionis.temu-dokter.index')->with('success', 'Pendaftaran temu dokter berhasil disimpan.');
    }

    public function edit($id)
    {
        $temu = TemuDokter::findOrFail($id);
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();
        $pets = Pet::with('pemilik.user')->get();

        return view('resepsionis.temu-dokter.edit', compact('temu','dokters','pets'));
    }

    public function update(Request $request, $id)
    {
        $temu = TemuDokter::findOrFail($id);

        $data = $request->validate([
            'idrole_user' => ['required','integer','exists:role_user,idrole_user'],
            'idpet' => ['required','integer','exists:pet,idpet'],
            'tanggal' => ['required','date'],
            'waktu' => ['required','date_format:H:i'],
            'status' => ['nullable','integer'],
        ]);
        // combine date + time into waktu_daftar
        $waktu_daftar = $data['tanggal'] . ' ' . $data['waktu'];

        DB::transaction(function() use ($temu, $data, $waktu_daftar) {
            // Do not update `idpemilik` because current DB schema does not include that column.
            $temu->update([
                'idrole_user' => $data['idrole_user'],
                'idpet' => $data['idpet'],
                'waktu_daftar' => $waktu_daftar,
                'status' => $data['status'] ?? $temu->status,
            ]);
        });

        return redirect()->route('resepsionis.temu-dokter.index')->with('success', 'Data temu dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $temu = TemuDokter::findOrFail($id);
        $temu->delete();

        return redirect()->route('resepsionis.temu-dokter.index')->with('success', 'Pendaftaran temu dokter berhasil dihapus.');
    }
}
