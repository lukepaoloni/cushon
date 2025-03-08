<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::fallback(function () {
    return redirect('/retail/isa/funds');
});

Route::get('/retail/isa/funds', function () {
    return Inertia::render('Retail/Isa/Funds', [
    ]);
});
