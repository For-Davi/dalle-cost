<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth');
});

Route::get('/panel/dashboard', function () {
    return Inertia::render('Panel');
})->name('panel.dashboard');

Route::get('/panel/data', function () {
    return Inertia::render('Data');
})->name('panel.data');

Route::get('/panel/users', function () {
    return Inertia::render('Users');
})->name('panel.users');