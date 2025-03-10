<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoices\Presentation\Http\InvoiceController;
use Modules\Invoices\Presentation\Http\InvoiceProductLineController;

Route::get('/invoice/{invoiceId}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::post('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::post('/invoice/send', [InvoiceController::class, 'send'])->name('invoice.send');


Route::post('/invoice-product-line/create', [InvoiceProductLineController::class, 'create'])
    ->name('invoiceProductLine.create');
