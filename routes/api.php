<?php

use App\Http\Controllers\PrintTableCheckerController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

Route::post('print', [PrintController::class, 'action'])->middleware('reclas');
Route::post('print/table-checker', [PrintTableCheckerController::class, 'action'])->middleware('reclas');