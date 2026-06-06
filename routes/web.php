<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisKontrakController;
use App\Http\Controllers\GolonganController;

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
        });

    Route::resource('karyawan', KaryawanController::class);
});

require __DIR__.'/auth.php';
