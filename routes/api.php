<?php 

use App\Http\Controllers\PrintController;
use App\Http\Controllers\PrintTableCheckerController;

Route::post('print', [PrintController::class, 'action'])->middleware('reclas');
Route::post('print/table-checker', [PrintTableCheckerController::class, 'action'])->middleware('reclas');