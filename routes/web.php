<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlockedDateController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UsersController;

Route::get('/login', [AuthController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/', [AuthController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/', [AuthController::class, 'auth']);

Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth');

Route::middleware(['admin'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/kelola', [UsersController::class, 'kelola_users'])->name('kelola_user');
        Route::get('/tambah', [UsersController::class, 'tambahUser'])->name('tambah_user');
        Route::post('/simpan', [UsersController::class, 'simpanUser'])->name('simpan_user');
        Route::get('/edit/{id}', [UsersController::class, 'editUser'])->name('edit_user');
        Route::put('/update/{id}', [UsersController::class, 'updateUser'])->name('update_user');
        Route::delete('/hapus/{id}', [UsersController::class, 'hapusUser'])->name('hapus_user');
    });

    Route::prefix('rooms')->group(function () {
        Route::get('/kelola', [RoomController::class, 'kelola_rooms'])->name('kelola_room');
        Route::get('/tambah', [RoomController::class, 'tambahRoom'])->name('tambah_room');
        Route::post('/simpan', [RoomController::class, 'simpanRoom'])->name('simpan_room');
        Route::get('/edit/{id}', [RoomController::class, 'editRoom'])->name('edit_room');
        Route::put('/update/{id}', [RoomController::class, 'updateRoom'])->name('update_room');
        Route::delete('/hapus/{id}', [RoomController::class, 'hapusRoom'])->name('hapus_room');
    });

    Route::prefix('promo')->group(function () {
        Route::get('/kelola', [PromoController::class, 'kelola_promo'])->name('kelola_promo');
        Route::get('/tambah', [PromoController::class, 'tambahPromo'])->name('tambah_promo');
        Route::post('/simpan', [PromoController::class, 'simpanPromo'])->name('simpan_promo');
        Route::get('/edit/{id}', [PromoController::class, 'editPromo'])->name('edit_promo');
        Route::put('/update/{id}', [PromoController::class, 'updatePromo'])->name('update_promo');
        Route::delete('/hapus/{id}', [PromoController::class, 'hapusPromo'])->name('hapus_promo');
    });

    Route::prefix('booking')->group(function () {
        Route::get('/kelola', [BookingController::class, 'kelola_booking'])->name('kelola_booking');
        Route::get('/tambah', [BookingController::class, 'tambahBooking'])->name('tambah_booking');
        Route::post('/simpan', [BookingController::class, 'simpanBooking'])->name('simpan_booking');
        Route::get('/edit/{id}', [BookingController::class, 'editBooking'])->name('edit_booking');
        Route::put('/update/{id}', [BookingController::class, 'updateBooking'])->name('update_booking');
        Route::delete('/hapus/{id}', [BookingController::class, 'hapusBooking'])->name('hapus_booking');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/edit', [AuthController::class, 'edit_profile'])->name('profile.edit');
        Route::post('/update', [AuthController::class, 'update_Profile'])->name('profile.update');
    });

    Route::resource('/blocked_dates', BlockedDateController::class);
});
