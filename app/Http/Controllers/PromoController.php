<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoController extends Controller
{
    public function kelola_promo(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $query = Promotion::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')->orWhere('deskripsi', 'like', '%' . $search . '%');
        }

        $promo = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.promos_table', compact('promo'))->render();
        }

        return view('admin.kelola_promo', compact('promo', 'profile'));
    }

    public function tambahPromo()
    {
        $profile = Auth::user();
        return view('admin.tambah.promo', compact('profile'));
    }

    public function simpanPromo(Request $request)
    {
        try {
            // Validasi data
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'deskripsi' => 'required|string|max:255',
                'tgl' => 'required|date',
                'waktu' => 'required',
            ]);

            // Simpan gambar ke storage
            $imagePath = $request->file('image')->store('promotions', 'public');

            // Simpan data ke database
            $promotion = Promotion::create([
                'name' => $request->name,
                'image' => $imagePath,
                'deskripsi' => $request->deskripsi,
                'tgl' => $request->tgl,
                'waktu' => $request->waktu,
            ]);

            return redirect()->route('kelola_promo')->with('success', 'Promo berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kirim pesan error ke view
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editPromo($id)
    {
        $profile = Auth::user();
        $promo = Promotion::findOrFail($id);
        return view('admin.edit.promo', compact('promo', 'profile'));
    }

    public function updatePromo(Request $request, $id)
    {
        $promo = Promotion::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'deskripsi' => 'required|string|max:255',
            'tgl' => 'required|date',
            'waktu' => 'required',
        ]);

        $imagePath = $promo->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('promotions', 'public');
        }

        $promo->update([
            'name' => $request->name,
            'image' => $imagePath,
            'deskripsi' => $request->deskripsi,
            'tgl' => $request->tgl,
            'waktu' => $request->waktu,
        ]);

        return redirect()->route('kelola_promo')->with('success', 'Promo berhasil diperbarui.');
    }

    public function hapusPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->delete();

        return redirect()->route('kelola_promo')->with('success', 'Promo berhasil dihapus.');
    }

}
