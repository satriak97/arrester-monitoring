<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArresterController;
use App\Http\Controllers\MeasurementHistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagedUserController;
use App\Http\Controllers\CabangUserController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::resource('arrester', ArresterController::class)
    ->middleware(['auth', 'admin']);

Route::get('/measurement-history', [MeasurementHistoryController::class, 'index'])
    ->middleware(['auth'])
    ->name('measurement.history');

Route::resource('users', UserController::class)
    ->middleware(['auth', 'admin']);

Route::get('/measurement-history/{arrester_id}', [\App\Http\Controllers\MeasurementHistoryController::class, 'showByArrester'])
    ->middleware(['auth'])
    ->name('measurement.history.by_arrester');

Route::resource('users', UserController::class)
    ->middleware(['auth', 'admin_master']);

Route::resource('users', UserController::class)
    ->middleware(['auth', 'admin_master']);

Route::get('/managed-users', [ManagedUserController::class, 'index'])
    ->middleware(['auth', 'admin_cabang'])
    ->name('managed-users.index');

Route::resource('cabang-users', CabangUserController::class)
    ->middleware(['auth', 'admin_cabang']);

require __DIR__.'/auth.php';