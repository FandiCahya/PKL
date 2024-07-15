<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $instrukturs = Instruktur::where('nama', 'like', "%{$search}%")->paginate(10);
        
        if ($request->ajax()) {
            return view('admin.kelola_instruktur', compact('instrukturs'))->render();
        }

        return view('admin.kelola_instruktur', compact('instrukturs','profile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profile = Auth::user();
        return view('admin.tambah.instruktur',compact('profile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        Instruktur::create($request->all());

        return redirect()->route('instrukturs.index')->with('success', 'Instruktur created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instruktur $instruktur)
    {
        $profile = Auth::user();
        return view('admin.kelola_instruktur', compact('instruktur','profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instruktur $instruktur)
    {
        $profile = Auth::user();
        return view('admin.edit.instruktur', compact('instruktur','profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instruktur $instruktur)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:instrukturs,email,' . $instruktur->id,
        ]);

        $instruktur->update($request->all());

        return redirect()->route('instrukturs.index')->with('success', 'Instruktur updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instruktur $instruktur)
    {
        $instruktur->delete();

        return redirect()->route('instrukturs.index')->with('success', 'Instruktur deleted successfully.');
    }
}
