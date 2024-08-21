<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedTgl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Logs;
use Illuminate\Support\Facades\Log;

class BlockedTglController extends Controller
{
    public function index(Request $request)
    {
        $profile = Auth::user();
        $search = $request->input('search');
        $query = BlockedTgl::query();

        if ($search) {
            $query->where('blocked_date', 'LIKE', "%{$search}%")->orWhere('reason', 'LIKE', "%{$search}%");
        }

        $blockedDates = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.blocked_tgl.table', compact('blockedDates'))->render();
        }

        return view('admin.blocked_tgl.index', compact('blockedDates', 'profile'));
    }

    public function create()
    {
        $profile = Auth::user();
        return view('admin.blocked_tgl.create', compact('profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blocked_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        $blockdate = BlockedTgl::create($request->all());

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new Block Date: ' . $blockdate->blocked_date,
            'table_name' => 'blocked_tgl',
            'table_id' => $blockdate->id,
            'data' => json_encode($blockdate->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('blocked_tgl.index')->with('success', 'Tanggal berhasil diblokir.');
    }

    public function edit($id)
    {
        $blockedDate = BlockedTgl::findOrFail($id);
        $profile = Auth::user();
        return view('admin.blocked_tgl.edit', compact('blockedDate', 'profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'blocked_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        $blockedDate = BlockedTgl::findOrFail($id);
        $blockedDate->update([
            'blocked_date' => $request->blocked_date,
            'reason' => $request->reason,
        ]);

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated blocked date: ' . $blockedDate->id,
            'table_name' => 'blocked_tgl',
            'table_id' => $blockedDate->id,
            'data' => json_encode($blockedDate->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('blocked_tgl.index')->with('success', 'Tanggal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            // Cari entri berdasarkan ID
            $blockedDate = BlockedTgl::findOrFail($id);
            $blockedDateData = $blockedDate->toArray();
    
            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted blocked date: ' . $blockedDateData['id'],
                'table_name' => 'blocked_tgl',
                'table_id' => $blockedDateData['id'],
                'data' => json_encode($blockedDateData),
            ];
    
            // Simpan log
            Logs::create($logData);
    
            // Soft delete blocked date
            $blockedDate->delete();
    
            return redirect()->route('blocked_tgl.index')->with('success', 'Tanggal berhasil dihapus.');
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            Log::error('Failed to delete blocked date with ID ' . $id . ': ' . $e->getMessage());
    
            // Redirect dengan pesan error
            return redirect()->route('blocked_tgl.index')->with('error', 'Gagal menghapus tanggal: ' . $e->getMessage());
        }
    }
    
}
