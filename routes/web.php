<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// 🖥️ VIEWS
Route::get('/', function () {
    return Inertia::render('Auth');
})->name('login');

 // ⚒️ ACTIONS
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');



Route::middleware(['auth'])->group(function () {

    // ⚒️ ACTIONS
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/', [UserController::class, 'store'])->name('user.store');
        Route::put('/', [UserController::class, 'store'])->name('user.update');
        Route::delete('/{userID}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    //🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹🔹

    // 🖥️ VIEWS
    Route::get('/panel/dashboard', function () {
        return Inertia::render('Panel');
    })->name('panel.dashboard');

    Route::get('/panel/data', function () {
        return Inertia::render('Data');
    })->name('panel.data');

    Route::get('/panel/users', [UserController::class, 'index'])->name('panel.users');
});


