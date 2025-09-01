<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// 🖥️ VIEWS
Route::get('/', function () {
    return Inertia::render('Auth');
})->name('auth');

 // ⚒️ ACTIONS
Route::post('/login', [AuthController::class, 'authenticate'])->name('login');



Route::middleware(['auth'])->group(function () {

    // ⚒️ ACTIONS
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
    Route::prefix('user')->group(function () {
        Route::post('/', [AuthController::class, 'store'])->name('store');
        Route::put('/', [UserController::class, 'store'])->name('store');
        Route::delete('/{userID}', [UserController::class, 'destroy'])->name('destroy');
    });

    //🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹

    // 🖥️ VIEWS
    Route::get('/panel/dashboard', function () {
        return Inertia::render('Panel');
    })->name('panel.dashboard');

    Route::get('/panel/data', function () {
        return Inertia::render('Data');
    })->name('panel.data');

    Route::get('/panel/users', function () {
        return Inertia::render('Users');
    })->name('panel.users');
});


