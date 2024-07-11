<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }

    
    public function auth(Request $request)
    {
        $credential = $request->only('email', 'password');

        if (Auth::attempt($credential)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); 
        }

        return back()->with('loginError', 'Belum Berhasil, nih!');
    }

    public function edit_profile()
    {
        $user = Auth::user();
        $profile = Auth::user();
        return view('admin.edit.profile', compact('user', 'profile'));
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Data untuk diupdate
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $dataToUpdate['image'] = $imagePath;
        }

        if ($request->password) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($dataToUpdate);

        return redirect('/dashboard')->with('success', 'Profile updated successfully.');
    }

}
