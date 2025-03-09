<?php

use App\Http\Controllers\Api\Retail\FundController;
use App\Http\Controllers\Api\Retail\InvestmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('retail')->group(function () {
    Route::get('/isa/funds', [FundController::class, 'index']);
    Route::post('/isa/investments', [InvestmentController::class, 'store']);
});
