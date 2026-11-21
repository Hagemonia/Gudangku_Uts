<?php

use App\Modules\ProductModule\Http\Controllers\CategoryController;
use App\Modules\ProductModule\Http\Controllers\ProductController;
use App\Modules\ProductModule\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum', 'role:admin,staff'])->group(function () {
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except(['create', 'show']);
    Route::resource('units', UnitController::class)->except(['create', 'show']);
});
