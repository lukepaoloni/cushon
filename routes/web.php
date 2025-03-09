<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::fallback(function () {
    return redirect('/retail/isa/dashboard');
});

Route::get('/retail/isa/dashboard', function () {
    return Inertia::render('Retail/Isa/Dashboard', [
    ]);
});
