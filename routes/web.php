<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlockedDateController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InstrukturController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BookingScheduleController;
use App\Http\Controllers\TimeSlotController;
use App\Models\Schedule;
use App\Http\Controllers\BlockedTglController;
use App\Models\Promotion;

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

    Route::prefix('class')->group(function () {
        Route::get('/kelola', [PromoController::class, 'kelola_promo'])->name('kelola_promo');
        Route::get('/tambah', [PromoController::class, 'tambahPromo'])->name('tambah_promo');
        Route::post('/simpan', [PromoController::class, 'simpanPromo'])->name('simpan_promo');
        Route::get('/edit/{id}', [PromoController::class, 'editPromo'])->name('edit_promo');
        Route::put('/update/{id}', [PromoController::class, 'updatePromo'])->name('update_promo');
        Route::delete('/hapus/{id}', [PromoController::class, 'hapusPromo'])->name('hapus_promo');
    });

    Route::prefix('booking')->group(function () {
        Route::get('/kelola/{id?}', [BookingController::class, 'kelola_booking'])->name('kelola_booking');
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

    Route::resource('instrukturs', InstrukturController::class);

    Route::resource('blocked_tgl', BlockedTglController::class);

    Route::resource('time-slots', TimeSlotController::class);

    Route::resource('/payments', PaymentController::class);

    Route::prefix('paymentsq')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::post('/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
        Route::post('/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::get('/validate/{id}', [PaymentController::class, 'getBookingDetails'])->name('payment.getBookingDetails');
    });
    

    

    Route::put('bookings/{id}/validate', [BookingController::class, 'validateBooking'])->name('validate_booking');

    // Menampilkan semua jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');

    // Menampilkan form untuk membuat jadwal baru
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');

    // Menyimpan jadwal baru
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');

    // Menampilkan form untuk mengedit jadwal
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');

    // Memperbarui jadwal yang sudah ada
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');

    // Menghapus jadwal
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Route untuk index, create, store, edit, update, destroy
    Route::get('/booking-schedule', [BookingScheduleController::class, 'index'])->name('booking_schedule.index');
    Route::get('/booking-schedule/create', [BookingScheduleController::class, 'create'])->name('tambah_booking_schedule');
    Route::post('/booking-schedule/store', [BookingScheduleController::class, 'store'])->name('simpan_booking_schedule');
    Route::get('/booking-schedule/edit/{id}', [BookingScheduleController::class, 'edit'])->name('edit_booking_schedule');
    Route::put('/booking-schedule/update/{id}', [BookingScheduleController::class, 'update'])->name('update_booking_schedule');
    Route::delete('/booking-schedule/delete/{id}', [BookingScheduleController::class, 'destroy'])->name('hapus_booking_schedule');

    // Route untuk search
    Route::get('/booking-schedule/search', [BookingScheduleController::class, 'index'])->name('kelola_booking_schedule');
});

Route::get('/api/schedules', function () {
    $schedules = Promotion::with(['instruktur', 'room'])->get();

    $events = $schedules->map(function ($schedule) {
        return [
            'title' => $schedule->name,
            'start' => $schedule->tgl,
            'room_name' => $schedule->room->nama,
            'instruktur_name' => $schedule->instruktur->nama,
        ];
    });

    return response()->json($events);
});
