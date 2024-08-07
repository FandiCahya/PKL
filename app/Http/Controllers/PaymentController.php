<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;

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
            return view('admin.payments.index', compact('payments'))->render();
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
        return view('admin.payments.create', compact('profile','bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string|max:255',
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
            'booking_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $payment->payment_proof;
        if ($request->hasFile('payment_proof')) {
            // $imagePath = $request->file('image')->store('promotions', 'public');
            $fileName = $request->file('payment_proof')->getClientOriginalName();
            $request->file('payment_proof')->move(public_path('pembayaran'), $fileName);
            $imagePath = 'pembayaran/'.$fileName;
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
}
