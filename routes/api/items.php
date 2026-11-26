<?php

use App\Http\Controllers\Api\ItemApiController;
use Illuminate\Support\Facades\Route;

Route::get('/items', [ItemApiController::class, 'index'])->name('api.items.index');
