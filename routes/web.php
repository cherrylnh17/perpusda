<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisKontrakController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KenaikanBerkalaController;
use App\Http\Controllers\KenaikanGolonganController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Countdown
    Route::view('/countdown', 'welcome')
        ->name('countdown');

    // Profile
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });

    // Master Data
    Route::resources([
        'pendidikan' => PendidikanController::class,
        'jabatan'    => JabatanController::class,
        'kontrak'    => JenisKontrakController::class,
        'golongan'   => GolonganController::class,
    ]);

    // API: Ambil pendidikan berdasarkan jenjang (untuk cascading dropdown)
    Route::get('/api/pendidikan', [PendidikanController::class, 'getByJenjang'])
        ->name('api.pendidikan.by-jenjang');

    // Karyawan
    Route::prefix('karyawan')
        ->name('karyawan.')
        ->controller(KaryawanController::class)
        ->group(function () {

            Route::get('export/excel', 'exportExcel')
                ->name('export.excel');

            Route::get('export/pdf', 'exportPdf')
                ->name('export.pdf');

            Route::post('import', 'importExcel')
                ->name('import');

            Route::get('template', 'downloadTemplate')
                ->name('template');

            Route::delete('{karyawan}/foto', 'deleteFoto')
                ->name('foto.destroy');

            Route::get('{karyawan}/export-pdf', 'exportPdfSingle')
                ->name('export.pdf.single');
        });

    Route::resource('karyawan', KaryawanController::class);

    // ── Kenaikan (Landing Page) ─────────────────────────────────────────────
    Route::prefix('kenaikan')->name('kenaikan.')->group(function () {
        Route::get('/', function () {
            return view('kenaikan.index');
        })->name('index');
    });

    // ── Kenaikan Berkala ────────────────────────────────────────────────────
    Route::prefix('kenaikan-berkala')->name('kenaikan-berkala.')->group(function () {
        Route::get('/', [KenaikanBerkalaController::class, 'index'])->name('index');
        Route::post('/{karyawan}/approve', [KenaikanBerkalaController::class, 'approve'])->name('approve');
        Route::post('/{karyawan}/reject',  [KenaikanBerkalaController::class, 'reject'])->name('reject');
    });

    // ── Kenaikan Golongan ───────────────────────────────────────────────────
    Route::prefix('kenaikan-golongan')->name('kenaikan-golongan.')->group(function () {
        Route::get('/', [KenaikanGolonganController::class, 'index'])->name('index');
        Route::post('/{karyawan}/approve', [KenaikanGolonganController::class, 'approve'])->name('approve');
        Route::post('/{karyawan}/reject',  [KenaikanGolonganController::class, 'reject'])->name('reject');
    });
});

require __DIR__.'/auth.php';
