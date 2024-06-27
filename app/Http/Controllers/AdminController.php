<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahUser = User::count();
        return view('admin.dashboard',compact('jumlahUser'));
    }



}
