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
use App\Models\BlockedDate;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahUser = User::count();
        $jumlahRoom = Room::count();
        $jumlahPromo = Promotion::count();
        $jumlahBooking = Booking::count();
        $profile = Auth::user();
        return view('admin.dashboard',compact('jumlahUser','jumlahRoom','jumlahPromo','jumlahBooking','profile'));
    }

    public function kelola_users(Request $request)
    {
        $profile = Auth::user();
        $search = $request->query('search');
        $query = User::query();
        
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

        $users = $query->paginate(10);
        return view('admin.kelola_user', compact('users','profile'));
    }

    public function kelola_rooms(Request $request)
    {
        $search = $request->query('search');
        $query = Room::query();
        $profile = Auth::user();
        if ($search) {
            $rooms = Room::where('nama', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $rooms = Room::all();
        }
        $rooms = $query->paginate(10);
        return view('admin.kelola_room', compact('rooms','profile'));
    }

    public function kelola_booking(Request $request){
        $search = $request->get('search');
        $query = Booking::query();
        $profile = Auth::user();
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
        $bookings = $query->paginate(10);
        // dd($bookings);
        return view('admin.kelola_booking', compact('bookings','profile'));
    }

    public function kelola_promo(Request $request)
    {
        $search = $request->query('search');
        $query = Promotion::query();
        $profile = Auth::user();
        if ($search) {
            $promo = Promotion::where('deskripsi', 'LIKE', "%{$search}%")
                        ->get();
        } else {
            $promo = Promotion::all();
        }
        $promo = $query->paginate(10);
        return view('admin.kelola_promo', compact('promo','profile'));
    }

    public function tambahRoom()
    {
        $profile = Auth::user();
        return view('admin.tambah.room',compact('profile'));
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
        $profile = Auth::user();
        return view('admin.edit.room', compact('room','profile'));
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
        $profile = Auth::user();
        return view('admin.tambah.user',compact('profile'));
    }

    public function simpanUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);
    
        // Simpan gambar ke storage
        $imagePath = $request->file('image')->store('profile_images', 'public');
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'image' => $imagePath,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
    
        return redirect()->route('kelola_user')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $profile = Auth::user();
        $user = User::findOrFail($id);
        return view('admin.edit.user', compact('user','profile'));
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
            'role' => 'required|in:admin,user',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $user->image;
        if ($request->hasFile('image')) {
            // Simpan gambar baru ke storage
            $imagePath = $request->file('image')->store('profile_images', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'image' => $imagePath,
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
        $profile = Auth::user();
        return view('admin.tambah.promo',compact('profile'));
    }
    
    public function simpanPromo(Request $request)
    {
        try {
            // Validasi data
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'deskripsi' => 'required|string|max:255',
                'tgl' => 'required|date',
                'waktu' => 'required',
            ]);
    
            // Simpan gambar ke storage
            $imagePath = $request->file('image')->store('promotions', 'public');
    
            // Simpan data ke database
            $promotion = Promotion::create([
                'name' => $request->name,
                'image' => $imagePath,
                'deskripsi' => $request->deskripsi,
                'tgl' => $request->tgl,
                'waktu' => $request->waktu,
            ]);
    
            return redirect()->route('kelola_promo')->with('success', 'Promo berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kirim pesan error ke view
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function editPromo($id)
    {
        $profile = Auth::user();
        $promo = Promotion::findOrFail($id);
        return view('admin.edit.promo', compact('promo','profile'));
    }
    
    public function updatePromo(Request $request, $id)
    {
        $promo = Promotion::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'required|string|max:255',
            'tgl' => 'required|date',
            'waktu' => 'required',
        ]);
    
        $imagePath = $promo->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotions', 'public');
        }
    
        $promo->update([
            'name' => $request->name,
            'image' => $imagePath,
            'deskripsi' => $request->deskripsi,
            'tgl' => $request->tgl,
            'waktu' => $request->waktu,
        ]);
    
        return redirect()->route('kelola_promo')->with('success', 'Promo berhasil diperbarui.');
    }
    
    public function hapusPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->delete();
    
        return redirect()->route('kelola_promo')->with('success', 'Promo berhasil dihapus.');
    }

    public function editBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $profile = Auth::user();
        return view('admin.edit.booking', compact('booking','profile'));
    }
    
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
    
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'tgl' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'status' => 'required|in:Booked,Pending,Rejected',
        ]);

        $blockedDate = BlockedDate::where('blocked_date', $request->tgl)->first();

        if ($blockedDate) {
        return back()->withErrors(['tgl' => 'Tanggal ini diblokir: ' . $blockedDate->reason]);
        }
    
        $booking->update([
            'room_id' => $request->room_id,
            'promotion_id' => $request->promotion_id,
            'tgl' => $request->tgl,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);
    
        return redirect()->route('kelola_booking')->with('success', 'Booking berhasil diperbarui.');
    }
    
    public function hapusBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
    
        return redirect()->route('kelola_booking')->with('success', 'Booking berhasil dihapus.');
    }
    
    public function edit_profile()
    {
        $user = Auth::user();
        $profile = Auth::user();
        return view('admin.edit.profile', compact('user','profile'));
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Data untuk diupdate
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ];
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $dataToUpdate['image'] = $imagePath;
        }
    
        if ($request->password) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }
    
        // Update user
        $user->update($dataToUpdate);
    
        return redirect('/dashboard')->with('success', 'Profile updated successfully.');
    }
    
    public function block_dates()
    {
        $blockedDates = BlockedDate::all();
        return view('admin.blocked_dates.index', compact('blockedDates'));
    }

    public function hapus_block_dates($id)
    {
        $blockedDate = BlockedDate::findOrFail($id);
        $blockedDate->delete();

        return redirect()->route('blocked-dates.index')->with('success', 'Tanggal berhasil dihapus.');
    }

}
