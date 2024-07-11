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
    public function index(Request $request)
    {
        $profile = Auth::user();
        $search = $request->input('search');
        $query = BlockedDate::query();

        if ($search) {
            $query->where('blocked_date', 'LIKE', "%{$search}%")->orWhere('reason', 'LIKE', "%{$search}%");
        }

        $blockedDates = $query->get();

        if ($request->ajax()) {
            return view('admin.blocked_dates_table', compact('blockedDates'))->render();
        }

        return view('admin.block_dates', compact('blockedDates', 'profile'));
    }

    public function create()
    {
        $profile = Auth::user();
        return view('admin.tambah.block_dates', compact('profile'));
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

    public function edit($id)
    {
        $blockedDate = BlockedDate::findOrFail($id);
        $profile = Auth::user();
        return view('admin.edit.block_dates', compact('blockedDate', 'profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        $blockedDate = BlockedDate::findOrFail($id);
        $blockedDate->update([
            'date' => $request->date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('blocked_dates.index')->with('success', 'Blocked date updated successfully.');
    }
    public function destroy($id)
    {
        $blockedDate = BlockedDate::findOrFail($id);
        $blockedDate->delete();

        return redirect()->route('blocked_dates.index')->with('success', 'Tanggal berhasil dihapus.');
    }
}
