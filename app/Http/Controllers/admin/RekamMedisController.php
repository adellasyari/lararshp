<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekamMediss = RekamMedis::with(['pet', 'roleUser'])->get();
        return view('admin.rekam-medis.index', compact('rekamMediss'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rekam-medis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'obat' => 'nullable|string',
            'idpet' => 'required|exists:pets,idpet',
            'idrole_user' => 'required|exists:role_users,idrole_user',
        ]);

        RekamMedis::create($request->all());

        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil ditambahkan.');
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
        return view('admin.rekam-medis.edit', compact('rekamMedis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'obat' => 'nullable|string',
            'idpet' => 'required|exists:pets,idpet',
            'idrole_user' => 'required|exists:role_users,idrole_user',
        ]);

        $rekamMedis->update($request->all());

        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();

        return redirect()->route('admin.rekam-medis.index')->with('success', 'Rekam Medis berhasil dihapus.');
    }
}
