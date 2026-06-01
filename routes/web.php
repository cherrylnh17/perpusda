<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisKontrakController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/countdown', function () {
    return view('welcome');
})->name('countdown');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::prefix('karyawan')->name('karyawan.')->middleware('auth')->group(function () {

    // CRUD standard (resource)

    // Export
    Route::get('export/excel',   [KaryawanController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/pdf',     [KaryawanController::class, 'exportPdf'])->name('export.pdf');

    // Import
    Route::post('import',        [KaryawanController::class, 'importExcel'])->name('import');

    // ── Route baru: download template dinamis (fix: tidak butuh file statis) ──
    Route::get('template',       [KaryawanController::class, 'downloadTemplate'])->name('template');

    // Hapus foto
    Route::delete('{karyawan}/foto', [KaryawanController::class, 'deleteFoto'])->name('foto.destroy');

    Route::resource('/', KaryawanController::class)
        ->parameters(['' => 'karyawan']);

});

Route::resource('pendidikan', PendidikanController::class)->names('pendidikan');

Route::resource('jabatan', JabatanController::class)->names('jabatan');

Route::resource('kontrak', JenisKontrakController::class)->names('kontrak');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
