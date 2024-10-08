<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\BlockedTgl;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index()
    {
        // Fetch all blocked dates from the database
        $blockedDates = BlockedTgl::all();

        // Return the data as a JSON response
        return response()->json($blockedDates);
    }

    public function index2(){
        $blockdatetimes = BlockedDate::all();

        return response()->json($blockdatetimes);
    }
}
