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
Route::get('/tambah_user', [AdminController::class, 'tambahUser'])->name('tambah_user')->middleware('admin');
Route::post('/simpan_user', [AdminController::class, 'simpanUser'])->name('simpan_user')->middleware('admin');
Route::get('/edit_user/{id}', [AdminController::class, 'editUser'])->name('edit_user')->middleware('admin');
Route::put('/update_user/{id}', [AdminController::class, 'updateUser'])->name('update_user')->middleware('admin');
Route::delete('/hapus_user/{id}', [AdminController::class, 'hapusUser'])->name('hapus_user')->middleware('admin');


Route::get('/kelola_room', [AdminController::class, 'kelola_rooms'])->name('kelola_room')->middleware('admin');
Route::get('/tambah_room', [AdminController::class, 'tambahRoom'])->name('tambah_room')->middleware('admin');
Route::post('/simpan_room', [AdminController::class, 'simpanRoom'])->name('simpan_room')->middleware('admin');
Route::get('/edit_room/{id}', [AdminController::class, 'editRoom'])->name('edit_room')->middleware('admin');
Route::put('/update_room/{id}', [AdminController::class, 'updateRoom'])->name('update_room')->middleware('admin');
Route::delete('/hapus_room/{id}', [AdminController::class, 'hapusRoom'])->name('hapus_room')->middleware('admin');

Route::get('/kelola_promo', [AdminController::class, 'kelola_promo'])->name('kelola_promo')->middleware('admin');
Route::get('/tambah_promo', [AdminController::class, 'tambahPromo'])->name('tambah_promo')->middleware('admin');
Route::post('/simpan_promo', [AdminController::class, 'simpanPromo'])->name('simpan_promo')->middleware('admin');
Route::get('/edit_promo/{id}', [AdminController::class, 'editPromo'])->name('edit_promo')->middleware('admin');
Route::put('/update_promo/{id}', [AdminController::class, 'updatePromo'])->name('update_promo')->middleware('admin');
Route::delete('/hapus_promo/{id}', [AdminController::class, 'hapusPromo'])->name('hapus_promo')->middleware('admin');


Route::get('/kelola_booking', [AdminController::class, 'kelola_booking'])->name('kelola_booking')->middleware('admin');

Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth');