<?php

namespace App\Http\Controllers;

use App\Models\Lha;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LhaController extends Controller
{
    /**
     * Store a newly created LHA in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pemeriksaan' => 'required|exists:tb_pemeriksaan,pemeriksaan_id',
            'no_lha' => 'required|string|max:255|unique:tb_lha,no_lha',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'status' => 'required|integer|in:1,2',
            'file_lha' => 'nullable|file|mimes:pdf|max:10240' // 10MB max
        ], [
            'id_pemeriksaan.required' => 'ID Pemeriksaan harus diisi.',
            'id_pemeriksaan.exists' => 'Pemeriksaan tidak ditemukan.',
            'no_lha.required' => 'Nomor LHA harus diisi.',
            'no_lha.unique' => 'Nomor LHA sudah digunakan.',
            'tahun.required' => 'Tahun harus diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun maksimal ' . (date('Y') + 5) . '.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'file_lha.mimes' => 'File harus berformat PDF.',
            'file_lha.max' => 'Ukuran file maksimal 10MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam validasi data.');
        }

        try {
            // Check if LHA already exists for this pemeriksaan
            $existingLha = Lha::where('id_pemeriksaan', $request->id_pemeriksaan)->first();
            if ($existingLha) {
                return redirect()->back()
                    ->with('error', 'LHA untuk pemeriksaan ini sudah ada.');
            }

            $fileName = null;

            // Handle file upload
            if ($request->hasFile('file_lha')) {
                $file = $request->file('file_lha');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Store file in storage/app/public/lha
                $file->storeAs('public/lha', $fileName);
            }

            // Create LHA record
            Lha::create([
                'id_pemeriksaan' => $request->id_pemeriksaan,
                'no_lha' => $request->no_lha,
                'file_lha' => $fileName,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);

            return redirect()->route('temuan.index')
                ->with('success', 'Dokumen LHA berhasil disimpan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified LHA in storage.
     */
    public function update(Request $request, $id)
    {
        $lha = Lha::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'no_lha' => 'required|string|max:255|unique:tb_lha,no_lha,' . $id . ',id_lha',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'status' => 'required|integer|in:1,2',
            'file_lha' => 'nullable|file|mimes:pdf|max:10240' // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam validasi data.');
        }

        try {
            $fileName = $lha->file_lha;

            // Handle file upload
            if ($request->hasFile('file_lha')) {
                // Delete old file if exists
                if ($fileName && Storage::exists('public/lha/' . $fileName)) {
                    Storage::delete('public/lha/' . $fileName);
                }

                $file = $request->file('file_lha');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Store new file
                $file->storeAs('public/lha', $fileName);
            }

            // Update LHA record
            $lha->update([
                'no_lha' => $request->no_lha,
                'file_lha' => $fileName,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);

            return redirect()->route('temuan.index')
                ->with('success', 'Dokumen LHA berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified LHA from storage.
     */
    public function destroy($id)
    {
        try {
            $lha = Lha::findOrFail($id);

            // Delete file if exists
            if ($lha->file_lha && Storage::exists('public/lha/' . $lha->file_lha)) {
                Storage::delete('public/lha/' . $lha->file_lha);
            }

            $lha->delete();

            return redirect()->route('temuan.index')
                ->with('success', 'Dokumen LHA berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
