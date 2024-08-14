<?php

use App\Http\Controllers\BookingController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function(){
    (new BookingController())->checkAndUpdateRoomAvailability();
})->everyMinute();

Schedule::command('timeslot:update-availability')->everyMinute();
