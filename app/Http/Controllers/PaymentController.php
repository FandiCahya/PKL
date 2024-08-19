<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $payments = Payment::where('booking_id', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.payments.table', compact('payments'))->render();
        }

        return view('admin.payments.index', compact('payments', 'profile', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profile = Auth::user();
        $bookings = Booking::all();
        $users = User::all();
        return view('admin.payments.create', compact('profile', 'bookings', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        $image = $request->file('payment_proof');

        // Define the destination path (public/promotions)
        $destinationPath = public_path('pembayaran');

        // Get the file's original name
        $fileName = $image->getClientOriginalName();

        // Move the file to the destination path
        $image->move($destinationPath, $fileName);

        // Get the relative path to save in the database or further processing
        $imagePath = 'pembayaran/' . $fileName;

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'booking_id' => $request->booking_id,
            'payment_proof' => $imagePath,
            'amount' => $request->amount,
        ]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new payment for booking ID: ' . $payment->booking_id,
            'table_name' => 'payments',
            'table_id' => $payment->id,
            'data' => json_encode($payment->toArray()),
        ];

        Logs::create($logData);

        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $profile = Auth::user();
        return view('admin.payments.show', compact('payment', 'profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $profile = Auth::user();
        return view('admin.payments.edit', compact('payment', 'profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $payment->payment_proof;
        if ($request->hasFile('payment_proof')) {
            // $imagePath = $request->file('image')->store('promotions', 'public');
            $fileName = $request->file('payment_proof')->getClientOriginalName();
            $request->file('payment_proof')->move(public_path('pembayaran'), $fileName);
            $imagePath = 'pembayaran/' . $fileName;
        }

        $payment->update($request->except('payment_proof'));

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated payment for booking ID: ' . $payment->booking_id,
            'table_name' => 'payments',
            'table_id' => $payment->id,
            'data' => json_encode($payment->toArray()),
        ];

        Logs::create($logData);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Deleted payment for booking ID: ' . $payment->booking_id,
            'table_name' => 'payments',
            'table_id' => $payment->id,
            'data' => json_encode($payment->toArray()),
        ];

        Logs::create($logData);

        // Delete the file if it exists
        // if ($payment->payment_proof && \Storage::exists('public/' . $payment->payment_proof)) {
        //     \Storage::delete('public/' . $payment->payment_proof);
        // }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
    public function confirm(Request $request)
    {
        $payment = Payment::find($request->payment_id);
        if ($payment) {
            $payment->status = 'confirmed';
            $payment->save();

            $booking = Booking::find($payment->booking_id);
            // $bookingType = Booking::find($payment->booking());
            if ($booking) {
                $booking->status = 'Booked';
                $booking->save();
            }
            
            if ($booking->status === 'Booked') {
                // Generate the QR code
                if ($booking->booking_type === 'room') {
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

            // Log the confirmation action
            Logs::create([
                'user_id' => Auth::id(),
                'action' => 'confirm',
                'description' => 'Confirmed payment for booking ID: ' . $booking->id,
                'table_name' => 'payments',
                'table_id' => $payment->id,
                'data' => json_encode($payment->toArray()),
            ]);

            return redirect()->route('payments.index')->with('success', 'Payment Confirmed successfully.');
        }

        return redirect()->route('payments.index')->with('error', 'Payment not found.');
    }

    public function reject(Request $request)
    {
        $payment = Payment::find($request->payment_id);
        if ($payment) {
            $payment->status = 'rejected';
            $payment->save();

            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->status = 'Rejected';
                $booking->save();
            }

            // Log the rejection action
            Logs::create([
                'user_id' => Auth::id(),
                'action' => 'reject',
                'description' => 'Rejected payment for booking ID: ' . $booking->id,
                'table_name' => 'payments',
                'table_id' => $payment->id,
                'data' => json_encode($payment->toArray()),
            ]);

            return redirect()->route('payments.index')->with('success', 'Payment Rejected successfully.');
        }

        return redirect()->route('payments.index')->with('error', 'Payment not found.');
    }

    public function getBookingDetails($id)
    {
        $payment = Payment::with(['booking.user', 'booking.room', 'booking.timeSlot', 'booking.promotion'])->find($id);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        return response()->json($payment);
    }
}
