<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoController extends Controller
{
    public function kelola_promo(Request $request)
    {

        $kelas = Promotion::all();

        return response()->json(['promotions' => $kelas]);
    }

    public function show($id)
    {
        $kelas = Promotion::findOrFail($id);
        return response()->json($kelas);
    }

    public function simpanPromo(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'deskripsi' => 'required|string|max:255',
                'tgl' => 'required|date',
                'waktu' => 'required',
                'harga' => 'required|numeric|min:0',
            ]);

            $imagePath = $request->file('image')->store('promotions', 'public');

            $promotion = Promotion::create([
                'name' => $request->name,
                'image' => $imagePath,
                'deskripsi' => $request->deskripsi,
                'tgl' => $request->tgl,
                'waktu' => $request->waktu,
                'harga' => $request->harga,
            ]);

            $logData = [
                'user_id' => Auth::id(),
                'action' => 'create',
                'description' => 'Created a new Promotion: ' . $promotion->name,
                'table_name' => 'promotions',
                'table_id' => $promotion->id,
                'data' => json_encode($promotion->toArray()),
            ];

            Logs::create($logData);

            return response()->json(['success' => 'Promotion successfully added', 'promotion' => $promotion], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function editPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        return response()->json(['promotion' => $promo]);
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

        $logData = [
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => 'Updated Promotion: ' . $promo->name,
            'table_name' => 'promotions',
            'table_id' => $promo->id,
            'data' => json_encode($promo->toArray()),
        ];

        Logs::create($logData);

        return response()->json(['success' => 'Promotion successfully updated', 'promotion' => $promo]);
    }

    public function hapusPromo($id)
    {
        $promo = Promotion::findOrFail($id);
        $promoData = $promo->toArray();
        $promo->delete();

        $logData = [
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => 'Deleted Promotion: ' . $promoData['name'],
            'table_name' => 'promotions',
            'table_id' => $id,
            'data' => json_encode($promoData),
        ];

        Logs::create($logData);

        return response()->json(['success' => 'Promotion successfully deleted']);
    }
}
