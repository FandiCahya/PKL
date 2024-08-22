<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Logs;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $pembayaran = Payment::with(['booking'])->get();
        return response()->json(['pembayaran' => $pembayaran], 200);
    }

    public function getPaymentByBookingId($booking_id)
    {
        // Cari pembayaran berdasarkan booking_id
        $payment = Payment::where('booking_id', $booking_id)->first();

        // Jika pembayaran ditemukan, kembalikan detail pembayaran
        if ($payment) {
            return response()->json([
                'success' => true,
                'payment' => $payment
            ], 200);
        }

        // Jika pembayaran tidak ditemukan, kembalikan pesan kesalahan
        return response()->json([
            'success' => false,
            'message' => 'Payment not found for the given booking ID.'
        ], 404);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        // Cek apakah sudah ada pembayaran untuk booking_id ini
        $existingPayment = Payment::where('booking_id', $request->booking_id)->first();
        if ($existingPayment) {
            return response()->json(
                [
                    'error' => 'Payment proof already exists for this booking.',
                ],
                400,
            );
        }

        // Ambil data booking berdasarkan booking_id
        $booking = Booking::findOrFail($request->booking_id);

        // Ambil harga dari tabel booking
        $amount = $request->amount;

        // Proses upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('payment_proof')) {
            $image = $request->file('payment_proof');
            $destinationPath = public_path('pembayaran');
            $fileName = $image->getClientOriginalName();
            $image->move($destinationPath, $fileName);
            $imagePath = 'pembayaran/' . $fileName;
        }

        // Simpan data pembayaran ke database
        $payment = Payment::create([
            'user_id' => $request->user_id,
            'booking_id' => $booking->id,
            'payment_proof' => $imagePath,
            'amount' => $amount,
        ]);

        $userId = $request->user_id;
        // Data log
        $logData = [
            'user_id' => $userId,
            'action' => 'create',
            'description' => 'Created a new payment for booking ID: ' . $payment->booking_id,
            'table_name' => 'payments',
            'table_id' => $payment->id,
            'data' => json_encode($payment->toArray()),
        ];

        Logs::create($logData);

        return response()->json(
            [
                'success' => 'Payment created successfully.',
                'payment' => $payment,
            ],
            201,
        );
    }
}
