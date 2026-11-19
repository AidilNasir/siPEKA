<?php

use App\Http\Controllers\AnalisisKesenjanganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RencanaPengembanganController;
use App\Http\Controllers\StandarKompetensiController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Application Routes (Accessible after Login)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

    // 1. Data Pegawai BPVP Kendari
    Route::resource('pegawai', PegawaiController::class);

    // 2. Pemantauan & Analisis Kesenjangan (Monev Gap)
    Route::resource('analisis', AnalisisKesenjanganController::class);
    Route::get('/pemantauan-kesenjangan', [AnalisisKesenjanganController::class, 'index'])->name('gap.monitoring');

    // 3. Standar Kompetensi Jabatan (SKJ)
    Route::get('/standar-kompetensi', [StandarKompetensiController::class, 'index'])->name('kompetensi.index');
    Route::post('/standar-kompetensi/update', [StandarKompetensiController::class, 'update'])->name('kompetensi.update');

    // 4. Rencana Pengembangan & Hak 20 JP
    Route::get('/rencana-pengembangan', [RencanaPengembanganController::class, 'index'])->name('pengembangan.index');
    Route::patch('/rencana-pengembangan/{analisis}/jp', [RencanaPengembanganController::class, 'updateJp'])->name('pengembangan.updateJp');
    Route::get('/rencana-diklat', [RencanaPengembanganController::class, 'index'])->name('training.plans');

    // 5. Laporan & Ekspor Data
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel');
    Route::get('/laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.exportCsv');
    Route::get('/laporan/export-pegawai-csv', [LaporanController::class, 'exportPegawaiCsv'])->name('laporan.exportPegawaiCsv');
    Route::get('/reports', [LaporanController::class, 'index'])->name('reports.index');

    // Additional aliases for sidebar compatibility
    Route::get('/unit-kerja', [PegawaiController::class, 'index'])->name('units.index');
    Route::get('/pengaturan', [StandarKompetensiController::class, 'index'])->name('settings.index');
});
