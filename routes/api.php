<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MeasurementController;

Route::post('/measurement', [MeasurementController::class, 'store']);

Route::middleware('auth:sanctum')->get('/measurement/latest', [MeasurementController::class, 'latest']);