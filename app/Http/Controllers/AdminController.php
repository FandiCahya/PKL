<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;
use App\Models\Promotion;
use App\Models\Booking;
use App\Models\BookingSchedule;
use App\Models\Instruktur;
use App\Models\Logs;
use App\Models\Payment;

// use App\Models\Schedule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahUser = User::count();
        $jumlahRoom = Room::count();
        $jumlahPromo = Promotion::count();
        $jumlahBooking = Booking::count();
        $jumlahBooking2 = BookingSchedule::count();
        $jumlahInstruktur = Instruktur::count();
        $jumlahPayments = Payment::count();
        $jumlahBlockdate = BlockedDate::count();
        $jumlahLog = Logs::count();
        $profile = Auth::user();
        return view('admin.dashboard', compact('jumlahLog','jumlahBlockdate','jumlahBooking2','jumlahUser', 'jumlahRoom', 'jumlahPromo', 'jumlahBooking','jumlahInstruktur', 'profile','jumlahPayments'));
    }

}
