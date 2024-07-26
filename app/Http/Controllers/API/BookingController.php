<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getBookingsByUserId($user_id)
    {
        $bookings = Booking::where('user_id', $user_id)->get();
        return response()->json($bookings);
    }

    // public function getBookingsByUserIdAndDate($userId, Request $request)
    // {
    //     $date = $request->query('date'); // Get date from query parameters

    //     // Query bookings based on user ID and date
    //     $bookings = Booking::where('user_id', $userId)
    //                         ->whereDate('tgl', $date)
    //                         ->get();

    //     return response()->json($bookings);
    // }
}
