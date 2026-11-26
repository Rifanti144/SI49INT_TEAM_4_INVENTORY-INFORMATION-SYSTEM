<?php

use App\Http\Controllers\Api\FleetApiController;
use Illuminate\Support\Facades\Route;

Route::get('/vehicles', [FleetApiController::class, 'index'])->name('api.vehicles.index');
