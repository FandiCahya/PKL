<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Logs;
use Illuminate\Support\Facades\Log;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['data' => User::latest()->get(), 'Message' => 'List Users']);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user',
        ]);

        try {
            // Simpan gambar ke storage
            $imagePath = $request->file('image')->store('profile_images', 'public');

            // Buat pengguna baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'image' => $imagePath,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ]);

            // Data log
            // $logData = [
            //     'user_id' => $user->id,
            //     'action' => 'create',
            //     'description' => 'Created a new user: ' . $user->name,
            //     'table_name' => 'users',
            //     'table_id' => $user->id,
            //     'data' => json_encode($user->toArray()),
            // ];

            // // Simpan log
            // Logs::create($logData);

            return response()->json(['message' => 'User berhasil ditambahkan.', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan pengguna: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'sometimes|string|max:255',
            'no_hp' => 'sometimes|string|max:20',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|max:50',
        ]);

        // Update user attributes
        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }
        if ($request->has('email')) {
            $user->email = $validatedData['email'];
        }
        if ($request->has('alamat')) {
            $user->alamat = $validatedData['alamat'];
        }
        if ($request->has('no_hp')) {
            $user->no_hp = $validatedData['no_hp'];
        }
        if ($request->has('role')) {
            $user->role = $validatedData['role'];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($user->image && $user->image !== 'default.png' && Storage::exists('public/' . $user->image)) {
                Storage::delete('public/' . $user->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('images', 'public');
            $user->image = $imagePath;
        }

        // Handle password update
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Save the updated user
        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Validate the incoming request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'sometimes|required|string|max:255',
            'no_hp' => 'sometimes|required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|in:admin,user',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);
    
        try {

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = public_path('profile_images');
                $fileName = $image->getClientOriginalName();
                $image->move($destinationPath, $fileName);
                $imagePath = 'profile_images/' . $fileName;
            }
    
            // Update the user with only the provided fields
            $user->update(array_filter([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'alamat' => $request->input('alamat'),
                'no_hp' => $request->input('no_hp'),
                'image' => $imagePath,
                'password' => $request->filled('password') ? bcrypt($request->password) : null,
                'role' => $request->input('role'),
            ]));

            // print('hello');
                    // Log the update
        Logs::create([
            'user_id' => $user->id, // The ID of the user making the update
            'action' => 'update',
            'description' => 'Updated user ID: ' . $user->id,
            'table_name' => 'users',
            'table_id' => $user->id,
            'data' => json_encode($user),
        ]);
    
            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (\Exception $e) {
            Logs::create([
                'user_id' => $user->id, // The ID of the user making the update
                'action' => 'update_failed',
                'description' => 'Failed to update user ID: ' . $user->id,
                'table_name' => 'users',
                'table_id' => $user->id,
                'data' => json_encode(['error' => $e->getMessage()]),
            ]);
            return response()->json(['error' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            // Hapus gambar jika ada
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted a user: ' . $user->name,
                'table_name' => 'users',
                'table_id' => $user->id,
                'data' => json_encode($user->toArray()),
            ];

            // Simpan log
            Logs::create($logData);

            $user->delete();

            return response()->json(['message' => 'User berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus pengguna: ' . $e->getMessage()], 500);
        }
    }
}
