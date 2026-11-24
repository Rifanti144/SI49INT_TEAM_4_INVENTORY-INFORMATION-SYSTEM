<?php

use App\Http\Controllers\FleetController;
use Illuminate\Support\Facades\Route;

Route::controller(FleetController::class)
    ->prefix('fleet')
    ->name('fleet.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::middleware('role:admin,staff')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{vehicle}/edit', 'edit')->name('edit');
            Route::match(['put', 'patch'], '/{vehicle}', 'update')->name('update');
            Route::delete('/{vehicle}', 'destroy')->name('destroy');
        });
    });
