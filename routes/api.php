<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatistikPelaporanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/pelaporan', [PelaporanController::class,'create']);
Route::get('/pelaporan', [PelaporanController::class,'get']);
Route::get('/pelaporan/{id_pelaporan}', [PelaporanController::class,'show']);
Route::put('/pelaporan/{id_pelaporan}', [PelaporanController::class,'update']);
Route::delete('/pelaporan/{id_pelaporan}', [PelaporanController::class,'delete']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/user', [UserController::class,'showall']);
Route::get('/pelaporans', [PelaporanController::class,'index']);
Route::get('/statistik-pelaporan', [StatistikPelaporanController::class, 'getStatistikPelaporan']);

Route::get('/users', [UserController::class, 'index']);  // Menampilkan daftar pengguna
Route::post('/users', [UserController::class, 'store']); // Menambahkan pengguna baru
Route::put('/users/{id}', [UserController::class, 'update']); // Mengupdate pengguna
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Menghapus pengguna