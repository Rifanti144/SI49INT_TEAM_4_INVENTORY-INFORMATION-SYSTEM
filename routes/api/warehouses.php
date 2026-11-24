<?php

use App\Http\Controllers\Api\WarehouseApiController;
use Illuminate\Support\Facades\Route;

Route::get('/warehouses', [WarehouseApiController::class, 'index'])->name('api.warehouses.index');
