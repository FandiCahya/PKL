<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Room;
use App\Models\Promotion;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function kelola_booking(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $query = Booking::with('user', 'room');

        if ($search) {
            $query
                ->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('room', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
        }

        $bookings = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.bookings_table', compact('bookings'))->render();
        }

        return view('admin.kelola_booking', compact('bookings', 'profile'));
    }

    public function tambahBooking()
    {
        // Ambil data dari tabel rooms dan promotions
        $users = User::all();
        $rooms = Room::all();
        $promotions = Promotion::all();
        $profile = Auth::user();

        // Kirim data ke view
        return view('admin.tambah.booking', compact('users', 'rooms', 'promotions', 'profile'));
    }

    public function simpanBooking(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'nullable|exists:rooms,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'tgl' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'status' => 'required|in:Booked,Pending,Rejected',
        ]);

        $blockedDate = BlockedDate::where('blocked_date', $request->tgl)->first();

        if ($blockedDate) {
            return back()->withErrors(['tgl' => 'The selected date is blocked: ' . $blockedDate->reason]);
        }

        $room = Room::findOrFail($request->room_id);

        // Validate room availability
        if (!$room->availability) {
            return back()->withErrors(['room_id' => 'The selected room is not available for booking.']);
        }

        // Perhitungan end time
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = $startTime->copy()->addMinutes(60);

        // Insert the booking record to trigger the before_insert_bookings trigger
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'room_id' => $request->room_id,
            'promotion_id' => $request->promotion_id,
            'tgl' => $request->tgl,
            'start_time' => $request->start_time,
            'end_time' => $endTime->format('H:i'),
            'status' => $request->status,
            'qrcode' => '',
        ]);

        $room->reduceCapacity();

        // Generate the actual QR code
        $qrContent = 'Booking ID: ' . $booking->id . ', User ID: ' . $booking->user_id;
        $qrCode = QrCode::format('png')->generate($qrContent);
        $qrCodePath = 'qr_codes/' . uniqid() . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);

        // Update the booking record with the actual QR code path
        $booking->update(['qrcode' => $qrCodePath]);

        return redirect()->route('kelola_booking')->with('success', 'Booking created successfully.');
    }
    public function editBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $profile = Auth::user();
        return view('admin.edit.booking', compact('booking', 'profile'));
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

        $room = Room::findOrFail($request->room_id);

        // Validate room availability
        if (!$room->availability) {
            return back()->withErrors(['room_id' => 'The selected room is not available for booking.']);
        }

        // Calculate end_time (start_time + 60 minutes)
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = $startTime->copy()->addMinutes(60);

        $booking->update([
            'room_id' => $request->room_id,
            'promotion_id' => $request->promotion_id,
            'tgl' => $request->tgl,
            'start_time' => $request->start_time,
            'end_time' => $endTime->format('H:i'),
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

    public function checkAndUpdateRoomAvailability()
    {
        $now = Carbon::now();

        // Ambil semua booking yang masih aktif
        $activeBookings = Booking::where('tgl', '<=', $now->toDateString()) // Pastikan tanggal booking masih valid
            ->where(function ($query) use ($now) {
                $query->where('end_time', '<=', $now->toTimeString())->orWhereNull('end_time'); // Jika end_time null, dianggap masih aktif
            })
            ->get();

        foreach ($activeBookings as $booking) {
            // Cek apakah sudah melewati end_time
            if ($booking->end_time && $now->gt(Carbon::createFromFormat('H:i', $booking->end_time))) {
                // Mengembalikan kapasitas dan set availabilitas menjadi true
                $room = Room::find($booking->room_id);
                if ($room) {
                    $room->increaseCapacity();
                    $room->update(['availability' => true]);
                }
                $booking->update(['status' => 'Finished']);
            }
        }
    }
}
