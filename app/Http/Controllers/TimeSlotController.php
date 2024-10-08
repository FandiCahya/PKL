<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Logs;

class TimeSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $timeSlots = TimeSlot::whereHas('room', function($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%");
        })
        ->orWhere('start_time', 'like', "%{$search}%")
        ->orWhere('end_time', 'like', "%{$search}%")
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Using pagination
    
        if ($request->ajax()) {
            return view('admin.time_slots.table', compact('timeSlots'))->render();
        }
    
        return view('admin.time_slots.index', compact('timeSlots', 'profile', 'search'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profile = Auth::user();
        $rooms = Room::all();
        return view('admin.time_slots.create', compact('profile', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'availability' => 'required|boolean',
        ]);

        // $timeSlot = TimeSlot::create($request->all());
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);

        while ($startTime->lessThan($endTime)) {
            // Buat TimeSlot baru untuk setiap jam
            $timeSlot = TimeSlot::create([
                'start_time' => $startTime->format('H:i'),
                'end_time' => $startTime->copy()->addHour()->format('H:i'),
                'room_id' => $request->room_id,
                'availability' => $request->availability,
            ]);

            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'create',
                'description' => 'Created a new time slot: ' . $timeSlot->start_time . ' - ' . $timeSlot->end_time,
                'table_name' => 'time_slots',
                'table_id' => $timeSlot->id,
                'data' => json_encode($timeSlot->toArray()),
            ];

            // Save log
            Logs::create($logData);
            $startTime->addHour();
        }
        return redirect()->route('time-slots.index')->with('success', 'Time slot created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSlot $timeSlot)
    {
        $profile = Auth::user();
        return view('admin.time_slots.show', compact('timeSlot', 'profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeSlot $timeSlot)
    {
        $profile = Auth::user();
        $rooms = Room::all();
        return view('admin.time_slots.edit', compact('timeSlot', 'profile', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeSlot $timeSlot)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'availability' => 'required|boolean',
        ]);

        $timeSlot->update($request->all());

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated a time slot: ' . $timeSlot->start_time . ' - ' . $timeSlot->end_time,
            'table_name' => 'time_slots',
            'table_id' => $timeSlot->id,
            'data' => json_encode($timeSlot->toArray()),
        ];

        // Save log
        Logs::create($logData);

        return redirect()->route('time-slots.index')->with('success', 'Time slot updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlotData = $timeSlot->toArray();
    
        try {
            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted a time slot: ' . $timeSlot->start_time . ' - ' . $timeSlot->end_time,
                'table_name' => 'time_slots',
                'table_id' => $timeSlot->id,
                'data' => json_encode($timeSlotData),
            ];
    
            // Simpan log
            Logs::create($logData);
    
            // Soft delete time slot
            $timeSlot->delete();
    
            return redirect()->route('time-slots.index')->with('success', 'Time slot deleted successfully.');
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            // Log::error('Failed to delete time slot with ID ' . $timeSlot->id . ': ' . $e->getMessage());
    
            // Redirect dengan pesan error
            return redirect()->route('time-slots.index')->with('error', 'Failed to delete time slot: ' . $e->getMessage());
        }
    }
    
}
