<?php

namespace App\Http\Controllers;

use App\Models\Tl;
use App\Models\Rekomendasi;
use App\Models\UploadTl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TlController extends Controller
{
    public function byRekomendasi(string $id)
    {
        $rekomendasi = Rekomendasi::with(['pemeriksaan.unit', 'temuan'])->findOrFail($id);

        $tindakLanjut = Tl::where('rekomendasi_id', $id)
            ->orderBy('tl_tgl', 'desc')
            ->get();

        // PERBAIKAN: ambil lampiran berdasarkan semua tl_id milik rekomendasi ini
        $uploadTls = UploadTl::whereIn('tl_id', $tindakLanjut->pluck('tl_id'))->get();

        return view('tindaklanjut.kelola', compact('rekomendasi', 'tindakLanjut', 'uploadTls'));
    }

    public function publishVerif(string $id)
    {
        $tl = Tl::findOrFail($id);

        if ($tl->tl_publish_verif === 'Y') {
            return response()->json(['message' => 'Sudah dikirim'], 200);
        }

        $tl->tl_publish_verif = 'Y';
        $tl->save();

        return response()->json(['message' => 'Berhasil dikirim'], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rekomendasi_id' => 'required|exists:tb_rekomendasi,rekomendasi_id',
            'tl_deskripsi' => 'required|string',
            'tl_link' => 'nullable|string',
        ]);

        $rekomendasi = Rekomendasi::findOrFail($request->rekomendasi_id);

        // Pastikan user_opr tidak NULL: login user -> rekomendasi->user_nik -> default
        $operatorNik = Auth::user()->user_nik ?? $rekomendasi->user_nik ?? '0000000';
        $data = [
            'pemeriksaan_id' => $rekomendasi->pemeriksaan_id,
            'temuan_id' => $rekomendasi->temuan_id,
            'rekomendasi_id' => $rekomendasi->rekomendasi_id,
            'tl_deskripsi' => $request->tl_deskripsi,
            'tl_tgl' => now(),
            'tl_tanggapan' => '',
            'tl_tanggapan_publish_kabag' => 'N',
            'tl_tanggapan_kirim' => 'N',
            'tl_catatan' => '',
            'tl_catatan_publish_vrf' => 'N',
            'tl_catatan_publish_spi' => 'N',
            'tl_tanggapan_tgl' => '1900-01-01',
            'tl_catatan_tgl' => '1900-01-01',
            'tl_status' => 'Belum di Tindak Lanjut',
            'tl_status_cache' => '',
            'tl_status_tgl' => now(),
            'tl_status_publish_kabag' => 'N',
            'tl_status_kirim' => 'N',
            'tl_publish_verif' => 'N',
            'tl_publish_spi' => 'N',
            'tl_publish_kabag' => 'N',
            'tl_status_from_vrf' => 'N',
            'tl_status_from_spi' => 'N',
            'tl_pmr_sebelumnya' => 0,
            'tl_open_spi' => '0',
            'tl_open_opr' => '0',
            'user_opr' => $operatorNik,
            'user_vrf' => '',
            'tl_link' => trim($request->input('tl_link', '')),
        ];

        // dump data to console (browser) before saving
        // dump($data);

        $tl = Tl::create($data);

                // Tambah fungsi upload file (minimal, tanpa mengubah alur lainnya)
        if ($request->hasFile('tl_lampiran')) {
            if (!Storage::disk('public')->exists('tl')) {
                Storage::disk('public')->makeDirectory('tl');
            }

            $file = $request->file('tl_lampiran');
            $original = $file->getClientOriginalName();
            $safeName = Str::random(8) . '_' . preg_replace('/[^\w\-.]+/u', '_', $original);

            // Simpan file ke storage/app/public/tl (disk public)
            Storage::disk('public')->putFileAs('tl', $file, $safeName);

            UploadTl::create([
                'tl_id' => $tl->tl_id,
                'uploadtl_nama' => $safeName,
                'uploadtl_tgl' => now()->toDateString(),
                'token' => (string) Str::uuid(),
            ]);
        }

        return redirect()
            ->route('tl.byRekomendasi', $rekomendasi->rekomendasi_id)
            ->with('success', 'Tindak lanjut berhasil disimpan.');

        return redirect()
            ->route('tl.byRekomendasi', $rekomendasi->rekomendasi_id)
            ->with('success', 'Tindak lanjut berhasil disimpan.');
    }

    public function tanggapan(Request $request, string $id)
    {
        $request->validate([
            'tl_tanggapan' => 'required|string',
            'tl_status' => 'required|string',

        ]);

        $tl = Tl::findOrFail($id);

        $tl->tl_tanggapan = $request->tl_tanggapan;
        $tl->tl_catatan = $request->tl_catatan;
        $tl->tl_tanggapan_tgl = now();
        $tl->tl_catatan_tgl = now();
        $tl->tl_status = $request->tl_status;
        $tl->tl_status_cache = $request->tl_status;
        $tl->tl_status_tgl = now();
        $tl->save();

        return redirect()
            ->route('tl.byRekomendasi', $tl->rekomendasi_id)
            ->with('success', 'Tanggapan berhasil disimpan.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tl_deskripsi' => 'required|string',
            'tl_link' => 'nullable|string',
            'tl_lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
        ]);

        $tl = Tl::findOrFail($id);

        $tl->tl_deskripsi = $request->tl_deskripsi;
        // Pastikan default ke '' jika tidak diisi
        $tl->tl_link = trim($request->input('tl_link', ''));
        $tl->save();

        if ($request->hasFile('tl_lampiran')) {
            // Pastikan folder 'tl' ada di disk 'public'
            if (!Storage::disk('public')->exists('tl')) {
                Storage::disk('public')->makeDirectory('tl');
            }

            $file = $request->file('tl_lampiran');
            $original = $file->getClientOriginalName();
            $safeName = Str::random(8) . '_' . preg_replace('/[^\w\-.]+/u', '_', $original);

            // Simpan file ke storage/app/public/tl (disk public)
            Storage::disk('public')->putFileAs('tl', $file, $safeName);

            UploadTl::create([
                'tl_id' => $tl->tl_id,
                'uploadtl_nama' => $safeName,
                'uploadtl_tgl' => now()->toDateString(),
                'token' => (string) Str::uuid(),
            ]);
        }

        return redirect()
            ->route('tl.byRekomendasi', $tl->rekomendasi_id)
            ->with('success', 'Tindak lanjut berhasil diperbarui.');
    }

    public function show(string $id)
    {
        $tl = Tl::findOrFail($id);

        $uploads = UploadTl::where('tl_id', $tl->tl_id)
            ->orderByDesc('uploadtl_id')
            ->get(['uploadtl_id', 'uploadtl_nama', 'uploadtl_tgl']);

        return response()->json([
            'tl_id' => $tl->tl_id,
            'tl_deskripsi' => $tl->tl_deskripsi,
            'tl_link' => $tl->tl_link,
            'uploads' => $uploads,
        ]);
    }

    public function downloadLampiran(string $uploadId)
    {
        $upload = UploadTl::findOrFail($uploadId);
        $filename = $upload->uploadtl_nama;

        // Cek di disk 'public' (lokasi benar saat ini)
        $publicRel = 'tl/' . $filename;
        if (Storage::disk('public')->exists($publicRel)) {
            return Storage::disk('public')->download($publicRel, $filename);
        }

        // Fallback: lokasi lama akibat default disk 'local' (private/public/tl)
        $legacyAbs = storage_path('app/private/public/tl/' . $filename);
        if (is_file($legacyAbs)) {
            return response()->download($legacyAbs, $filename);
        }

        abort(404, 'Lampiran tidak ditemukan pada lokasi publik maupun legacy.');
    }
}
