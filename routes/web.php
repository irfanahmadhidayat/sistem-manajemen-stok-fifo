<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StokController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\BarangKeluarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/adminlte', function () {
    return view('layouts-page.app');
});

Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
Route::resource('/barang', BarangController::class);

Route::get('/jenis-barang/get-data', [JenisController::class, 'getDataJenisBarang']);
Route::resource('/jenis-barang', JenisController::class);

Route::get('/satuan-barang/get-data', [SatuanController::class, 'getDataSatuanBarang']);
Route::resource('/satuan-barang', SatuanController::class);

Route::get('/api/barang-masuk/', [BarangMasukController::class, 'getAutoCompleteData']);
Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getDataBarangMasuk']);
Route::get('/api/satuan/', [BarangMasukController::class, 'getSatuan']);
Route::resource('/barang-masuk', BarangMasukController::class);

Route::get('/api/barang-keluar/', [BarangKeluarController::class, 'getAutoCompleteData']);
Route::get('/barang-keluar/get-data', [BarangKeluarController::class, 'getDataBarangKeluar']);
Route::get('/api/satuan/', [BarangKeluarController::class, 'getSatuan']);
Route::resource('/barang-keluar', BarangKeluarController::class);

Route::get('/stok-penjualan', [StokController::class, 'index'])->name('stok.penjualan');

Route::get('/laporan-stok/get-data', [LaporanStokController::class, 'getData']);
Route::get('/laporan-stok/print-stok', [LaporanStokController::class, 'printStok']);
Route::get('/api/satuan/', [LaporanStokController::class, 'getSatuan']);
Route::get('/laporan-stok', [LaporanStokController::class, 'index'])->name('laporan-stok.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
