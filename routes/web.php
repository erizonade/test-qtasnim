<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/barang/getAllBarang', [BarangController::class, 'getAllBarang']);
    Route::get('/transaksi/searchTransaksi', [HomeController::class, 'searchTransaksi'])->name('searchTransaksi');
    Route::get('/transaksi/filterTransaksi', [HomeController::class, 'filterTransaksi'])->name('filterTransaksi');
    Route::get('/transaksi/invoice', [TransaksiController::class, 'numberInvoice']);

    /* Resource */
    Route::resource('jenis-barang', JenisBarangController::class)->names('jenis-barang');
    Route::resource('barang', BarangController::class)->names('barang');
    Route::resource('transaksi', TransaksiController::class)->names('transaksi')->only(['index', 'store', 'show']);

});

require __DIR__.'/auth.php';
