<?php

use App\Models\barang;
use App\Models\customer;
use App\Models\tjenis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\barangController;
use App\Http\Controllers\tjenisController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\transaksiController;

Route::get('/', function () {
    // dd(barang::find("tes03"));
    // return view('welcome');

    return view('transaksi', [
        "title" => "transkasi",
        "active" => "transaksi",
        "barangs" => barang::all(),
        "customers" => customer::all(),
        "tjenis" => tjenis::all(),
    ]);
});

Route::get('/get-barang/{kode_barang}', function ($kode_barang) {
    $barang = barang::where('kode_barang', $kode_barang)->first();
    return response()->json([
        'method'=>'GET',
        'status'=>200,
        'data'=>$barang
    ]);
});

// Route untuk menampilkan halaman transaksi
// Route::get('/transaksi', [transaksiController::class, 'index'])->name('transaksi.index');

// Route untuk menyimpan transaksi
// Route::post('/', [transaksiController::class, 'store'])->name('transaksi.store');
Route::post('/tambah-barang', [transaksiController::class, 'tambahBarang'])->name('transaksi.tambahBarang');
Route::post('/input-header', [transaksiController::class, 'inputHeader'])->name('transaksi.inputHeader');
Route::put('/update-faktur', [transaksiController::class, 'updateDataFaktur'])->name('transaksi.updateDataFaktur');
Route::post('/cari-data', [transaksiController::class, 'cariData'])->name('transaksi.cariData');
Route::delete('/hapus-data/{no_faktur}', [transaksiController::class, 'hapusFaktur'])->name('transaksi.hapusFaktur');
Route::put('/update-header/{no_faktur}', [TransaksiController::class, 'updateFaktur'])->name('transaksi.updateHeader');

Route::get('/printFaktur/{no_faktur}', [transaksiController::class, 'printFaktur'])->name('transaksi.printFaktur');

Route::get('/preview/{no_faktur}', [transaksiController::class, 'previewFaktur'])->name('transaksi.preview');

Route::get('/exportCsv/{no_faktur}', [TransaksiController::class, 'exportCsv'])->name('transaksi.exportCsv');


Route::delete('/hapus-barang/{id}', [transaksiController::class, 'hapusBarang'])->name('transaksi.hapusBarang');
Route::put('/update-barang/{id}', [transaksiController::class, 'updateBarang'])->name('transaksi.updateBarang');


Route::resource('barang', barangController::class);
Route::resource('customer', customerController::class);
Route::resource('tjenis', tjenisController::class);
