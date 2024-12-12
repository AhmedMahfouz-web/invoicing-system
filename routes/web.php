<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

// Dashboard as home page
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/invoice-view', function () { return view('invoices.invoiceodf'); });

Route::get('invoices/bulk', function () {
    return view('invoices.bulk_download');
})->name('invoices.bulk.download');
Route::post('/invoices/bulk-download', [InvoiceController::class, 'bulkDownload'])->name('invoices.bulk-download');

// Resource Routes
Route::resource('clients', ClientController::class);
Route::resource('products', ProductController::class);
Route::resource('invoices', InvoiceController::class);

// Invoice Additional Routes
Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
Route::get('invoices-export', [InvoiceController::class, 'exportExcel'])->name('invoices.export');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

require __DIR__ . '/auth.php';
