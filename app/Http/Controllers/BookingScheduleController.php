<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingSchedule;
use App\Models\Schedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Logs;

class BookingScheduleController extends Controller
{
    public function index(Request $request)
    {
        $profile = Auth::user();
        $search = $request->input('search');
        $query = BookingSchedule::query()->with('schedule.room', 'schedule.instruktur'); // Eager load relations

        if ($search) {
            $query->whereHas('schedule.room', function ($q) use ($search) {
                $q->where('kelas', 'like', '%' . $search . '%');
            });
        }

        $booking_schedule = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.kelola_booking_schedule', compact('booking_schedule', 'profile'))->render();
        }

        return view('admin.kelola_booking_schedule', compact('booking_schedule', 'profile'));
    }

    public function create()
    {
        $schedules = Schedule::all();
        $profile = Auth::user();
        return view('admin.tambah.booking_schedule', compact('schedules', 'profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:Booked,Pending,Rejected,Finished',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        $bookingSchedule = BookingSchedule::create([
            'schedule_id' => $request->schedule_id,
            'status' => $request->status,
            'qrcode' => '', // Placeholder for QR code generation
        ]);

        // Generate QR Code logic goes here
        $qrContent = 'Booking Schedule ID: ' . $bookingSchedule->id . ', Schedule id: ' . $bookingSchedule->schedule->id . ', Kelas: ' . $bookingSchedule->schedule->promotion->name . ', Room: ' . $bookingSchedule->schedule->room->nama . ', Instruktur: ' . $bookingSchedule->schedule->instruktur->nama . ', Harga: ' . $bookingSchedule->schedule->promotion->harga;
        $qrCode = QrCode::format('png')->generate($qrContent);
        // Example: Replace with your QR code generation logic
        $qrCodePath = 'qr_codes/' . uniqid() . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);

        // Update booking schedule with QR code path
        $bookingSchedule->update(['qrcode' => $qrCodePath]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new booking Schedule: ' . $bookingSchedule->id,
            'table_name' => 'booking_schedule',
            'table_id' => $bookingSchedule->id,
            'data' => json_encode($bookingSchedule->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('booking_schedule.index')->with('success', 'Booking schedule created successfully.');
    }

    public function edit($id)
    {
        $booking_schedule = BookingSchedule::findOrFail($id);
        $schedules = Schedule::all();
        $profile = Auth::user();
        return view('admin.edit.booking_schedule', compact('booking_schedule', 'schedules', 'profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:Booked,Pending,Rejected,Finished',
        ]);

        $booking_schedule = BookingSchedule::findOrFail($id);
        $booking_schedule->update([
            'schedule_id' => $request->schedule_id,
            'status' => $request->status,
        ]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated booking Class: ' . $booking_schedule->id,
            'table_name' => 'booking_class',
            'table_id' => $booking_schedule->id,
            'data' => json_encode($booking_schedule->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('booking_schedule.index')->with('success', 'Booking schedule updated successfully.');
    }

    public function destroy($id)
    {
        $booking_schedule = BookingSchedule::findOrFail($id);
        $bookingData = $booking_schedule->toArray();
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Deleted booking schedule: ' . $bookingData['id'],
            'table_name' => 'booking_schedule ',
            'table_id' => $bookingData['id'],
            'data' => json_encode($bookingData),
        ];

        // Simpan log
        Logs::create($logData);
        $booking_schedule->delete();

        return redirect()->route('booking_schedule.index')->with('success', 'Booking schedule deleted successfully.');
    }
}
