<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\LhaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TlController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (simple)
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// API routes for user lookup
Route::get('/api/user/{nik}', [UserController::class, 'getUserByNik']);
Route::get('/api/users/search', [UserController::class, 'searchUsers']);
Route::post('/api/users/bulk', [UserController::class, 'getUsersByNiks']);

// Pemeriksaan routes
Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
Route::get('/pemeriksaan/{id}/edit', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit');
Route::put('/pemeriksaan/{id}', [PemeriksaanController::class, 'update'])->name('pemeriksaan.update');
Route::delete('/pemeriksaan/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');

// Tambahkan route ini ke file routes/web.php yang sudah ada

Route::resource('temuan', TemuanController::class);
Route::get('temuan/{id}/kelola', [TemuanController::class, 'kelola'])->name('temuan.kelola');

// Rekomendasi routes
Route::resource('rekomendasi', RekomendasiController::class);
Route::get('rekomendasi/{id}/kelola', [RekomendasiController::class, 'kelola'])->name('rekomendasi.kelola');
// Route utama yang sudah ada
Route::get('/rekomendasi/kelola-rekomendasi/{temuan_id}', [RekomendasiController::class, 'kelolaByTemuan'])
    ->name('rekomendasi.kelola-rekomendasi');

// Alias agar referensi lama tetap berfungsi (opsional)
Route::get('/rekomendasi/kelola-temuan/{temuan_id}', [RekomendasiController::class, 'kelolaByTemuan'])
    ->name('rekomendasi.kelola-temuan');
Route::post('/rekomendasi/kirim-batch', [RekomendasiController::class, 'kirimBatch'])->name('rekomendasi.kirim-batch');

// LHA routes
Route::post('/lha', [LhaController::class, 'store'])->name('lha.store');
Route::put('/lha/{id}', [LhaController::class, 'update'])->name('lha.update');
Route::delete('/lha/{id}', [LhaController::class, 'destroy'])->name('lha.destroy');

// Tindak Lanjut per Rekomendasi
Route::get('/tindak-lanjut', function () {
    return redirect()->route('rekomendasi.index');
})->name('tl.index');
Route::get('/tindak-lanjut/rekomendasi/{id}', [TlController::class, 'byRekomendasi'])->name('tl.byRekomendasi');
Route::get('/tindak-lanjut/{id}', [TlController::class, 'show'])->name('tl.show');
Route::get('/lampiran/tl/{uploadId}', [TlController::class, 'downloadLampiran'])->name('tl.lampiran');
Route::post('/tindak-lanjut', [TlController::class, 'store'])->name('tl.store')  // Hapus middleware('auth')
;
Route::put('/tindak-lanjut/{id}', [TlController::class, 'update'])->name('tl.update');
Route::post('/tindak-lanjut/{id}/tanggapan', [TlController::class, 'tanggapan'])->name('tl.tanggapan');
Route::post('/tindak-lanjut/{id}/publish-verif', [TlController::class, 'publishVerif'])->name('tl.publish-verif');
