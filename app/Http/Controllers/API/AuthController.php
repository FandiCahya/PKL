<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Logs;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            Logs::create([
                'user_id' => null,
                'action' => 'login_failed',
                'description' => 'Invalid login details',
                'data' => json_encode($credentials),
            ]);
            return response()->json([
                'user' => null,
                'message' => 'Invalid login details',
                'status' => 'failed',
            ], 401);
        }

        $user = Auth::user();
        // $token = $user->createToken('auth_token')->plainTextToken;

        // Logs::create([
        //     'user_id' => $user->id,
        //     'action' => 'login_success',
        //     'description' => 'User logged in successfully',
        //     'data' => json_encode($user),
        // ]);

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'alamat' => $user->alamat,
            'no_hp' => $user->no_hp,
            'image' => $user->image,
            'password' => $user->password,
            'status' => 'loggedin',
        ], 200);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            Logs::create([
                'user_id' => null,
                'action' => 'register_failed',
                'description' => 'Registration failed due to validation errors',
                'data' => json_encode($validator->errors()),
            ]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        Auth::login($user);
        // Logs::create([
        //     'user_id' => $user->id,
        //     'action' => 'register_success',
        //     'description' => 'User registered successfully',
        //     'data' => json_encode($user),
        // ]);

        return response()->json(['message' => 'Registration successful', 'user' => $user]);
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed'], 500);
        }
    }

    // public function edit_profile(Request $request)
    // {
    //     $user = Auth::user();

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    //         'alamat' => 'nullable|string|max:255',
    //         'no_hp' => 'nullable|string|max:15',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'password' => 'nullable|string|min:8|confirmed',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $dataToUpdate = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'alamat' => $request->alamat,
    //         'no_hp' => $request->no_hp,
    //     ];

    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('profile_images', 'public');
    //         $dataToUpdate['image'] = $imagePath;
    //     }

    //     if ($request->password) {
    //         $dataToUpdate['password'] = Hash::make($request->password);
    //     }

    //     $user->update($dataToUpdate);

    //     return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    // }
}
