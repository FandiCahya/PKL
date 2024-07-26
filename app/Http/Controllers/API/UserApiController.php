<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Logs;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['data'=>User::latest()->get(),'Message'=>'List Users']);
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
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'create',
                'description' => 'Created a new user: ' . $user->name,
                'table_name' => 'users',
                'table_id' => $user->id,
                'data' => json_encode($user->toArray()),
            ];

            // Simpan log
            Logs::create($logData);

            return response()->json(['message' => 'User berhasil ditambahkan.', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan pengguna: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|in:user',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $imagePath = $user->image;
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                // Simpan gambar baru ke storage
                $imagePath = $request->file('image')->store('profile_images', 'public');
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'image' => $imagePath,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                // 'role' => $request->role,
            ]);

            // Data log
            // $logData = [
            //     'user_id' => Auth::id(),
            //     'action' => 'update',
            //     'description' => 'Updated a user: ' . $user->name,
            //     'table_name' => 'users',
            //     'table_id' => $user->id,
            //     'data' => json_encode($user->toArray()),
            // ];

            // // Simpan log
            // Logs::create($logData);

            return response()->json(['message' => 'User berhasil diperbarui.', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui pengguna: ' . $e->getMessage()], 500);
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
