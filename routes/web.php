<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterItemsController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriPdfController;
use App\Http\Controllers\MasterItemExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ==========================================
// MASTER ITEMS ROUTES
// ==========================================
Route::prefix('master-items')->group(function () {
    Route::get('/', [MasterItemsController::class, 'index']);
    Route::get('/search', [MasterItemsController::class, 'search']);
    Route::get('/form/{method}/{id?}', [MasterItemsController::class, 'formView']);
    Route::post('/form/{method}/{id?}', [MasterItemsController::class, 'formSubmit']);
    Route::get('/view/{kode}', [MasterItemsController::class, 'singleView']);
    Route::get('/delete/{id}', [MasterItemsController::class, 'delete']);
    Route::get('/update-random-data', [MasterItemsController::class, 'updateRandomData']);

    // ← TAMBAH ROUTE EXPORT EXCEL
    Route::get('/export-excel', [MasterItemExportController::class, 'exportExcel']);
});


Route::prefix('kategori')->group(function () {
    Route::get('/', [KategoriController::class, 'index']);
    Route::get('/search', [KategoriController::class, 'search']);
    Route::get('/form/{method}/{id?}', [KategoriController::class, 'formView']);
    Route::post('/form/{method}/{id?}', [KategoriController::class, 'formSubmit']);
    Route::get('/view/{kode}', [KategoriController::class, 'singleView']);
    Route::get('/delete/{id}', [KategoriController::class, 'delete']);
    Route::post('/toggle-item', [KategoriController::class, 'toggleItem']);

    // Export PDF Routes
    Route::get('/export-pdf/{kode}', [KategoriPdfController::class, 'exportPdf']);
    Route::get('/export-pdf-all', [KategoriPdfController::class, 'exportPdfAll']);
    Route::get('/export-excel', [MasterItemExportController::class, 'exportExcel']);
    Route::get('/export-excel-html', [MasterItemExportController::class, 'exportExcelHtml']);
    Route::get('/export-simple', [MasterItemExportController::class, 'exportSimpleExcel']);
});