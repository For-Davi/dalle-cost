<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DashboardController;

// 🖥️ Auth Views
Route::get('/', fn () => Inertia::render('Auth'))->name('login');

// ⚒️ Auth Actions
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

// 🔒 Routes requiring authentication
Route::middleware(['auth'])->group(function () {

    // ⚒️ Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | CRUD Routes (generic users)
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('member')->name('member.')->group(function () {
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::put('/{member}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('category')->name('category.')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('origin')->name('origin.')->group(function () {
        Route::post('/', [OriginController::class, 'store'])->name('store');
        Route::put('/{origin}', [OriginController::class, 'update'])->name('update');
        Route::delete('/{origin}', [OriginController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('data')->name('data.')->group(function () {
        Route::post('/', [DataController::class, 'store'])->name('store');
        Route::put('/{data}', [DataController::class, 'update'])->name('update');
        Route::delete('/{data}', [DataController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Panel
    |--------------------------------------------------------------------------
    */
    Route::prefix('panel')->name('panel.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/members', [MemberController::class, 'index'])->name('members');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::get('/origins', [OriginController::class, 'index'])->name('origins');
        Route::get('/data', [DataController::class, 'index'])->name('data');
    });
});
