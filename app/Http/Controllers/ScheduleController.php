<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Promotion;
use App\Models\Instruktur;
use App\Models\Room;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::query();
        $profile = Auth::user();
    
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('promotion', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('instruktur', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('room', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }
    
        $schedules = $query->with(['promotion', 'instruktur', 'room'])->paginate(10);
    
        if ($request->ajax()) {
            return view('schedules.schedule_table', compact('schedules', 'profile'))->render();
        }
    
        return view('admin.kelola_schedule', compact('schedules', 'profile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $promotions = Promotion::all();
        $instrukturs = Instruktur::all();
        $profile = Auth::user();
        $rooms = Room::all();
        return view('admin.tambah.schedule', compact('promotions', 'instrukturs', 'rooms', 'profile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promotions_id' => 'required|exists:promotions,id',
            'instrukturs_id' => 'required|exists:instrukturs,id',
            'rooms_id' => 'required|exists:rooms,id',
            'tgl' => 'required|date',
        ]);

        $schedule = Schedule::create($request->all());
        
        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new schedule: ' . $schedule->id,
            'table_name' => 'schedules',
            'table_id' => $schedule->id,
            'data' => json_encode($schedule->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $promotions = Promotion::all();
        $instrukturs = Instruktur::all();
        $profile = Auth::user();
        $rooms = Room::all();
        return view('admin.edit.schedule', compact('schedule', 'promotions', 'instrukturs', 'rooms', 'profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'promotions_id' => 'required|exists:promotions,id',
            'instrukturs_id' => 'required|exists:instrukturs,id',
            'rooms_id' => 'required|exists:rooms,id',
            'tgl' => 'required|date',
        ]);

        $schedule->update($request->all());
        
        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated schedule: ' . $schedule->id,
            'table_name' => 'schedules',
            'table_id' => $schedule->id,
            'data' => json_encode($schedule->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $scheduleData = $schedule->toArray();
        $schedule->delete();

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Deleted schedule: ' . $scheduleData['id'],
            'table_name' => 'schedules',
            'table_id' => $scheduleData['id'],
            'data' => json_encode($scheduleData),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
