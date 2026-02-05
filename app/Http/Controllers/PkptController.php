<?php

namespace App\Http\Controllers;

use App\Models\Pkpt;
use Illuminate\Http\Request;

class PkptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Get all PKPT data
        $pkptData = Pkpt::when($tahun, function ($query) use ($tahun) {
            return $query->where('tahun', $tahun);
        })
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'asc')
            ->get();

        // Transform data to pivot format (group by tahun and bulan)
        $pkpts = $pkptData->groupBy(function ($item) {
            return $item->tahun . '-' . $item->bulan;
        })->map(function ($group) {
            $first = $group->first();
            return (object) [
                'pkpt_id' => $first->pkpt_id,
                'tahun' => $first->tahun,
                'bulan' => $first->bulan,
                'rutin' => $group->where('jenis_audit', 'Rutin')->sum('jumlah'),
                'khusus' => $group->where('jenis_audit', 'Khusus')->sum('jumlah'),
                'tematik' => $group->where('jenis_audit', 'Tematik')->sum('jumlah'),
            ];
        })->values();

        // Get available years for filter
        $availableYears = Pkpt::selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('pkpt.index', compact('pkpts', 'tahun', 'availableYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:2100',
            'rutin' => 'nullable|integer|min:0',
            'khusus' => 'nullable|integer|min:0',
            'tematik' => 'nullable|integer|min:0',
        ]);

        // Save each jenis_audit as separate row
        $data = [];
        if (!empty($validated['rutin']) && $validated['rutin'] > 0) {
            $data[] = [
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'jenis_audit' => 'Rutin',
                'jumlah' => $validated['rutin'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($validated['khusus']) && $validated['khusus'] > 0) {
            $data[] = [
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'jenis_audit' => 'Khusus',
                'jumlah' => $validated['khusus'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($validated['tematik']) && $validated['tematik'] > 0) {
            $data[] = [
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'jenis_audit' => 'Tematik',
                'jumlah' => $validated['tematik'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($data)) {
            Pkpt::insert($data);
        }

        return redirect()->route('pkpt.index')->with('success', 'Data PKPT berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get all data for this tahun-bulan combination
        $pkpt = Pkpt::findOrFail($id);
        $allData = Pkpt::where('tahun', $pkpt->tahun)
            ->where('bulan', $pkpt->bulan)
            ->get();

        return response()->json([
            'pkpt_id' => $pkpt->pkpt_id,
            'tahun' => $pkpt->tahun,
            'bulan' => $pkpt->bulan,
            'rutin' => $allData->where('jenis_audit', 'Rutin')->sum('jumlah'),
            'khusus' => $allData->where('jenis_audit', 'Khusus')->sum('jumlah'),
            'tematik' => $allData->where('jenis_audit', 'Tematik')->sum('jumlah'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bulan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:2100',
            'rutin' => 'nullable|integer|min:0',
            'khusus' => 'nullable|integer|min:0',
            'tematik' => 'nullable|integer|min:0',
        ]);

        $pkpt = Pkpt::findOrFail($id);

        // Delete all existing records for this tahun-bulan
        Pkpt::where('tahun', $pkpt->tahun)
            ->where('bulan', $pkpt->bulan)
            ->delete();

        // Insert new records
        $data = [];
        if (!empty($validated['rutin']) && $validated['rutin'] > 0) {
            $data[] = [
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'jenis_audit' => 'Rutin',
                'jumlah' => $validated['rutin'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($validated['khusus']) && $validated['khusus'] > 0) {
            $data[] = [
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'jenis_audit' => 'Khusus',
                'jumlah' => $validated['khusus'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($validated['tematik']) && $validated['tematik'] > 0) {
            $data[] = [
                'tahun' => $validated['tahun'],
                'bulan' => $validated['bulan'],
                'jenis_audit' => 'Tematik',
                'jumlah' => $validated['tematik'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($data)) {
            Pkpt::insert($data);
        }

        return redirect()->route('pkpt.index')->with('success', 'Data PKPT berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pkpt = Pkpt::findOrFail($id);

        // Delete all records for this tahun-bulan combination
        Pkpt::where('tahun', $pkpt->tahun)
            ->where('bulan', $pkpt->bulan)
            ->delete();

        return redirect()->route('pkpt.index')->with('success', 'Data PKPT berhasil dihapus');
    }
}
