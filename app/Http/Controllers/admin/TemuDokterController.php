<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemuDokter;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TemuDokterController extends Controller
{
    public function index()
    {
        $temuDokters = TemuDokter::with(['dokter.user', 'pet'])
            ->orderBy('waktu_daftar', 'desc')
            ->get();

        return view('admin.temu-dokter.index', compact('temuDokters'));
    }

    public function create()
    {
        // daftar dokter (role_user entries yang berperan sebagai Dokter)
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();

        $pets = Pet::with('pemilik.user')->get();

        return view('admin.temu-dokter.create', compact('dokters','pets'));
    }

    public function store(Request $request)
    {
        // Match resepsionis behavior: use server time for waktu_daftar and compute per-day sequence.
        $data = $request->validate([
            'idrole_user' => ['required','integer','exists:role_user,idrole_user'],
            'idpet' => ['required','integer','exists:pet,idpet'],
        ]);

        // Determine timezone to use for 'local' times. Default to Jakarta if app timezone unset.
        $tz = config('app.timezone') ?: 'Asia/Jakarta';

        // Fill waktu_daftar with current server/app time (local to $tz). Store as UTC in DB
        // so stored timestamps are consistent regardless of server timezone.
        $waktu_daftar_dt = \Carbon\Carbon::now($tz);
        $waktu_daftar_db = $waktu_daftar_dt->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');
        // Keep the local datetime string for computing the per-day sequence
        $waktu_daftar = $waktu_daftar_dt->format('Y-m-d H:i:s');

        // compute next no_urut for the same date (per-day sequence)
        $dateOnly = $waktu_daftar_dt->toDateString();
        $maxForDate = TemuDokter::whereDate('waktu_daftar', $dateOnly)->max('no_urut');
        $nextNo = ($maxForDate ? (int)$maxForDate : 0) + 1;

        // Prevent duplicate open registration: if the same pet already has a waiting
        // appointment on the same date, do not create another one.
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
            try {
                DB::statement("SET time_zone = '+00:00'");
            } catch (\Exception $e) {
                // ignore timezone setting failure
            }

            $created = TemuDokter::create([
                'idpet' => $data['idpet'],
                'waktu_daftar' => $waktu_daftar_db,
                'idrole_user' => $data['idrole_user'],
                'no_urut' => $nextNo,
                'status' => 0,
            ]);
        });

        if ($created) {
            return redirect()->route('admin.temu-dokter.index')->with('success', 'Pendaftaran temu dokter berhasil disimpan.');
        }

        return redirect()->route('admin.temu-dokter.index')->with('error', 'Gagal menyimpan pendaftaran temu dokter.');
    }

    public function edit($id)
    {
        $temu = TemuDokter::findOrFail($id);
        $dokters = RoleUser::with('user')->whereHas('role', function($q){
            $q->where('nama_role', 'Dokter');
        })->where('status', 1)->get();
        $pets = Pet::with('pemilik.user')->get();

        return view('admin.temu-dokter.edit', compact('temu','dokters','pets'));
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
            $temu->update([
                'idrole_user' => $data['idrole_user'],
                'idpet' => $data['idpet'],
                'waktu_daftar' => $waktu_daftar,
                'status' => $data['status'] ?? $temu->status,
            ]);
        });

        return redirect()->route('admin.temu-dokter.index')->with('success', 'Data temu dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $temu = TemuDokter::findOrFail($id);
        $temu->delete();

        return redirect()->route('admin.temu-dokter.index')->with('success', 'Pendaftaran temu dokter berhasil dihapus.');
    }
}
