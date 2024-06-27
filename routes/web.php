<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/',  [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/', [AuthController::class, 'auth']);

Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth');