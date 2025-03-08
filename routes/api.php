<?php

use App\Http\Controllers\Api\Retail\FundController;
use Illuminate\Support\Facades\Route;

Route::prefix('retail')->group(function () {
    Route::get('/isa/funds', [FundController::class, 'index']);
});
