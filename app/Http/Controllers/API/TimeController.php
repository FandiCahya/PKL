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
}
