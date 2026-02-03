<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Temuan;
use App\Models\Rekomendasi;
use App\Models\Tl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    /**
     * Display monitoring tindak lanjut page
     */
    public function tindakLanjut(Request $request)
    {
        // Get all units with their related data
        $units = Unit::all();

        $monitoringData = [];

        foreach ($units as $unit) {
            // Count temuan for this unit
            $jumlahTemuan = Temuan::whereHas('pemeriksaan', function ($query) use ($unit) {
                $query->where('unit_id', $unit->unit_id);
            })->count();

            // Count rekomendasi for this unit
            $jumlahRekomendasi = Rekomendasi::where('unit_id', $unit->unit_id)->count();

            // Count tindak lanjut for this unit
            $jumlahTindakLanjut = Tl::whereHas('rekomendasi', function ($query) use ($unit) {
                $query->where('unit_id', $unit->unit_id);
            })->count();

            // Count status tindak lanjut
            $statusBelumDitindaklanjuti = Rekomendasi::where('unit_id', $unit->unit_id)
                ->where('rekomendasi_status', 'Belum di Tindak Lanjut')
                ->count();

            $statusBelumSesuai = Rekomendasi::where('unit_id', $unit->unit_id)
                ->where('rekomendasi_status', 'Belum Sesuai')
                ->count();

            $statusSesuai = Rekomendasi::where('unit_id', $unit->unit_id)
                ->where('rekomendasi_status', 'Sesuai')
                ->count();

            $monitoringData[] = [
                'unit_id' => $unit->unit_id,
                'unit_nama' => $unit->unit_nama,
                'jumlah_temuan' => $jumlahTemuan,
                'jumlah_rekomendasi' => $jumlahRekomendasi,
                'jumlah_tindak_lanjut' => $jumlahTindakLanjut,
                'status_belum_ditindaklanjuti' => $statusBelumDitindaklanjuti,
                'status_belum_sesuai' => $statusBelumSesuai,
                'status_sesuai' => $statusSesuai,
            ];
        }

        // Sort by unit name
        usort($monitoringData, function ($a, $b) {
            return strcmp($a['unit_nama'], $b['unit_nama']);
        });

        return view('monitoring.tindak-lanjut', compact('monitoringData'));
    }
}
