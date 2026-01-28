<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('master.unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_nama' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:50|unique:tb_unit,kode_unit',
            'jenis' => 'nullable|string|max:100',
        ]);

        Unit::create($validated);

        return redirect()->route('unit.index')->with('success', 'Unit Kerja berhasil ditambahkan');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'unit_nama' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:50|unique:tb_unit,kode_unit,' . $id . ',unit_id',
            'jenis' => 'nullable|string|max:100',
        ]);

        $unit->update($validated);

        return redirect()->route('unit.index')->with('success', 'Unit Kerja berhasil diupdate');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('unit.index')->with('success', 'Unit Kerja berhasil dihapus');
    }
}
