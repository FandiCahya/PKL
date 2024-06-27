<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/',  [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/', [AuthController::class, 'auth']);

Route::get('/kelola_user', [AdminController::class, 'kelola_users'])->name('kelola_user')->middleware('admin');
Route::get('/kelola_room', [AdminController::class, 'kelola_rooms'])->name('kelola_room')->middleware('admin');
Route::get('/kelola_promo', [AdminController::class, 'kelola_promo'])->name('kelola_promo')->middleware('admin');

Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth');