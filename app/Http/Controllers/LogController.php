<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $profile = Auth::user();
        $search = $request->input('search');
        $logs = Logs::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('action', 'like', "%{$search}%")->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('admin.logs', compact('logs', 'search','profile'));
    }
}
