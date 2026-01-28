<?php

namespace App\Http\Controllers;

use App\Models\Temu;
use Illuminate\Http\Request;

class TemuController extends Controller
{
    public function index()
    {
        $temu = Temu::all();
        return view('master.temu.index', compact('temu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_temuan' => 'required|string|max:50|unique:tb_master_temuan,kode_temuan',
            'klasifikasi_temuan' => 'required|string|max:255',
        ]);

        Temu::create($validated);

        return redirect()->route('temu.index')->with('success', 'Master Temuan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $temu = Temu::findOrFail($id);
        return response()->json($temu);
    }

    public function update(Request $request, $id)
    {
        $temu = Temu::findOrFail($id);

        $validated = $request->validate([
            'kode_temuan' => 'required|string|max:50|unique:tb_master_temuan,kode_temuan,' . $id . ',temu_id',
            'klasifikasi_temuan' => 'required|string|max:255',
        ]);

        $temu->update($validated);

        return redirect()->route('temu.index')->with('success', 'Master Temuan berhasil diupdate');
    }

    public function destroy($id)
    {
        $temu = Temu::findOrFail($id);
        $temu->delete();

        return redirect()->route('temu.index')->with('success', 'Master Temuan berhasil dihapus');
    }
}
