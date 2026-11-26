<?php

use App\Http\Controllers\Api\VendorApiController;
use Illuminate\Support\Facades\Route;

Route::get('/vendors', [VendorApiController::class, 'index'])->name('api.vendors.index');
