<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisBarangController;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('/barang/getAllBarang', [BarangController::class, 'getAllBarang']);
Route::get('/transaksi/searchTransaksi', [HomeController::class, 'searchTransaksi']);

/* Resource */
Route::resource('jenis-barang', JenisBarangController::class)->names('jenis-barang');
Route::resource('barang', BarangController::class)->names('barang');
Route::resource('transaksi', TransaksiController::class)->names('transaksi')->only(['index', 'store', 'show']);
