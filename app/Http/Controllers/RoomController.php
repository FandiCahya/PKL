<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function kelola_rooms(Request $request)
    {
        $profile = Auth::user();
        $search = $request->query('search');
        $query = Room::query();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%");
        }

        $rooms = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.rooms_table', compact('rooms'))->render();
        }

        return view('admin.kelola_room', compact('rooms', 'profile'));
    }

    public function tambahRoom()
    {
        $profile = Auth::user();
        return view('admin.tambah.room', compact('profile'));
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
        return view('admin.edit.room', compact('room', 'profile'));
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
