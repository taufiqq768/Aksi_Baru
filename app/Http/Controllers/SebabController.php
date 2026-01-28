<?php

namespace App\Http\Controllers;

use App\Models\Sebab;
use Illuminate\Http\Request;

class SebabController extends Controller
{
    public function index()
    {
        $sebab = Sebab::all();
        return view('master.sebab.index', compact('sebab'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sebab_kode' => 'required|string|max:50|unique:tb_master_penyebab,sebab_kode',
            'klasifikasi_sebab' => 'required|string|max:255',
        ]);

        Sebab::create($validated);

        return redirect()->route('sebab.index')->with('success', 'Master Penyebab berhasil ditambahkan');
    }

    public function edit($id)
    {
        $sebab = Sebab::findOrFail($id);
        return response()->json($sebab);
    }

    public function update(Request $request, $id)
    {
        $sebab = Sebab::findOrFail($id);

        $validated = $request->validate([
            'sebab_kode' => 'required|string|max:50|unique:tb_master_penyebab,sebab_kode,' . $id . ',sebab_id',
            'klasifikasi_sebab' => 'required|string|max:255',
        ]);

        $sebab->update($validated);

        return redirect()->route('sebab.index')->with('success', 'Master Penyebab berhasil diupdate');
    }

    public function destroy($id)
    {
        $sebab = Sebab::findOrFail($id);
        $sebab->delete();

        return redirect()->route('sebab.index')->with('success', 'Master Penyebab berhasil dihapus');
    }
}
