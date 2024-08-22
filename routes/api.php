<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\TimeController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\BlockController;



Route::get('/blocked-dates', [BlockController::class, 'index']);
Route::get('/blocked-datestimes', [BlockController::class, 'index2']);

// Route::get('detailbooking/{id}',[BookingController::class,'show']);
Route::get('detailbooking/{user_id}', [BookingController::class, 'getBookingsByUserId']);
Route::post('/bookings', [BookingController::class, 'store']);



// Route::get('/detailbooking/{userId}', [BookingController::class, 'getBookingsByUserIdAndDate']);
// Route::apiResource('rooms', RoomController::class);
Route::get('/kelolarooms',[RoomController::class,'index']);

Route::get('/kelolawaktu',[TimeController::class,'index']);
Route::get('times/room/{roomId}', [TimeController::class, 'showByRoomId']);

Route::get('/kelolapembayaran',[PaymentController::class,'index']);
Route::post('payments', [PaymentController::class, 'store']);
Route::get('payments/{booking_id}', [PaymentController::class, 'getPaymentByBookingId']);



Route::post('/auth/login', [AuthController::class, 'login']);
  
Route::post('/auth/logout', [AuthController::class, 'logout']);


Route::get('kelolakelas', [PromoController::class, 'kelola_promo']);
Route::get('detailkelas/{id}', [PromoController::class, 'show'])->name('api.show_promo');
// Route::post('simpankelas', [PromoController::class, 'simpanPromo'])->name('api.simpan_promo');
// Route::put('updatekelas/{id}', [PromoController::class, 'updatePromo'])->name('api.update_promo');
// Route::delete('hapuskelas/{id}', [PromoController::class, 'hapusPromo'])->name('api.hapus_promo');

Route::get('userlist', [UserApiController::class, 'index']);
Route::get('detailuser/{id}', [UserApiController::class, 'show']);
Route::post('createuser', [UserApiController::class, 'store']);
Route::put('updateuser/{id}', [UserApiController::class, 'updateUser']);
Route::delete('deleteuser/{id}', [UserApiController::class, 'destroy']);

Route::post('/user/{id}', [UserApiController::class, 'updateUser']);


