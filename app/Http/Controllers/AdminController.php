<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.dashboard',compact('jumlahUser','jumlahRoom','jumlahPromo','jumlahBooking'));
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

    public function kelola_booking(Request $request){
        $search = $request->get('search');
        
        if($search){
            $bookings = Booking::with('user', 'room')
            ->whereHas('user', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('room', function($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();
        }else{
            $bookings = Booking::all();
        }
        // dd($bookings);
        return view('admin.kelola_booking', compact('bookings'));
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

    public function tambahRoom()
    {
        return view('admin.tambah.room');
    }
    
    public function simpanRoom(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'availability' => 'required|boolean',
            'harga' => 'required|integer',
        ]);

        Room::create([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
            'availability' => $request->availability,
            'harga' => $request->harga,
        ]);

        return redirect()->route('kelola_room')->with('success', 'Room berhasil ditambahkan.');
    }
    public function editRoom($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.edit.room', compact('room'));
    }

    public function updateRoom(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'availability' => 'required|boolean',
            'harga' => 'required|integer',
        ]);

        $room = Room::findOrFail($id);
        $room->update([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
            'availability' => $request->availability,
            'harga' => $request->harga,
        ]);

        return redirect()->route('kelola_room')->with('success', 'Room berhasil diperbarui.');
    }
    public function hapusRoom($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        
        return redirect()->route('kelola_room')->with('success', 'Room berhasil dihapus.');
    }

    

}
