<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArresterController;
use App\Http\Controllers\MeasurementHistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagedUserController;
use App\Http\Controllers\CabangUserController;
use App\Http\Controllers\GiPenyulangController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Data Arrester: semua role login boleh lihat
    Route::get('/arrester', [ArresterController::class, 'index'])
        ->name('arrester.index');

    // CRUD Arrester: hanya admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/arrester/create', [ArresterController::class, 'create'])
            ->name('arrester.create');

        Route::post('/arrester', [ArresterController::class, 'store'])
            ->name('arrester.store');

        Route::get('/arrester/{id}/edit', [ArresterController::class, 'edit'])
            ->name('arrester.edit');

        Route::put('/arrester/{id}', [ArresterController::class, 'update'])
            ->name('arrester.update');

        Route::delete('/arrester/{id}', [ArresterController::class, 'destroy'])
            ->name('arrester.destroy');
    });

    Route::get('/measurement-history', [MeasurementHistoryController::class, 'index'])
        ->name('measurement.history');

    Route::get('/measurement-history/{arrester_id}', [MeasurementHistoryController::class, 'showByArrester'])
        ->name('measurement.history.by_arrester');

    // Kelola User: hanya admin master
    Route::resource('users', UserController::class)
        ->middleware(['admin_master']);

    // Kelola user oleh admin cabang
    Route::get('/managed-users', [ManagedUserController::class, 'index'])
        ->middleware(['admin_cabang'])
        ->name('managed-users.index');

    Route::resource('cabang-users', CabangUserController::class)
        ->middleware(['admin_cabang']);

    Route::get('/gi-penyulang', [GiPenyulangController::class, 'index'])
    ->name('gi-penyulang.index');

    Route::post('/gi', [GiPenyulangController::class, 'storeGi'])
        ->middleware('admin')
        ->name('gi.store');

    Route::post('/penyulang', [GiPenyulangController::class, 'storePenyulang'])
        ->middleware('admin')
        ->name('penyulang.store');

    Route::get('/gi-penyulang', [GiPenyulangController::class, 'index'])
        ->name('gi-penyulang.index');

    Route::post('/gi', [GiPenyulangController::class, 'storeGi'])
        ->middleware('admin')
        ->name('gi.store');

    Route::put('/gi/{id}', [GiPenyulangController::class, 'updateGi'])
        ->middleware('admin')
        ->name('gi.update');

    Route::delete('/gi/{id}', [GiPenyulangController::class, 'destroyGi'])
        ->middleware('admin')
        ->name('gi.destroy');

    Route::post('/penyulang', [GiPenyulangController::class, 'storePenyulang'])
        ->middleware('admin')
        ->name('penyulang.store');

    Route::put('/penyulang/{id}', [GiPenyulangController::class, 'updatePenyulang'])
        ->middleware('admin')
        ->name('penyulang.update');

    Route::delete('/penyulang/{id}', [GiPenyulangController::class, 'destroyPenyulang'])
        ->middleware('admin')
        ->name('penyulang.destroy');
});

require __DIR__.'/auth.php';