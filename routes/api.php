<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/login", [UsersController::class, "login"]);
Route::post("/daftar", [UsersController::class, "daftar"]);
Route::get("/kategori", [AnimalController::class, "category"]);
Route::get("/habitat", [AnimalController::class, "habitat"]);
Route::apiResource("/animal", AnimalController::class);
