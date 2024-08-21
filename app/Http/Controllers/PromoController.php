<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use App\Models\Promotion;
use App\Models\Logs;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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

        $promo = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.promos_table', compact('promo'))->render();
        }

        return view('admin.kelola_promo', compact('promo', 'profile'));
    }

    public function tambahPromo()
    {
        $profile = Auth::user();
        $roomsq = Room::all();
        $instruktursq = Instruktur::all();
        return view('admin.class.create', compact('profile','roomsq','instruktursq'));
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
                'room_id' => 'required|exists:rooms,id',
                'instruktur_id' => 'required|exists:instrukturs,id',
            ]);
    
            // Simpan gambar ke storage
            $image = $request->file('image');
            $destinationPath = public_path('kelas');
            $fileName = $image->getClientOriginalName();
            $image->move($destinationPath, $fileName);
            $imagePath = 'kelas/' . $fileName;
    
            // Simpan data ke database
            $promotion = Promotion::create([
                'name' => $request->name,
                'image' => $imagePath,
                'deskripsi' => $request->deskripsi,
                'tgl' => $request->tgl,
                'waktu' => $request->waktu,
                'harga' => $request->harga,
                'room_id' => $request->room_id,
                'instruktur_id' => $request->instruktur_id,
            ]);

            // dd($promotion);
    
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
            // Tambahkan logging error ke log Laravel
            Log::error('Error adding promo: ' . $e->getMessage());
    
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
        $rooms = Room::all();
        $instrukturs = Instruktur::all();
        return view('admin.class.edit', compact('promo', 'profile','rooms','instrukturs'));
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
            'room_id' => 'required|exists:rooms,id',
            'instruktur_id' => 'required|exists:instrukturs,id',
        ]);

        $imagePath = $promo->image;
        if ($request->hasFile('image')) {
            // $imagePath = $request->file('image')->store('promotions', 'public');
            $fileName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('kelas'), $fileName);
            $imagePath = 'kelas/' . $fileName;
        }

        $promo->update([
            'name' => $request->name,
            'image' => $imagePath,
            'deskripsi' => $request->deskripsi,
            'tgl' => $request->tgl,
            'waktu' => $request->waktu,
            'harga' => $request->harga,
            'room_id' => $request->room_id,
            'instruktur_id' => $request->instruktur_id,
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
        try {
            $promo = Promotion::findOrFail($id);
            $promoData = $promo->toArray();
    
            // Data log
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted promotion: ' . $promoData['name'],
                'table_name' => 'promotions',
                'table_id' => $id,
                'data' => json_encode($promoData),
            ];
    
            // Simpan log
            Logs::create($logData);
    
            // Soft delete promotion
            $promo->delete();
    
            return redirect()->route('kelola_promo')->with('success', 'Promotion berhasil dihapus.');
        } catch (\Exception $e) {
            // Log error untuk keperluan debugging
            Log::error('Failed to delete promotion with ID ' . $id . ': ' . $e->getMessage());
    
            // Redirect dengan pesan error
            return redirect()->route('kelola_promo')->with('error', 'Failed to delete promotion: ' . $e->getMessage());
        }
    }
    
}
