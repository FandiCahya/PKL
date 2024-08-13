<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function index()
    {
        $times = TimeSlot::all();
        return response()->json($times, 200);
    }

    public function showByRoomId($roomId)
    {
        $times = TimeSlot::where('room_id', $roomId)->get();

        if ($times->isEmpty()) {
            return response()->json(['message' => 'No time slots found for this room.'], 404);
        }

        return response()->json($times, 200);
    }
}
