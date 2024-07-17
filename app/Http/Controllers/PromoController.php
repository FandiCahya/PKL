<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Logs;
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
                'harga' => 'required|numeric|min:0',
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
                'harga' => $request->harga,
            ]);
    
            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'create',
                'description' => 'Created a new Class: ' . $promotion->name,
                'table_name' => 'promotions',
                'table_id' => $promotion->id,
                'data' => json_encode($promotion->toArray()),
            ];
    
            // Simpan log
            Logs::create($logData);
    
            return redirect()->route('kelola_promo')->with('success', 'Class berhasil ditambahkan.');
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
            'harga' => 'required|numeric|min:0',
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
            'harga' => $request->harga,
        ]);
    
        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated class: ' . $promo->name,
            'table_name' => 'promotions',
            'table_id' => $promo->id,
            'data' => json_encode($promo->toArray()),
        ];
    
        // Simpan log
        Logs::create($logData);
    
        return redirect()->route('kelola_promo')->with('success', 'Class berhasil diperbarui.');
    }

    public function hapusPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        $promoData = $promo->toArray();
        $promo->delete();
    
        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Deleted class: ' . $promoData['name'],
            'table_name' => 'promotions',
            'table_id' => $id,
            'data' => json_encode($promoData),
        ];
    
        // Simpan log
        Logs::create($logData);
    
        return redirect()->route('kelola_promo')->with('success', 'Class berhasil dihapus.');
    }
}
