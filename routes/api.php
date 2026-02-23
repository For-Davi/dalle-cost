<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('token.secret')->group(function () {

    Route::get('/movements', function () {
        return response()->json(
            DB::table('movements')->get()
        );
    });

    Route::get('/categories', function () {
        return response()->json(
            DB::table('categories')->get()
        );
    });

    Route::get('/members', function () {
        return response()->json(
            DB::table('members')->get()
        );
    });

    Route::get('/origins', function () {
        return response()->json(
            DB::table('origins')->get()
        );
    });

    Route::get('/receipts', function () {
        return response()->json(
            DB::table('receipts')->get()
        );
    });

    Route::get('/users', function () {
        return response()->json(
            DB::table('users')->get()
        );
    });

});
