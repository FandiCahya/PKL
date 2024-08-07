<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use App\Models\BlockedTgl;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Room;
use App\Models\Promotion;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Logs;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\File;

class BookingController extends Controller
{
    public function kelola_booking(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $query = Booking::with('user', 'room', 'timeSlot', 'promotion');

        if ($search) {
            $query
                ->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('room', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.bookings_table', compact('bookings'))->render();
        }

        return view('admin.kelola_booking', compact('bookings', 'profile'));
    }

    public function tambahBooking()
    {
        // Fetch data from related tables
        $users = User::all();
        $rooms = Room::all();
        $promotions = Promotion::all();
        $timeSlots = TimeSlot::all();
        $profile = Auth::user();

        // Send data to view
        return view('admin.tambah.booking', compact('users', 'rooms', 'promotions', 'profile', 'timeSlots'));
    }

    public function simpanBooking(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'nullable|exists:rooms,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'tgl' => 'required_if:promotion_id,null|date',
            'time_slot_id' => 'required_if:promotion_id,null|exists:time_slots,id',
            'status' => 'nullable|in:Booked,Pending,Rejected',
        ]);
    
        // Determine the booking type
        if ($request->room_id) {
            $bookingType = 'room';
        } elseif ($request->promotion_id) {
            $bookingType = 'class';
        } else {
            return response()->json(['error' => 'Either room_id or promotion_id must be provided.'], 400);
        }
    
        // Set default status to 'Pending' if not provided
        $status = $request->status ?? 'Pending';

        $tgl = null;
        $timeSlotId = null;
        $PtimeSlotId = null;
        $price = 0;
        $room_id = null;
    
        // Fetch promotion details if booking type is 'class'
        if ($bookingType == 'class') {
            $promotion = Promotion::findOrFail($request->promotion_id);
    
            $tgl = $promotion->tgl;
            $PtimeSlotId = $promotion->waktu;
            $room_id = $promotion->room_id;
            $price = $promotion->harga;
        } else {
            $tgl = $request->tgl;
            $timeSlotId = $request->time_slot_id;
    
            // Fetch time slot details
            $timeSlot = TimeSlot::findOrFail($timeSlotId);
            $startTimeString = $timeSlot->start_time;
            $endTimeString = $timeSlot->end_time;
    
            try {
                $startTime = Carbon::parse($startTimeString);
                $endTime = Carbon::parse($endTimeString);
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                return response()->json(['error' => 'Error parsing time: ' . $e->getMessage()], 400);
            }
    
            $duration = $startTime->diffInMinutes($endTime);
            $price = ($duration / 60) * 25000; // Price per hour
            $room_id = $request->room_id;
        }
    
        $blockedDate = BlockedDate::where('blocked_date', $tgl)
            ->where(function ($query) use ($timeSlotId) {
                $query->where('time_slot_id', $timeSlotId);
            })
            ->first();
    
        if ($blockedDate) {
            return response()->json(['error' => 'The selected date is blocked: ' . $blockedDate->reason], 400);
        }
    
        $blockedTgl = BlockedTgl::where('blocked_date', $tgl)->first();
    
        if ($blockedTgl) {
            return response()->json(['error' => 'The selected date is blocked: ' . $blockedTgl->reason], 400);
        }
    
        if ($bookingType == 'room') {
            $room = Room::findOrFail($room_id);
    
            // Validate room availability
            if (!$room->availability) {
                return response()->json(['error' => 'The selected room is not available for booking.'], 400);
            }
        }
    
