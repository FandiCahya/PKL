<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;
use App\Models\Promotion;
use App\Models\Booking;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahUser = User::count();
        $jumlahRoom = Room::count();
        $jumlahPromo = Promotion::count();
        $jumlahBooking = Booking::count();
        $profile = Auth::user();
        return view('admin.dashboard', compact('jumlahUser', 'jumlahRoom', 'jumlahPromo', 'jumlahBooking', 'profile'));
    }

}
