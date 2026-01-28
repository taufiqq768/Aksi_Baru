<?php

namespace App\Http\Controllers;

use App\Models\Coso;
use Illuminate\Http\Request;

class CosoController extends Controller
{
    public function index()
    {
        $coso = Coso::all();
        return view('master.coso.index', compact('coso'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_coso' => 'required|string|max:50|unique:tb_master_coso,kode_coso',
            'klasifikasi_coso' => 'required|string|max:255',
        ]);

        Coso::create($validated);

        return redirect()->route('coso.index')->with('success', 'Master COSO berhasil ditambahkan');
    }

    public function edit($id)
    {
        $coso = Coso::findOrFail($id);
        return response()->json($coso);
    }

    public function update(Request $request, $id)
    {
        $coso = Coso::findOrFail($id);

        $validated = $request->validate([
            'kode_coso' => 'required|string|max:50|unique:tb_master_coso,kode_coso,' . $id . ',coso_id',
            'klasifikasi_coso' => 'required|string|max:255',
        ]);

        $coso->update($validated);

        return redirect()->route('coso.index')->with('success', 'Master COSO berhasil diupdate');
    }

    public function destroy($id)
    {
        $coso = Coso::findOrFail($id);
        $coso->delete();

        return redirect()->route('coso.index')->with('success', 'Master COSO berhasil dihapus');
    }
}
