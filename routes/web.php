<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\LhaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TlController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BidangTemuanController;
use App\Http\Controllers\SebabController;
use App\Http\Controllers\CosoController;
use App\Http\Controllers\TemuController;
use App\Http\Controllers\KlasifikasiAbController;
use App\Http\Controllers\PkptController;
use Illuminate\Auth\Events\Login;

// Dashboard route
Route::get('/', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');

// Authentication routes (simple)
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// API routes for user lookup
Route::get('/api/user/{nik}', [UserController::class, 'getUserByNik']);
Route::get('/api/users/search', [UserController::class, 'searchUsers']);
Route::post('/api/users/bulk', [UserController::class, 'getUsersByNiks']);

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {

    //Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PKPT routes
    Route::get('/pkpt', [PkptController::class, 'index'])->name('pkpt.index');
    Route::post('/pkpt', [PkptController::class, 'store'])->name('pkpt.store');
    Route::get('/pkpt/{id}/edit', [PkptController::class, 'edit'])->name('pkpt.edit');
    Route::put('/pkpt/{id}', [PkptController::class, 'update'])->name('pkpt.update');
    Route::delete('/pkpt/{id}', [PkptController::class, 'destroy'])->name('pkpt.destroy');

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
    Route::post('/rekomendasi/publish-batch', [RekomendasiController::class, 'publishBatch'])->name('rekomendasi.publish-batch');

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
    Route::post('/tindak-lanjut', [TlController::class, 'store'])->name('tl.store');
    Route::put('/tindak-lanjut/{id}', [TlController::class, 'update'])->name('tl.update');
    Route::post('/tindak-lanjut/{id}/tanggapan', [TlController::class, 'tanggapan'])->name('tl.tanggapan');
    Route::post('/tindak-lanjut/{id}/publish-spi', [TlController::class, 'publishSPI'])->name('tl.publish-spi');

    // Monitoring Routes
    Route::get('/monitoring/tindak-lanjut', [\App\Http\Controllers\MonitoringController::class, 'tindakLanjut'])->name('monitoring.tindak-lanjut');

    // Master Data Routes
// Manajemen User
    Route::get('/master/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/master/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/master/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/master/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/master/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Unit Kerja
    Route::get('/master/unit', [UnitController::class, 'index'])->name('unit.index');
    Route::post('/master/unit', [UnitController::class, 'store'])->name('unit.store');
    Route::get('/master/unit/{id}/edit', [UnitController::class, 'edit'])->name('unit.edit');
    Route::put('/master/unit/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('/master/unit/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

    // Bidang Temuan
    Route::get('/master/bidang', [BidangTemuanController::class, 'index'])->name('bidang.index');
    Route::post('/master/bidang', [BidangTemuanController::class, 'store'])->name('bidang.store');
    Route::get('/master/bidang/{id}/edit', [BidangTemuanController::class, 'edit'])->name('bidang.edit');
    Route::put('/master/bidang/{id}', [BidangTemuanController::class, 'update'])->name('bidang.update');
    Route::delete('/master/bidang/{id}', [BidangTemuanController::class, 'destroy'])->name('bidang.destroy');

    // Master Penyebab
    Route::get('/master/sebab', [SebabController::class, 'index'])->name('sebab.index');
    Route::post('/master/sebab', [SebabController::class, 'store'])->name('sebab.store');
    Route::get('/master/sebab/{id}/edit', [SebabController::class, 'edit'])->name('sebab.edit');
    Route::put('/master/sebab/{id}', [SebabController::class, 'update'])->name('sebab.update');
    Route::delete('/master/sebab/{id}', [SebabController::class, 'destroy'])->name('sebab.destroy');

    // Master COSO
    Route::get('/master/coso', [CosoController::class, 'index'])->name('coso.index');
    Route::post('/master/coso', [CosoController::class, 'store'])->name('coso.store');
    Route::get('/master/coso/{id}/edit', [CosoController::class, 'edit'])->name('coso.edit');
    Route::put('/master/coso/{id}', [CosoController::class, 'update'])->name('coso.update');
    Route::delete('/master/coso/{id}', [CosoController::class, 'destroy'])->name('coso.destroy');

    // Master Temuan
    Route::get('/master/temu', [TemuController::class, 'index'])->name('temu.index');
    Route::post('/master/temu', [TemuController::class, 'store'])->name('temu.store');
    Route::get('/master/temu/{id}/edit', [TemuController::class, 'edit'])->name('temu.edit');
    Route::put('/master/temu/{id}', [TemuController::class, 'update'])->name('temu.update');
    Route::delete('/master/temu/{id}', [TemuController::class, 'destroy'])->name('temu.destroy');

    // Master Rekomendasi
    Route::get('/master/rekom', [\App\Http\Controllers\RekomController::class, 'index'])->name('rekom.index');
    Route::post('/master/rekom', [\App\Http\Controllers\RekomController::class, 'store'])->name('rekom.store');
    Route::get('/master/rekom/{id}/edit', [\App\Http\Controllers\RekomController::class, 'edit'])->name('rekom.edit');
    Route::put('/master/rekom/{id}', [\App\Http\Controllers\RekomController::class, 'update'])->name('rekom.update');
    Route::delete('/master/rekom/{id}', [\App\Http\Controllers\RekomController::class, 'destroy'])->name('rekom.destroy');

    // Master AB
    Route::get('/master/ab', [KlasifikasiAbController::class, 'index'])->name('ab.index');
    Route::post('/master/ab', [KlasifikasiAbController::class, 'store'])->name('ab.store');
    Route::get('/master/ab/{id}/edit', [KlasifikasiAbController::class, 'edit'])->name('ab.edit');
    Route::put('/master/ab/{id}', [KlasifikasiAbController::class, 'update'])->name('ab.update');
    Route::delete('/master/ab/{id}', [KlasifikasiAbController::class, 'destroy'])->name('ab.destroy');
    // Profile Password Update
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // Fallback for accidental GET request
    Route::get('/profile/password', function () {
        return back();
    });
});
