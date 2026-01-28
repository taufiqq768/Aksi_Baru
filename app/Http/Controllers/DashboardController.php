<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Lha;
use App\Models\Temuan;
use App\Models\Rekomendasi;
use App\Models\Tl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Card jumlah pemeriksaan berdasarkan jenis
        $totalPemeriksaan = Pemeriksaan::count();
        $pemeriksaanRutin = Pemeriksaan::where('pemeriksaan_jenis', 'Rutin')->count();
        $pemeriksaanKhusus = Pemeriksaan::where('pemeriksaan_jenis', 'Khusus')->count();
        $pemeriksaanTematik = Pemeriksaan::where('pemeriksaan_jenis', 'Tematik')->count();

        // 2. Pie chart berdasarkan status TL
        $tlStatusData = Tl::select('tl_status', DB::raw('count(*) as total'))
            ->groupBy('tl_status')
            ->get();

        // Prepare data for pie chart
        $tlStatusLabels = $tlStatusData->pluck('tl_status')->toArray();
        $tlStatusCounts = $tlStatusData->pluck('total')->toArray();

        // 3. Chart perbandingan LHA, Temuan, Rekomendasi
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Bulan berjalan (current month)
        $lhaBulanIni = Lha::whereHas('pemeriksaan', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('pemeriksaan_tgl_mulai', $currentMonth)
                ->whereYear('pemeriksaan_tgl_mulai', $currentYear);
        })
            ->count();


        $temuanBulanIni = Temuan::whereHas('pemeriksaan', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('pemeriksaan_tgl_mulai', $currentMonth)
                ->whereYear('pemeriksaan_tgl_mulai', $currentYear);
        })
            ->count();

        $rekomendasiBulanIni = Rekomendasi::whereHas('pemeriksaan', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('pemeriksaan_tgl_mulai', $currentMonth)
                ->whereYear('pemeriksaan_tgl_mulai', $currentYear);
        })
            ->count();

        // Sampai dengan bulan berjalan (year to date)
        $lhaYTD = Lha::whereHas('pemeriksaan', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('pemeriksaan_tgl_mulai', '<=', $currentMonth)
                ->whereYear('pemeriksaan_tgl_mulai', $currentYear);
        })
            ->count();

        $temuanYTD = Temuan::whereHas('pemeriksaan', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('pemeriksaan_tgl_mulai', '<=', $currentMonth)
                ->whereYear('pemeriksaan_tgl_mulai', $currentYear);
        })
            ->count();

        $rekomendasiYTD = Rekomendasi::whereHas('pemeriksaan', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('pemeriksaan_tgl_mulai', $currentMonth)
                ->whereYear('pemeriksaan_tgl_mulai', $currentYear);
        })
            ->count();

        return view('dashboard.index', compact(
            'totalPemeriksaan',
            'pemeriksaanRutin',
            'pemeriksaanKhusus',
            'pemeriksaanTematik',
            'tlStatusLabels',
            'tlStatusCounts',
            'lhaBulanIni',
            'temuanBulanIni',
            'rekomendasiBulanIni',
            'lhaYTD',
            'temuanYTD',
            'rekomendasiYTD'
        ));
    }
}
