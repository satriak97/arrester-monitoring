<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MeasurementController;

Route::post('/measurement', [MeasurementController::class,'store']);
Route::get('/measurement/latest', [MeasurementController::class,'latest']);