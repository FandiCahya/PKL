<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BlockedDate;
use App\Models\BlockedTgl;
use App\Models\TimeSlot;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Room;
use App\Models\Promotion;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Logs;
use Illuminate\Support\Facades\File;

class BookingController extends Controller
{
    public function getBookingsByUserId($user_id)
    {
        $bookings = Booking::with(['room', 'promotion', 'timeSlot'])->
        where('user_id', $user_id)->get();
        return response()->json($bookings);
    }

    public function store(Request $request)
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
            $PtimeSlotId = $timeSlot->start_time;

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

        $room = Room::findOrFail($room_id);

        // Validate room availability
        if (!$room->availability) {
            return response()->json(['error' => 'The selected room is not available for booking.'], 400);
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
                $qrContent = 'Booking ID: ' . $booking->id . ', User: ' . $booking->user->name . ', Class: ' . $booking->promotion->name . ', Room: ' . $booking->promotion->room->nama . ', Tanggal: ' . $booking->promotion->tgl . ', Waktu: ' . $booking->promotion->waktu;
            }
            $qrCode = QrCode::format('png')->generate($qrContent);
            $fileName = uniqid() . '.png';
            $destinationPath = public_path('qr_codes/' . $fileName);
            File::put($destinationPath, $qrCode);
            $booking->qrcode = 'qr_codes/' . $fileName;
            $booking->save();
        }

        $room->reduceCapacity();

        return response()->json(['success' => 'Booking created successfully.', 'booking' => $booking], 201);
    }

    
}
