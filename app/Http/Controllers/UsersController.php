<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function kelola_users(Request $request)
    {
        $profile = Auth::user();
        $search = $request->query('search');
        $query = User::query();

        if ($search) {
            $query
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
                ->orWhere('no_hp', 'LIKE', "%{$search}%")
                ->orWhere('role', 'LIKE', "%{$search}%");
        }

        $users = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.users_table', compact('users'))->render();
        }

        return view('admin.kelola_user', compact('users', 'profile'));
    }

    public function tambahUser()
    {
        $profile = Auth::user();
        return view('admin.tambah.user', compact('profile'));
    }

    public function simpanUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
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

            return redirect()->route('kelola_user')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('kelola_user')->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        $profile = Auth::user();
        $user = User::findOrFail($id);
        return view('admin.edit.user', compact('user', 'profile'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
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
                'role' => $request->role,
            ]);

            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'update',
                'description' => 'Updated a user: ' . $user->name,
                'table_name' => 'users',
                'table_id' => $user->id,
                'data' => json_encode($user->toArray()),
            ];

            // Simpan log
            Logs::create($logData);

            return redirect()->route('kelola_user')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('kelola_user')->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function hapusUser($id)
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

            return redirect()->route('kelola_user')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kelola_user')->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}

