<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedDate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BlockedDateController extends Controller
{
    public function index()
    {
        $profile = Auth::user();
        $blockedDates = BlockedDate::all();
        return view('admin.block_dates', compact('blockedDates','profile'));
    }

    public function create()
    {
        $profile = Auth::user();
        return view('admin.tambah.block_dates',compact('profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blocked_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedDate::create($request->all());

        return redirect()->route('blocked_dates.index')->with('success', 'Tanggal berhasil diblokir.');
    }

    public function edit(BlockedDate $blockedDate)
    {
        $profile = Auth::user();
        return view('admin.blocked_dates.edit', compact('blockedDate','profile'));
    }

    public function update(Request $request, BlockedDate $blockedDate)
    {
        $request->validate([
            'blocked_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        $blockedDate->update($request->all());

        return redirect()->route('blocked_dates.index')->with('success', 'Tanggal berhasil diperbarui.');
    }

    public function destroy(BlockedDate $blockedDate)
    {
        $blockedDate->delete();

        return redirect()->route('blocked_dates.index')->with('success', 'Tanggal berhasil dihapus.');
    }
}
