<?php

namespace App\Http\Controllers;


use App\Models\Rekomendasi;
use App\Models\Pemeriksaan;
use App\Models\Temuan;
use App\Models\Rekom;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data pemeriksaan dengan jumlah rekomendasi dan data LHA, diurutkan berdasarkan ID
        $pemeriksaan = Pemeriksaan::with(['unit', 'lha'])
            ->withCount('rekomendasi')
            ->orderBy('pemeriksaan_id', 'asc')
            ->get();

        $units = Unit::all();
        $users = User::all();

        return view('rekomendasi.index', compact('pemeriksaan', 'units', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemeriksaan = Pemeriksaan::all();
        $units = Unit::all();
        $users = User::all();

        return view('rekomendasi.create', compact('pemeriksaan', 'units', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'pemeriksaan_id' => 'required|exists:tb_pemeriksaan,pemeriksaan_id',
                'temuan_id' => 'nullable|exists:tb_temuan,temuan_id',
                'rekomendasi_judul' => 'required|string|max:500',
                'rekomendasi_tgl' => 'required|date',
                'rekomendasi_tgl_deadline' => 'nullable|date',
                'rekomendasi_status' => 'nullable|string|max:100',
                'rekomen_id' => 'required|exists:tb_master_rekomendasi,rekomen_id',
                'unit_id' => 'nullable|exists:tb_unit,unit_id',
            ]);

            $data = $request->all();

            // Ambil unit default dari pemeriksaan bila unit_id tidak dikirim
            $defaultUnitId = null;
            if (!empty($data['pemeriksaan_id'])) {
                $pemeriksaan = \App\Models\Pemeriksaan::find($data['pemeriksaan_id']);
                $defaultUnitId = $pemeriksaan?->unit_id;
            }
            if (empty($data['unit_id'])) {
                $data['unit_id'] = $defaultUnitId;
            }

            // Set user_nik from authenticated user if not provided
            if (!isset($data['user_nik']) || empty($data['user_nik'])) {
                $data['user_nik'] = Auth::user()->user_nik ?? '0000000';
            }

            // Set default values
            $data['rekomendasi_aktif'] = $data['rekomendasi_aktif'] ?? 'Y';
            $data['rekomendasi_kirim'] = $data['rekomendasi_kirim'] ?? 'N';
            $data['rekomendasi_publish_kabag'] = $data['rekomendasi_publish_kabag'] ?? 'N';



            $data['rekomendasi_status'] = $data['rekomendasi_status'] ?? 'Belum di Tindak Lanjut';

            // Default untuk status tindak lanjut
            $data['rekomendasi_status_cache'] = $data['rekomendasi_status_cache'] ?? 'Belum di Tindak Lanjut';
            $data['rekomendasi_status_terbaru'] = $data['rekomendasi_status_terbaru'] ?? '';
            $data['rekomendasi_status_tanggal'] = $data['rekomendasi_status_tanggal'] ?? now();
            $data['rekomendasi_pmr_sebelumnya'] = $data['rekomendasi_pmr_sebelumnya'] ?? 0;


            // Remove empty values to avoid foreign key constraint issues
            $data = array_filter($data, function ($value, $key) {
                // Keep these fields even if empty
                $keepFields = [
                    'rekomendasi_status',
                    'rekomendasi_tgl_deadline',
                    'rekomendasi_status_cache',
                    'rekomendasi_status_terbaru',
                    'rekomendasi_status_tanggal',
                ];
                if (in_array($key, $keepFields)) {
                    return true;
                }
                return $value !== '' && $value !== null;
            }, ARRAY_FILTER_USE_BOTH);

            \Log::info('Attempting to create rekomendasi with data:', $data);

            $rekomendasi = Rekomendasi::create($data);

            \Log::info('Rekomendasi created successfully with ID:', ['rekomendasi_id' => $rekomendasi->rekomendasi_id]);

            // Redirect back to kelola page if came from there
            if ($request->has('temuan_id') && !empty($request->input('temuan_id'))) {
                return redirect()->route('rekomendasi.kelola-rekomendasi', $request->input('temuan_id'))->with('success', 'Rekomendasi berhasil ditambahkan.');
            }

            if ($request->has('pemeriksaan_id') && !empty($request->pemeriksaan_id)) {
                return redirect()->route('rekomendasi.kelola', $request->pemeriksaan_id)->with('success', 'Rekomendasi berhasil ditambahkan.');
            }

            return redirect()->route('rekomendasi.index')->with('success', 'Rekomendasi berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error when creating rekomendasi:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating rekomendasi:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $request->all()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menambahkan rekomendasi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rekomendasi = Rekomendasi::with(['pemeriksaan', 'pemeriksaan.unit', 'user', 'temuan'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($rekomendasi);
        }

        return view('rekomendasi.show', compact('rekomendasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rekomendasi = Rekomendasi::findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($rekomendasi);
        }

        $pemeriksaan = Pemeriksaan::all();
        $units = Unit::all();
        $users = User::all();

        return view('rekomendasi.edit', compact('rekomendasi', 'pemeriksaan', 'units', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rekomendasi = Rekomendasi::findOrFail($id);

            $request->validate([
                'pemeriksaan_id' => 'required|exists:tb_pemeriksaan,pemeriksaan_id',
                'temuan_id' => 'nullable|exists:tb_temuan,temuan_id',
                'rekomendasi_judul' => 'required|string|max:500',
                'rekomendasi_tgl' => 'required|date',
                'rekomendasi_tgl_deadline' => 'nullable|date',
                'rekomendasi_status' => 'nullable|string|max:100',
                'rekomen_id' => 'required|exists:tb_master_rekomendasi,rekomen_id',
                'unit_id' => 'nullable|exists:tb_unit,unit_id',
            ]);

            $data = $request->all();

            $data['rekomendasi_kirim'] = $data['rekomendasi_kirim'] ?? 'N';
            $data['rekomendasi_publish_kabag'] = $data['rekomendasi_publish_kabag'] ?? 'N';



            // Set user_nik from authenticated user if not provided
            if (!isset($data['user_nik']) || empty($data['user_nik'])) {
                $data['user_nik'] = Auth::user()->user_nik ?? '0000000';
            }

            // Remove empty values to avoid foreign key constraint issues
            $data = array_filter($data, function ($value, $key) {
                // Keep these fields even if empty
                $keepFields = ['rekomendasi_status', 'rekomendasi_tgl_deadline'];
                if (in_array($key, $keepFields)) {
                    return true;
                }
                return $value !== '' && $value !== null;
            }, ARRAY_FILTER_USE_BOTH);

            \Log::info('Attempting to update rekomendasi with data:', $data);

            $rekomendasi->update($data);

            \Log::info('Rekomendasi updated successfully with ID:', ['rekomendasi_id' => $rekomendasi->rekomendasi_id]);

            // Redirect based on context
            if (isset($data['temuan_id']) && $data['temuan_id']) {
                return redirect()->route('rekomendasi.kelola-rekomendasi', $data['temuan_id'])
                    ->with('success', 'Rekomendasi berhasil ditambahkan');
            }

            if ($request->has('pemeriksaan_id') && !empty($request->pemeriksaan_id)) {
                return redirect()->route('rekomendasi.kelola', $request->pemeriksaan_id)->with('success', 'Rekomendasi berhasil ditambahkan.');
            }

            return redirect()->route('rekomendasi.index')->with('success', 'Rekomendasi berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error when updating rekomendasi:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating rekomendasi:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $request->all()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memperbarui rekomendasi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rekomendasi = Rekomendasi::findOrFail($id);
            $pemeriksaan_id = $rekomendasi->pemeriksaan_id;
            $temuan_id = $rekomendasi->temuan_id;

            $rekomendasi->delete();

            \Log::info('Rekomendasi deleted successfully with ID:', ['rekomendasi_id' => $id]);

            // Redirect based on context
            if ($temuan_id) {
                // Perbaiki: gunakan route yang terdefinisi
                return redirect()->route('rekomendasi.kelola-rekomendasi', $temuan_id)
                    ->with('success', 'Rekomendasi berhasil dihapus.');
            }

            if ($pemeriksaan_id) {
                return redirect()->route('rekomendasi.kelola', $pemeriksaan_id)
                    ->with('success', 'Rekomendasi berhasil dihapus.');
            }

            return redirect()->route('rekomendasi.index')
                ->with('success', 'Rekomendasi berhasil dihapus.');

        } catch (\Exception $e) {
            \Log::error('Error deleting rekomendasi:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'id' => $id
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus rekomendasi: ' . $e->getMessage());
        }
    }

    /**
     * Display rekomendasi for specific pemeriksaan (kelola page)
     */
    public function kelola($pemeriksaan_id)
    {
        $pemeriksaan = Pemeriksaan::with(['unit', 'lha'])->findOrFail($pemeriksaan_id);
        $rekomendasi = Rekomendasi::with(['temuan', 'user', 'unit'])
            ->where('pemeriksaan_id', $pemeriksaan_id)
            ->orderBy('rekomendasi_id', 'asc')
            ->get();

        $units = Unit::all();
        $users = User::all();
        $masterRekomendasi = Rekom::orderBy('judul', 'asc')->get();

        return view('rekomendasi.kelola', compact('pemeriksaan', 'rekomendasi', 'units', 'users', 'masterRekomendasi'));
    }

    /**
     * Kelola rekomendasi untuk temuan tertentu
     */
    public function kelolaByTemuan(string $temuan_id)
    {
        $temuan = Temuan::with(['pemeriksaan.unit'])->findOrFail($temuan_id);
        $pemeriksaan = $temuan->pemeriksaan;
        $rekomendasi = Rekomendasi::with(['temuan', 'unit'])
            ->where('temuan_id', $temuan_id)
            ->orderBy('rekomendasi_tgl', 'desc')
            ->get();
        $units = Unit::all();
        $masterRekomendasi = Rekom::orderBy('judul', 'asc')->get();

        return view('rekomendasi.kelola', compact('temuan', 'pemeriksaan', 'rekomendasi', 'units', 'masterRekomendasi'));
    }

    public function kirimBatch(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'Tidak ada rekomendasi dipilih'], 400);
        }

        // Update hanya kolom rekomendasi_kirim secara bulk
        $updated = Rekomendasi::whereIn('rekomendasi_id', $ids)
            ->update(['rekomendasi_kirim' => 'Y']);

        // Update temuan_kirim = 'Y' untuk temuan terkait rekomendasi terpilih
        $temuanIds = Rekomendasi::whereIn('rekomendasi_id', $ids)
            ->pluck('temuan_id')
            ->filter()   // buang null/empty
            ->unique()
            ->values()
            ->all();

        $temuanUpdated = 0;
        if (!empty($temuanIds)) {
            $temuanUpdated = Temuan::whereIn('temuan_id', $temuanIds)
                ->update(['temuan_kirim' => 'Y']);
        }

        return response()->json([
            'message' => 'Berhasil mengirim rekomendasi',
            'count' => $updated,
            'temuan_count' => $temuanUpdated,
        ]);
    }

    public function publishBatch(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'Tidak ada rekomendasi dipilih'], 400);
        }

        // Update hanya kolom rekomendasi_publish_kabag secara bulk
        $updated = Rekomendasi::whereIn('rekomendasi_id', $ids)
            ->update(['rekomendasi_publish_kabag' => 'Y']);

        return response()->json([
            'message' => 'Berhasil mempublish rekomendasi',
            'count' => $updated,
        ]);
    }
}
