<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
