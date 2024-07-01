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

    public function tambahUser()
    {
        return view('admin.tambah.user');
    }

    public function simpanUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('kelola_user')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit.user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,users',
        ],);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => $request->role,
        ]);

        return redirect()->route('kelola_user')->with('success', 'User berhasil diperbarui.');
    }

    public function hapusUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('kelola_user')->with('success', 'User berhasil dihapus.');
    }
    public function tambahPromo()
    {
        return view('admin.tambah.promo');
    }
    
    public function simpanPromo(Request $request)
    {
        try {
            // Validasi data
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'deskripsi' => 'required|string|max:255',
                'datetime' => 'required',
            ]);
    
            // Simpan gambar ke storage
            $imagePath = $request->file('image')->store('promotions', 'public');
    
            // Simpan data ke database
            $promotion = Promotion::create([
                'name' => $request->name,
                'image' => $imagePath,
                'deskripsi' => $request->deskripsi,
                'datetime' => $request->datetime,
            ]);
    
            return redirect()->route('kelola_promo')->with('success', 'Promo berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kirim pesan error ke view
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function editPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        return view('admin.edit.promo', compact('promo'));
    }
    
    public function updatePromo(Request $request, $id)
    {
        $promo = Promotion::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'required|string|max:255',
            'datetime' => 'required',
        ]);
    
        $imagePath = $promo->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotions', 'public');
        }
    
        $promo->update([
            'name' => $request->name,
            'image' => $imagePath,
            'deskripsi' => $request->deskripsi,
            'datetime' => $request->datetime,
        ]);
    
        return redirect()->route('kelola_promo')->with('success', 'Promo berhasil diperbarui.');
    }
    
    public function hapusPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->delete();
    
        return redirect()->route('kelola_promo')->with('success', 'Promo berhasil dihapus.');
    }
}
