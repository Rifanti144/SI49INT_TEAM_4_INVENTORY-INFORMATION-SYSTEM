<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'api.active'])->group(function () {
    require __DIR__.'/api/vendors.php';
    require __DIR__.'/api/items.php';
    require __DIR__.'/api/warehouses.php';
    require __DIR__.'/api/fleet.php';
});
