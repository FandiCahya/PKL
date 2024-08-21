<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Logs;
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

        $rooms = $query->orderBy('created_at', 'desc')->paginate(10);

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
        ]);

        $room = Room::create([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
            'availability' => $request->availability,
        ]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new room: ' . $room->nama,
            'table_name' => 'rooms',
            'table_id' => $room->id,
            'data' => json_encode($room->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

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
        ]);

        $room = Room::findOrFail($id);
        $room->update([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
            'availability' => $request->availability,
        ]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated room: ' . $room->nama,
            'table_name' => 'rooms',
            'table_id' => $room->id,
            'data' => json_encode($room->toArray()),
        ];

        // Simpan log
        logs::create($logData);

        return redirect()->route('kelola_room')->with('success', 'Room berhasil diperbarui.');
    }
    public function hapusRoom($id)
    {
        try {
            $room = Room::findOrFail($id);
            $roomData = $room->toArray();

            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted room: ' . $roomData['nama'],
                'table_name' => 'rooms',
                'table_id' => $id,
                'data' => json_encode($roomData),
            ];

            // Simpan log
            Logs::create($logData);

            // Soft delete room
            $room->delete();

            return redirect()->route('kelola_room')->with('success', 'Room berhasil dihapus.');
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            // Logs::error('Gagal menghapus room dengan ID ' . $id . ': ' . $e->getMessage());

            // Redirect dengan pesan error
            return redirect()
                ->route('kelola_room')
                ->with('error', 'Gagal menghapus room: ' . $e->getMessage());
        }
    }
}
