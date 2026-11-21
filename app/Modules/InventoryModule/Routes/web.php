<?php

use App\Modules\InventoryModule\Http\Controllers\DashboardController;
use App\Modules\InventoryModule\Http\Controllers\StockCardController;
use App\Modules\InventoryModule\Http\Controllers\StockInController;
use App\Modules\InventoryModule\Http\Controllers\StockOutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum', 'role:admin,staff'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('stock-ins', StockInController::class)
        ->parameters(['stock-ins' => 'stockIn'])
        ->only(['index', 'create', 'store', 'show']);

    Route::resource('stock-outs', StockOutController::class)
        ->parameters(['stock-outs' => 'stockOut'])
        ->only(['index', 'create', 'store', 'show']);

    Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
});
