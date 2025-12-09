<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the pemeriksaan.
     */
    public function index()
    {
        $pemeriksaan = Pemeriksaan::with('unit')->get();
        $users = User::all();
        $units = Unit::all();
        $anggotas = User::where('user_level', 'spi')
        ->where('user_aktif', 'Y')
        ->get();

        return view('pemeriksaan.index', compact('pemeriksaan', 'users', 'units', 'anggotas'));
    }

    /**
     * Store a newly created pemeriksaan in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log data yang diterima
        \Log::info('Store request data:', $request->all());

        $request->validate([
            'pemeriksaan_jenis' => 'required|string',
            'pemeriksaan_pkpt' => 'required|string',
            'pemeriksaan_judul' => 'required|string',
            'pemeriksaan_objek' => 'required|string',
            'pemeriksaan_nomor_st' => 'required|string',
            'pemeriksaan_tanggal_st' => 'required|date',
            'pemeriksaan_ketua' => 'required|exists:tb_users,user_nik',
            'pemeriksaan_pengawas' => 'required|exists:tb_users,user_nik',
            'pemeriksaan_petugas' => 'nullable|array',
            'pemeriksaan_petugas.*' => 'exists:tb_users,user_nik',
            'pemeriksaan_tgl' => 'required|string',
            'pemeriksaan_doc' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $data = $request->except(['pemeriksaan_doc']);

        // Debug: Log incoming request data
        \Log::info('Store request data:', $request->all());

        $data = $request->except(['pemeriksaan_doc', 'file_surat_tugas', '_token']);

        // Handle pemeriksaan_petugas array conversion
        if (isset($data['pemeriksaan_petugas']) && is_array($data['pemeriksaan_petugas'])) {
            $data['pemeriksaan_petugas'] = implode('/', $data['pemeriksaan_petugas']);
        }

        // Handle file upload
        if ($request->hasFile('pemeriksaan_doc')) {
            $file = $request->file('pemeriksaan_doc');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');
            $data['pemeriksaan_doc'] = $path;
        } elseif ($request->hasFile('file_surat_tugas')) {
            // Handle alternative file field name
            $file = $request->file('file_surat_tugas');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');
            $data['pemeriksaan_doc'] = $path;
        }

        // Parse date range with correct format
        if ($request->pemeriksaan_tgl) {
            $dateRange = explode(' - ', $request->pemeriksaan_tgl);
            if (count($dateRange) == 2) {
                try {
                    $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dateRange[0]))->format('Y-m-d');
                    $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dateRange[1]))->format('Y-m-d');
                    $data['pemeriksaan_tgl_mulai'] = $startDate;
                    $data['pemeriksaan_tgl_akhir'] = $endDate;
                } catch (\Exception $e) {
                    \Log::error('Date parsing error:', ['error' => $e->getMessage(), 'date_range' => $request->pemeriksaan_tgl]);
                    // Fallback to d/m/Y format
                    try {
                        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dateRange[0]))->format('Y-m-d');
                        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dateRange[1]))->format('Y-m-d');
                        $data['pemeriksaan_tgl_mulai'] = $startDate;
                        $data['pemeriksaan_tgl_akhir'] = $endDate;
                    } catch (\Exception $e2) {
                        \Log::error('Date parsing fallback error:', ['error' => $e2->getMessage()]);
                    }
                }
            }
        }

        $data['pemeriksaan_aktif'] = 1; // Use integer instead of string

        // Debug: Check authentication status
        \Log::info('Auth check:', [
            'is_authenticated' => auth()->check(),
            'user' => auth()->user(),
            'user_nik' => auth()->check() ? auth()->user()->user_nik : 'not authenticated'
        ]);

        // Set user_nik with fallback
        if (auth()->check() && auth()->user()->user_nik) {
            $data['user_nik'] = auth()->user()->user_nik;
        } else {
            // Fallback: use a default user_nik or remove from data if nullable
            $data['user_nik'] = '10000001'; // Default admin user or use actual logged user
        }

        // Set kebun_id to match unit_id (as per user requirement)
        if (isset($data['unit_id'])) {
            $data['kebun_id'] = $data['unit_id'];
        }

        // Debug: Log data yang akan disimpan
        \Log::info('Data to be saved:', $data);

        try {
            // Include user_nik in the initial create since it's required
            $pemeriksaan = Pemeriksaan::create($data);

            \Log::info('Pemeriksaan created successfully:', $pemeriksaan->toArray());

            return redirect()->route('pemeriksaan.index')->with('success', 'Data pemeriksaan berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Error creating pemeriksaan:', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified pemeriksaan.
     */
    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with(['unit', 'pengawas', 'ketua'])->findOrFail($id);

        // Debug log untuk melihat data yang dikirim
        \Log::info('Pemeriksaan data for detail:', $pemeriksaan->toArray());

        return response()->json($pemeriksaan);
    }

    /**
     * Update the specified pemeriksaan in storage.
     */
    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $request->validate([
            'pemeriksaan_jenis' => 'required',
            'pemeriksaan_judul' => 'required|string|max:255',
            'pemeriksaan_objek' => 'required',
            'pemeriksaan_nomor_st' => 'required|string|max:255',
            'pemeriksaan_tanggal_st' => 'required|date',
            'pemeriksaan_ketua' => 'required|exists:tb_users,user_nik',
            'pemeriksaan_pengawas' => 'required|exists:tb_users,user_nik',
            'pemeriksaan_petugas' => 'nullable|array',
            'pemeriksaan_petugas.*' => 'exists:tb_users,user_nik',
            'pemeriksaan_tgl' => 'required|string',
            'pemeriksaan_doc' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,odt|max:25600', // 25MB
        ]);

        $data = $request->except(['pemeriksaan_doc']);

        // Handle pemeriksaan_petugas array conversion to slash-separated format
        if (isset($data['pemeriksaan_petugas']) && is_array($data['pemeriksaan_petugas'])) {
            $data['pemeriksaan_petugas'] = implode('/', $data['pemeriksaan_petugas']);
        }

        // Handle file upload
        if ($request->hasFile('pemeriksaan_doc')) {
            // Delete old file if exists
            if ($pemeriksaan->pemeriksaan_doc) {
                Storage::disk('public')->delete($pemeriksaan->pemeriksaan_doc);
            }

            $file = $request->file('pemeriksaan_doc');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');
            $data['pemeriksaan_doc'] = $path;
        }

        // Parse date range with correct format
        if ($request->pemeriksaan_tgl) {
            $dateRange = explode(' - ', $request->pemeriksaan_tgl);
            if (count($dateRange) == 2) {
                try {
                    $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dateRange[0]))->format('Y-m-d');
                    $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dateRange[1]))->format('Y-m-d');
                    $data['pemeriksaan_tgl_mulai'] = $startDate;
                    $data['pemeriksaan_tgl_akhir'] = $endDate;
                } catch (\Exception $e) {
                    \Log::error('Date parsing error in update:', ['error' => $e->getMessage(), 'date_range' => $request->pemeriksaan_tgl]);
                    // Fallback to d/m/Y format
                    try {
                        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dateRange[0]))->format('Y-m-d');
                        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dateRange[1]))->format('Y-m-d');
                        $data['pemeriksaan_tgl_mulai'] = $startDate;
                        $data['pemeriksaan_tgl_akhir'] = $endDate;
                    } catch (\Exception $e2) {
                        \Log::error('Date parsing fallback error in update:', ['error' => $e2->getMessage()]);
                        // Final fallback using strtotime for backward compatibility
                        try {
                            $data['pemeriksaan_tgl_mulai'] = date('Y-m-d', strtotime(trim($dateRange[0])));
                            $data['pemeriksaan_tgl_akhir'] = date('Y-m-d', strtotime(trim($dateRange[1])));
                        } catch (\Exception $e3) {
                            \Log::error('Final date parsing fallback error in update:', ['error' => $e3->getMessage()]);
                        }
                    }
                }
            }
        }

        // Set kebun_id to match unit_id if unit_id is provided
        if (isset($data['unit_id'])) {
            $data['kebun_id'] = $data['unit_id'];
        }

        $pemeriksaan->update($data);

        return redirect()->route('pemeriksaan.index')->with('success', 'Data pemeriksaan berhasil diupdate!');
    }

    /**
     * Remove the specified pemeriksaan from storage.
     */
    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        // Delete file if exists
        if ($pemeriksaan->pemeriksaan_doc) {
            Storage::disk('public')->delete($pemeriksaan->pemeriksaan_doc);
        }

        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan.index')->with('success', 'Data pemeriksaan berhasil dihapus!');
    }
}
