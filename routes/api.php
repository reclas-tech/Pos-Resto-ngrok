<?php 

use App\Http\Controllers\PrintController;

Route::post('print', [PrintController::class, 'action']);