<?php

namespace App\Http\Controllers;

use App\Models\BidangTemuan;
use Illuminate\Http\Request;

class BidangTemuanController extends Controller
{
    public function index()
    {
        $bidangTemuan = BidangTemuan::all();
        return view('master.bidang.index', compact('bidangTemuan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bidangtemuan_nama' => 'required|string|max:255',
        ]);

        BidangTemuan::create($validated);

        return redirect()->route('bidang.index')->with('success', 'Bidang Temuan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bidang = BidangTemuan::findOrFail($id);
        return response()->json($bidang);
    }

    public function update(Request $request, $id)
    {
        $bidang = BidangTemuan::findOrFail($id);

        $validated = $request->validate([
            'bidangtemuan_nama' => 'required|string|max:255',
        ]);

        $bidang->update($validated);

        return redirect()->route('bidang.index')->with('success', 'Bidang Temuan berhasil diupdate');
    }

    public function destroy($id)
    {
        $bidang = BidangTemuan::findOrFail($id);
        $bidang->delete();

        return redirect()->route('bidang.index')->with('success', 'Bidang Temuan berhasil dihapus');
    }
}
