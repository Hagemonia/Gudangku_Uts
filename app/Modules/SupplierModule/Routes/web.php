<?php

use App\Modules\SupplierModule\Http\Controllers\PaymentTermController;
use App\Modules\SupplierModule\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum', 'role:admin,staff'])->group(function () {
    Route::resource('suppliers', SupplierController::class)->except('show');
    Route::resource('payment-terms', PaymentTermController::class)
        ->parameters(['payment-terms' => 'paymentTerm'])
        ->except(['create', 'show']);
});
