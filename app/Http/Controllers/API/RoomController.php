<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return response()->json($rooms, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'availability' => 'required|boolean',
            'harga' => 'required|numeric',
        ]);

        $room = Room::create($validatedData);

        return response()->json($room, 201);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        return response()->json($room, 200);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'kapasitas' => 'sometimes|required|integer',
            'availability' => 'sometimes|required|boolean',
            'harga' => 'sometimes|required|numeric',
        ]);

        $room->update($validatedData);

        return response()->json($room, 200);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(null, 204);
    }
}

