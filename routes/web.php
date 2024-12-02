<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Perros', [
        'dato' => 'Este es un valor pasado desde Laravel',
    ]);
});
