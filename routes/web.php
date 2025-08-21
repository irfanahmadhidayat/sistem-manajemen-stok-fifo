<?php

use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StokController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LaporanBarangMasukController;
use App\Http\Controllers\LaporanBarangKeluarController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');

    // Barang Master
    Route::middleware(['auth', AdminOnly::class])->group(function () {
        Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
        Route::resource('/barang', BarangController::class);

        Route::get('/jenis-barang/get-data', [JenisController::class, 'getDataJenisBarang']);
        Route::resource('/jenis-barang', JenisController::class);

        Route::get('/satuan-barang/get-data', [SatuanController::class, 'getDataSatuanBarang']);
        Route::resource('/satuan-barang', SatuanController::class);
    });

    // Barang Masuk
    Route::get('/api/barang-masuk/', [BarangMasukController::class, 'getAutoCompleteData']);
    Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getDataBarangMasuk']);
    Route::get('/api/satuan/', [BarangMasukController::class, 'getSatuan']);
    Route::resource('/barang-masuk', BarangMasukController::class);

    // Barang Keluar
    Route::get('/api/barang-keluar/', [BarangKeluarController::class, 'getAutoCompleteData']);
    Route::get('/barang-keluar/get-data', [BarangKeluarController::class, 'getDataBarangKeluar']);
    Route::get('/api/satuan/', [BarangKeluarController::class, 'getSatuan']);
    Route::resource('/barang-keluar', BarangKeluarController::class);

    // Laporan
    Route::get('/laporan-stok/get-data', [LaporanStokController::class, 'getData']);
    Route::get('/laporan-stok/print-stok', [LaporanStokController::class, 'printStok']);
    Route::get('/api/satuan/', [LaporanStokController::class, 'getSatuan']);
    Route::get('/laporan-stok', [LaporanStokController::class, 'index'])->name('laporan-stok.index');

    Route::get('/stok-penjualan', [StokController::class, 'index'])->name('stok.penjualan');
    Route::get('/stok/penjualan/print', [StokController::class, 'printPDF'])->name('stok.penjualan.print');

    Route::get('/laporan/barang-masuk', [LaporanBarangMasukController::class, 'index'])->name('laporan.barang-masuk');
    Route::get('/laporan/barang-masuk/print', [LaporanBarangMasukController::class, 'print'])->name('laporan.barang-masuk.print');

    Route::get('/laporan/barang-keluar', [LaporanBarangKeluarController::class, 'index'])->name('laporan.barang-keluar');
    Route::get('/laporan/barang-keluar/print', [LaporanBarangKeluarController::class, 'print'])->name('laporan.barang-keluar.print');
});

require __DIR__ . '/auth.php';
