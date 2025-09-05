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
use App\Http\Controllers\FinanceController;

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
        Route::put('/{userID}', [UserController::class, 'update'])->name('update');
        Route::delete('/{userID}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('member')->name('member.')->group(function () {
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::put('/{memberID}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{memberID}', [MemberController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('category')->name('category.')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{categoryID}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{categoryID}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('finance')->name('finance.')->group(function () {
        Route::post('/', [FinanceController::class, 'store'])->name('store');
        Route::put('/{financeID}', [FinanceController::class, 'update'])->name('update');
        Route::delete('/{financeID}', [FinanceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('origin')->name('origin.')->group(function () {
        Route::post('/', [OriginController::class, 'store'])->name('store');
        Route::put('/{originID}', [OriginController::class, 'update'])->name('update');
        Route::delete('/{originID}', [OriginController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('data')->name('data.')->group(function () {
        Route::post('/', [DataController::class, 'store'])->name('store');
        Route::put('/{dataID}', [DataController::class, 'update'])->name('update');
        Route::delete('/{dataID}', [DataController::class, 'destroy'])->name('destroy');
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
        Route::get('/finances', [FinanceController::class, 'index'])->name('finances');
        Route::get('/data', [DataController::class, 'index'])->name('data');
    });
});