        // Insert the booking record
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'room_id' => $room_id,
            'promotion_id' => $request->promotion_id,
            'booking_type' => $bookingType,
            'tgl' => $tgl,
            'time_slot_id' => $timeSlotId,
            'promotion_time' => $PtimeSlotId,
            'harga' => $price,
            'status' => $status,
            'qrcode' => $status === 'Pending' ? '' : null,
        ]);
    
        if ($booking->status === 'Booked') {
            // Generate the QR code
            if ($bookingType == 'room') {
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Room: ' . $booking->room->nama . ', Tanggal: ' . $booking->tgl . ', Waktu: ' . $booking->timeSlot->start_time . ' - ' . $booking->timeSlot->end_time;
            } else {
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name .', Class: ' . $booking->promotion->name .', Room: ' . $booking->promotion->room->nama . ', Tanggal: ' . $booking->promotion->tgl . ', Waktu: ' . $booking->promotion->waktu;
            }
            $qrCode = QrCode::format('png')->generate($qrContent);
            $fileName = uniqid() . '.png';
            $destinationPath = public_path('qr_codes/' . $fileName);
            File::put($destinationPath, $qrCode);
            $booking->qrcode = 'qr_codes/' . $fileName;
            $booking->save();
        }
    
            $room->reduceCapacity();

        // Log data
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new booking: ' . $booking->id,
            'table_name' => 'bookings',
            'table_id' => $booking->id,
            'data' => json_encode($booking->toArray()),
        ];
        Logs::create($logData);

        return redirect()->route('kelola_booking')->with('success', 'Booking created successfully.');
    }

    public function editBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $profile = Auth::user();
        $timeSlots = TimeSlot::all();
        $promotions = Promotion::all();
        return view('admin.edit.booking', compact('booking', 'profile', 'timeSlots', 'promotions'));
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'nullable|exists:rooms,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'tgl' => 'required_if:promotion_id,null|date',
            'time_slot_id' => 'required_if:promotion_id,null|exists:time_slots,id',
            'status' => 'nullable|in:Booked,Pending,Rejected',
        ]);

        // Determine the booking type
        if ($request->room_id) {
            $bookingType = 'room';
        } elseif ($request->promotion_id) {
            $bookingType = 'class';
        } else {
            return response()->json(['error' => 'Either room_id or promotion_id must be provided.'], 400);
        }
    
        // Set default status to 'Pending' if not provided
        $status = $request->status ?? 'Pending';

        $tgl = null;
        $timeSlotId = null;
        $PtimeSlotId = null;
        $price = 0;
        $room_id = null;

        // Fetch promotion details if booking type is 'class'
        if ($bookingType == 'class') {
            $promotion = Promotion::findOrFail($request->promotion_id);
    
            $tgl = $promotion->tgl;
            $PtimeSlotId = $promotion->waktu;
            $room_id = $promotion->room_id;
            $price = $promotion->harga;
        } else {
            $tgl = $request->tgl;
            $timeSlotId = $request->time_slot_id;
    
            // Fetch time slot details
            $timeSlot = TimeSlot::findOrFail($timeSlotId);
            $startTimeString = $timeSlot->start_time;
            $endTimeString = $timeSlot->end_time;
    
            try {
                $startTime = Carbon::parse($startTimeString);
                $endTime = Carbon::parse($endTimeString);
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                return response()->json(['error' => 'Error parsing time: ' . $e->getMessage()], 400);
            }
    
            $duration = $startTime->diffInMinutes($endTime);
            $price = ($duration / 60) * 25000; // Price per hour
            $room_id = $request->room_id;
        }

        $blockedDate = BlockedDate::where('blocked_date', $request->tgl)
            ->where(function ($query) use ($request) {
                $query->where('time_slot_id', $request->time_slot_id);
            })
            ->first();

        if ($blockedDate) {
            return back()->withErrors(['tgl' => 'Tanggal ini diblokir: ' . $blockedDate->reason]);
        }

        $blockedTgl = BlockedTgl::where('blocked_date', $request->tgl)->first();

        if ($blockedTgl) {
            return back()->withErrors(['tgl' => 'The selected date is blocked: ' . $blockedDate->reason]);
        }

        $room = Room::findOrFail($request->room_id);

        // Validate room availability
        if (!$room->availability) {
            return back()->withErrors(['room_id' => 'The selected room is not available for booking.']);
        }

        // // Fetch time slot details
        // $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
        // $startTimeString = $timeSlot->start_time;
        // $endTimeString = $timeSlot->end_time;

        // try {
        //     $startTime = Carbon::parse($startTimeString);
        //     $endTime = Carbon::parse($endTimeString);
        // } catch (\Carbon\Exceptions\InvalidFormatException $e) {
        //     // Handle the error, maybe log it or correct the format
        //     dd('Error parsing time:', $e->getMessage(), $startTimeString, $endTimeString);
        // }
        // if ($endTime->lt($startTime)) {
        //     $duration = $startTime->diffInMinutes($endTime);
        // } else {
        //     $duration = $startTime->diffInMinutes($endTime);
        // }
        // $price = ($duration / 60) * 25000; // Price per hour

        $booking->update([
            'user_id' => $request->user_id,
            'room_id' => $room_id,
            'promotion_id' => $request->promotion_id,
            'booking_type' => $bookingType,
            'tgl' => $tgl,
            'time_slot_id' => $timeSlotId,
            'promotion_time' => $PtimeSlotId,
            'harga' => $price,
            'status' => $status,
            'qrcode' => $status === 'Pending' ? '' : null,
        ]);

        if ($booking->status === 'Booked' && !$booking->qrcode) {
            // Generate the QR code
            if ($bookingType == 'room') {
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Room: ' . $booking->room->nama . ', Tanggal: ' . $booking->tgl . ', Waktu: ' . $booking->timeSlot->start_time . ' - ' . $booking->timeSlot->end_time;
            } else {
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Class: ' . $booking->promotion->name . ', Room: ' . $booking->promotion->room->nama . ', Tanggal: ' . $booking->promotion->tgl . ', Waktu: ' . $booking->promotion->waktu;
            }
            $qrCode = QrCode::format('png')->generate($qrContent);
            $fileName = uniqid() . '.png';
            $destinationPath = public_path('qr_codes/' . $fileName);
            File::put($destinationPath, $qrCode);
            $booking->qrcode = 'qr_codes/' . $fileName;
            $booking->save();
        }

        // Log data
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated booking: ' . $booking->id,
            'table_name' => 'bookings',
            'table_id' => $booking->id,
            'data' => json_encode($booking->toArray()),
        ];
        Logs::create($logData);

        return redirect()->route('kelola_booking')->with('success', 'Booking berhasil diperbarui.');
    }

    public function hapusBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $bookingData = $booking->toArray();

        // Log data
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Deleted booking: ' . $bookingData['id'],
            'table_name' => 'bookings',
            'table_id' => $bookingData['id'],
            'data' => json_encode($bookingData),
        ];
        Logs::create($logData);

        $booking->delete();

        return redirect()->route('kelola_booking')->with('success', 'Booking berhasil dihapus.');
    }

    public function checkAndUpdateRoomAvailability()
    {
        $now = Carbon::now();

        // Get all active bookings
        $activeBookings = Booking::where('tgl', '<=', $now->toDateString())
            ->whereHas('timeSlot', function ($query) use ($now) {
                $query->where('end_time', '<=', $now->toTimeString())->orWhereNull('end_time');
            })
            ->get();

        foreach ($activeBookings as $booking) {
            // Check if end_time has passed
            $endTime = Carbon::createFromFormat('H:i', $booking->timeSlot->end_time);
            if ($now->gt($endTime)) {
                // Restore room capacity and set availability to true
                $room = Room::find($booking->room_id);
                if ($room) {
                    $room->increaseCapacity();
                    $room->update(['availability' => true]);
                }
                $booking->update(['status' => 'Finished']);
            }
        }
    }

    public function validateBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Booked,Rejected,Pending',
        ]);

        // Update booking status
        $booking->update([
            'status' => $request->status,
            'qrcode' => $request->status === 'Booked',
        ]);
        
        $bookingType = $booking->booking_type;

        if ($booking->status === 'Booked') {
            // Generate the QR code
            if ($bookingType == 'room') {
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Room: ' . $booking->room->nama . ', Tanggal: ' . $booking->tgl . ', Waktu: ' . $booking->timeSlot->start_time . ' - ' . $booking->timeSlot->end_time;
            } else {
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Class: ' . $booking->promotion->name . ', Room: ' . $booking->promotion->room->nama . ', Tanggal: ' . $booking->promotion->tgl . ', Waktu: ' . $booking->promotion->waktu;
            }
            // $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Room: ' . $booking->room->nama . ', Tanggal: ' . $booking->tgl . ', Waktu: ' . $booking->timeSlot->start_time . ' - ' . $booking->timeSlot->end_time;
            $qrCode = QrCode::format('png')->generate($qrContent);
            $fileName = uniqid() . '.png';
            $destinationPath = public_path('qr_codes/' . $fileName);
            File::put($destinationPath, $qrCode);
            $booking->qrcode = 'qr_codes/' . $fileName;
            $booking->save();
        }

        // Log data
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated booking status to: ' . $booking->status . ' for booking ID: ' . $booking->id,
            'table_name' => 'bookings',
            'table_id' => $booking->id,
            'data' => json_encode($booking->toArray()),
        ];
        Logs::create($logData);

        return redirect()->route('kelola_booking')->with('success', 'Booking status updated successfully.');
    }
}
