<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiAb;
use Illuminate\Http\Request;

class KlasifikasiAbController extends Controller
{
    public function index()
    {
        $klasifikasiAb = KlasifikasiAb::all();
        return view('master.ab.index', compact('klasifikasiAb'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ab' => 'required|string|max:50|unique:tb_master_ab,kode_ab',
            'judul_ab' => 'required|string|max:255',
        ]);

        KlasifikasiAb::create($validated);

        return redirect()->route('ab.index')->with('success', 'Master AB berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ab = KlasifikasiAb::findOrFail($id);
        return response()->json($ab);
    }

    public function update(Request $request, $id)
    {
        $ab = KlasifikasiAb::findOrFail($id);

        $validated = $request->validate([
            'kode_ab' => 'required|string|max:50|unique:tb_master_ab,kode_ab,' . $id . ',id_ab',
            'judul_ab' => 'required|string|max:255',
        ]);

        $ab->update($validated);

        return redirect()->route('ab.index')->with('success', 'Master AB berhasil diupdate');
    }

    public function destroy($id)
    {
        $ab = KlasifikasiAb::findOrFail($id);
        $ab->delete();

        return redirect()->route('ab.index')->with('success', 'Master AB berhasil dihapus');
    }
}
