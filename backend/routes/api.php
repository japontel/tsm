<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TransactionController;

Route::prefix('v1')->group(function () {
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::delete('transactions/{id}', [TransactionController::class, 'destroy']);
});