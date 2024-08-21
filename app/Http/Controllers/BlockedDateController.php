<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedDate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Logs;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Log;

class BlockedDateController extends Controller
{
    public function index(Request $request)
    {
        $profile = Auth::user();
        $search = $request->input('search');
        $query = BlockedDate::query();

        if ($search) {
            $query->where('blocked_date', 'LIKE', "%{$search}%")->orWhere('reason', 'LIKE', "%{$search}%");
        }

        $blockedDates = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.blocked_dates_table', compact('blockedDates'))->render();
        }

        return view('admin.block_dates', compact('blockedDates', 'profile'));
    }

    public function create()
    {
        $profile = Auth::user();
        $timeSlots = TimeSlot::all();
        return view('admin.tambah.block_dates', compact('profile', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blocked_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'reason' => 'nullable|string|max:255',
        ]);

        $blockdate = BlockedDate::create($request->all());

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new Block Date: ' . $blockdate->nama,
            'table_name' => 'blocked_dates',
            'table_id' => $blockdate->id,
            'data' => json_encode($blockdate->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('blocked_dates.index')->with('success', 'Tanggal berhasil diblokir.');
    }

    public function edit($id)
    {
        $blockedDate = BlockedDate::findOrFail($id);
        $profile = Auth::user();
        $timeSlots = TimeSlot::all();
        return view('admin.edit.block_dates', compact('blockedDate', 'profile','timeSlots'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'reason' => 'nullable|string|max:255',
        ]);

        $blockedDate = BlockedDate::findOrFail($id);
        $blockedDate->update([
            'date' => $request->date,
            'reason' => $request->reason,
        ]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated blocked date: ' . $blockedDate->id,
            'table_name' => 'blocked_dates',
            'table_id' => $blockedDate->id,
            'data' => json_encode($blockedDate->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('blocked_dates.index')->with('success', 'Blocked date updated successfully.');
    }
    public function destroy($id)
    {
        try {
            $blockedDate = BlockedDate::findOrFail($id);
            $blockedDateData = $blockedDate->toArray();
    
            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted blocked date: ' . $blockedDateData['blocked_date'],
                'table_name' => 'blocked_dates',
                'table_id' => $id,
                'data' => json_encode($blockedDateData),
            ];
    
            // Simpan log
            Logs::create($logData);
    
            // Soft delete blocked date
            $blockedDate->delete();
    
            return redirect()->route('blocked_dates.index')->with('success', 'Tanggal berhasil dihapus.');
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            Log::error('Failed to delete blocked date with ID ' . $id . ': ' . $e->getMessage());
    
            // Redirect dengan pesan error
            return redirect()->route('blocked_dates.index')->with('error', 'Gagal menghapus tanggal: ' . $e->getMessage());
        }
    }
    
}
