<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BasecampController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\Api\UserController;

Route::apiResource('technicians', TechnicianController::class);
// 1. Public Route (Bisa diakses tanpa login)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::apiResource('technicians', TechnicianController::class);

// 2. Protected Route (Wajib Menggunakan Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Endpoint yang hanya bisa diakses oleh Role ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::get('/master-data', [MasterDataController::class, 'index']);

        // CRUD Area, Basecamp, & Users
        Route::apiResource('areas', AreaController::class);
        Route::apiResource('basecamps', BasecampController::class);
        Route::apiResource('users', UserController::class);
    });

});