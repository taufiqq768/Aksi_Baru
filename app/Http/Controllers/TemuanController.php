<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use App\Models\Pemeriksaan;
use App\Models\Unit;
use App\Models\User;
use App\Models\BidangTemuan;
use App\Models\Temu;
use App\Models\Sebab;
use App\Models\Coso;
use App\Models\KlasifikasiAb;
use App\Models\Lha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data pemeriksaan dengan jumlah temuan dan data LHA, diurutkan berdasarkan ID
        $pemeriksaan = Pemeriksaan::with(['unit', 'lha'])
            ->withCount('temuan')
            ->orderBy('pemeriksaan_id', 'asc')
            // ->having('temuan_count', '>', 0) // Hanya tampilkan pemeriksaan yang memiliki temuan
            ->get();

        $units = Unit::all();
        $users = User::all();

        return view('temuan.index', compact('pemeriksaan', 'units', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemeriksaan = Pemeriksaan::all();
        $units = Unit::all();
        $users = User::all();

        return view('temuan.create', compact('pemeriksaan', 'units', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'pemeriksaan_id' => 'required|exists:tb_pemeriksaan,pemeriksaan_id',
                'temuan_judul' => 'required|string|max:500',
                // 'temuan_obyek' => 'required|string|max:500',
                // 'temuan_tgl' => 'required|date',
                'nominal' => 'nullable|integer|min:0',
                'penyebab' => 'nullable|string|max:1000',
                'temuan_kriteria' => 'nullable|string|max:1000',
                'bidangtemuan_id' => 'nullable|exists:tb_bidangtemuan,bidangtemuan_id',
                'temu_id' => 'nullable|exists:tb_master_temuan,temu_id',
                'sebab_id' => 'nullable|exists:tb_master_penyebab,sebab_id',
                'coso_id' => 'nullable|exists:tb_master_coso,coso_id',
                'id_klasifikasi_ab' => 'nullable|exists:tb_master_ab,id_ab',
            ]);

            $data = $request->all();

            // Set user_nik from authenticated user if not provided
            if (!isset($data['user_nik']) || empty($data['user_nik'])) {
                $data['user_nik'] = Auth::user()->user_nik ?? '0000000';
            }

            // Remove empty values to avoid foreign key constraint issues
            $data = array_filter($data, function($value) {
                return $value !== '' && $value !== null;
            });

            // Ensure required fields are present
            $data['pemeriksaan_id'] = $request->pemeriksaan_id;
            $data['temuan_judul'] = $request->temuan_judul;
            $data['temuan_obyek'] = $request->temuan_obyek;
            $data['temuan_tgl'] = $request->temuan_tgl;

            // Set default value for temuan_pmr_sebelumnya if not provided
            if (!isset($data['temuan_pmr_sebelumnya']) || empty($data['temuan_pmr_sebelumnya'])) {
                $data['temuan_pmr_sebelumnya'] = 0; // or null if the field allows null
            }

            // Set default value for temuan_kriteria if not provided
            if (!isset($data['temuan_kriteria']) || empty($data['temuan_kriteria'])) {
                $data['temuan_kriteria'] = ''; // empty string as default
            }

            \Log::info('Attempting to create temuan with data:', $data);

            $temuan = Temuan::create($data);

            \Log::info('Temuan created successfully with ID:', ['temuan_id' => $temuan->temuan_id]);

            // Redirect back to kelola page if came from there
            if ($request->has('pemeriksaan_id') && !empty($request->pemeriksaan_id)) {
                return redirect()->route('temuan.kelola', $request->pemeriksaan_id)->with('success', 'Temuan berhasil ditambahkan.');
            }

            return redirect()->route('temuan.index')->with('success', 'Temuan berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error when creating temuan:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating temuan:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $request->all()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menambahkan temuan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $temuan = Temuan::with(['pemeriksaan', 'pemeriksaan.unit', 'user'])->findOrFail($id);
    
        // Pastikan selalu kirim JSON untuk request AJAX/JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($temuan);
        }
    
        return view('temuan.show', compact('temuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $temuan = Temuan::findOrFail($id);

        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json($temuan);
        }

        $pemeriksaan = Pemeriksaan::all();
        $units = Unit::all();
        $users = User::all();

        return view('temuan.edit', compact('temuan', 'pemeriksaan', 'units', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'pemeriksaan_id' => 'nullable|exists:tb_pemeriksaan,pemeriksaan_id',
                'temuan_judul' => 'required|string|max:500',
                // 'temuan_obyek' => 'nullable|string|max:500',
                // 'temuan_tgl' => 'nullable|date',
                'nominal' => 'nullable|integer|min:0',
                'penyebab' => 'nullable|string|max:1000',
                'temuan_kriteria' => 'nullable|string|max:1000',
                'bidangtemuan_id' => 'nullable|exists:tb_bidangtemuan,bidangtemuan_id',
                'temu_id' => 'nullable|exists:tb_master_temuan,temu_id',
                'sebab_id' => 'nullable|exists:tb_master_penyebab,sebab_id',
                'coso_id' => 'nullable|exists:tb_master_coso,coso_id',
                'id_klasifikasi_ab' => 'nullable|exists:tb_master_ab,id_ab',
            ]);

            $temuan = Temuan::findOrFail($id);
            $data = $request->all();

            // Set user_nik from authenticated user if not provided
            if (!isset($data['user_nik']) || empty($data['user_nik'])) {
                $data['user_nik'] = Auth::user()->user_nik ?? '0000000';
            }

            // Set default value for temuan_pmr_sebelumnya if not provided
            if (!isset($data['temuan_pmr_sebelumnya']) || empty($data['temuan_pmr_sebelumnya'])) {
                $data['temuan_pmr_sebelumnya'] = 0;
            }

            // Set default value for temuan_kriteria if not provided
            if (!isset($data['temuan_kriteria']) || empty($data['temuan_kriteria'])) {
                $data['temuan_kriteria'] = '';
            }

            // Remove empty values to avoid foreign key constraint issues
            $data = array_filter($data, function($value, $key) {
                // Keep these fields even if empty
                $keepFields = ['temuan_kriteria', 'penyebab', 'temuan_obyek', 'nominal', 'temuan_pmr_sebelumnya'];
                if (in_array($key, $keepFields)) {
                    return true;
                }
                return $value !== '' && $value !== null;
            }, ARRAY_FILTER_USE_BOTH);

            \Log::info('Attempting to update temuan with data:', $data);

            $temuan->update($data);

            \Log::info('Temuan updated successfully with ID:', ['temuan_id' => $temuan->temuan_id]);

            // Redirect back to kelola page if came from there
            if ($temuan->pemeriksaan_id) {
                return redirect()->route('temuan.kelola', $temuan->pemeriksaan_id)->with('success', 'Temuan berhasil diperbarui.');
            }

            return redirect()->route('temuan.index')->with('success', 'Temuan berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error when updating temuan:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating temuan:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $request->all()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memperbarui temuan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $temuan = Temuan::findOrFail($id);
        $temuan->delete();

        return redirect()->route('temuan.index')->with('success', 'Temuan berhasil dihapus.');
    }

    /**
     * Kelola temuan - untuk menampilkan rincian daftar temuan berdasarkan pemeriksaan
     */
    public function kelola(string $pemeriksaan_id)
    {
        $pemeriksaan = Pemeriksaan::with(['unit', 'temuan'])->findOrFail($pemeriksaan_id);
        $temuan = $pemeriksaan->temuan;

        // Load master data for dropdowns
        $bidangTemuan = BidangTemuan::all();
        $masterTemuan = Temu::all();
        $masterSebab = Sebab::all();
        $masterCoso = Coso::all();
        $masterAb = KlasifikasiAb::all();

        return view('temuan.kelola', compact('pemeriksaan', 'temuan', 'bidangTemuan', 'masterTemuan', 'masterSebab', 'masterCoso', 'masterAb'));
    }
}
