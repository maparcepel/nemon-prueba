<?php

use App\Http\Controllers\EnergyCalculatorController;
use Illuminate\Support\Facades\Route;

Route::post('/calculate', [EnergyCalculatorController::class, 'calculate']);
Route::get('/consumptions', [EnergyCalculatorController::class, 'getConsumptions']);
Route::get('/prices', [EnergyCalculatorController::class, 'getPrices']);
