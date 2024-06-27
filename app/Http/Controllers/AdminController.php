<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\Promotion;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahUser = User::count();
        $jumlahRoom = Room::count();
        $jumlahPromo = Promotion::count();
        return view('admin.dashboard',compact('jumlahUser','jumlahRoom','jumlahPromo'));
    }

    public function kelola_users(Request $request)
    {
        // $searching = User::search('name');
        $search = $request->query('search');
        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('alamat', 'LIKE', "%{$search}%")
                        ->orWhere('no_hp', 'LIKE', "%{$search}%")
                        ->orWhere('role', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $users = User::all();
        }
        return view('admin.kelola_user', compact('users'));
    }

    public function kelola_rooms(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $rooms = Room::where('nama', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $rooms = Room::all();
        }
        return view('admin.kelola_room', compact('rooms'));
    }

    public function kelola_promo(Request $request)
    {
        $search = $request->query('search');
        if ($search) {
            $promo = Promotion::where('deskripsi', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $promo = Promotion::all();
        }
        return view('admin.kelola_promo', compact('promo'));
    }

}
