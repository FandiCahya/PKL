<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $profile = Auth::user();
        $instrukturs = Instruktur::where('nama', 'like', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('alamat', 'LIKE', "%{$search}%")
            ->orWhere('no_hp', 'LIKE', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.kelola_instruktur', compact('instrukturs'))->render();
        }

        return view('admin.kelola_instruktur', compact('instrukturs', 'profile', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profile = Auth::user();
        return view('admin.tambah.instruktur', compact('profile'));
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

        $instruktur = Instruktur::create($request->all());

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => 'Created a new instructor: ' . $instruktur->nama,
            'table_name' => 'instrukturs',
            'table_id' => $instruktur->id,
            'data' => json_encode($instruktur->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('instrukturs.index')->with('success', 'Instruktur created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instruktur $instruktur)
    {
        $profile = Auth::user();
        return view('admin.kelola_instruktur', compact('instruktur', 'profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instruktur $instruktur)
    {
        $profile = Auth::user();
        return view('admin.edit.instruktur', compact('instruktur', 'profile'));
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
            'email' => 'required|string|email|max:255' . $instruktur->id,
        ]);

        $instruktur->update($request->all());

        // Data log
        $logData = [
            'user_id' => Auth::id(),
            'action' => 'Update',
            'description' => 'Update an instructor: ' . $instruktur->nama,
            'table_name' => 'instrukturs',
            'table_id' => $instruktur->id,
            'data' => json_encode($instruktur->toArray()),
        ];

        // Simpan log
        Logs::create($logData);

        return redirect()->route('instrukturs.index')->with('success', 'Instruktur updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instruktur $instruktur)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Prepare log data
            $logData = [
                'user_id' => Auth::id(),
                'action' => 'delete',
                'description' => 'Deleted an instructor: ' . $instruktur->nama,
                'table_name' => 'instrukturs',
                'table_id' => $instruktur->id,
                'data' => json_encode($instruktur->toArray()),
            ];

            // Save the log
            Logs::create($logData);

            // Soft delete the instruktur
            $instruktur->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('instrukturs.index')->with('success', 'Instruktur deleted successfully.');
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error or handle it as necessary
            return redirect()
                ->route('instrukturs.index')
                ->with('error', 'Failed to delete Instruktur: ' . $e->getMessage());
        }
    }
}
