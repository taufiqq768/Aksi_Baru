<?php

namespace App\Http\Controllers;

use App\Models\Rekom;
use Illuminate\Http\Request;

class RekomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $masterRekomendasi = Rekom::orderBy('judul', 'asc')->get();
        return view('master.rekom.index', compact('masterRekomendasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.rekom.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
            ]);

            Rekom::create([
                'judul' => $request->judul,
            ]);

            return redirect()->route('rekom.index')->with('success', 'Master Rekomendasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rekom = Rekom::findOrFail($id);
        return view('master.rekom.show', compact('rekom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rekom = Rekom::findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($rekom);
        }

        return view('master.rekom.edit', compact('rekom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rekom = Rekom::findOrFail($id);

            $request->validate([
                'judul' => 'required|string|max:255',
            ]);

            $rekom->update([
                'judul' => $request->judul,
            ]);

            return redirect()->route('rekom.index')->with('success', 'Master Rekomendasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rekom = Rekom::findOrFail($id);

            // Check if this rekom is being used by any rekomendasi
            if ($rekom->rekomendasi()->count() > 0) {
                return back()->with('error', 'Master Rekomendasi tidak dapat dihapus karena masih digunakan pada data rekomendasi.');
            }

            $rekom->delete();

            return redirect()->route('rekom.index')->with('success', 'Master Rekomendasi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
