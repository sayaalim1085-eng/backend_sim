<?php

namespace App\Http\Controllers;

use App\Models\Basecamp;
use Illuminate\Http\Request;

class BasecampController extends Controller
{
    public function index()
    {
        return response()->json(Basecamp::with('area')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id'       => 'required|exists:areas,id',
            'nama_basecamp' => 'required|string|max:250',
            'alamat'        => 'nullable|string',
        ]);

        $basecamp = Basecamp::create($validated);

        return response()->json([
            'message' => 'Data Basecamp berhasil ditambahkan',
            'data' => $basecamp
        ], 201);
    }

    public function show($id)
    {
        $basecamp = Basecamp::with('area')->findOrFail($id);
        return response()->json($basecamp, 200);
    }

    public function update(Request $request, $id)
    {
        $basecamp = Basecamp::findOrFail($id);

        $validated = $request->validate([
            'area_id'       => 'required|exists:areas,id',
            'nama_basecamp' => 'required|string|max:250',
            'alamat'        => 'nullable|string',
        ]);

        $basecamp->update($validated);

        return response()->json([
            'message' => 'Data Basecamp berhasil diperbarui',
            'data' => $basecamp
        ], 200);
    }

    public function destroy($id)
    {
        $basecamp = Basecamp::findOrFail($id);
        $basecamp->delete();

        return response()->json(['message' => 'Data Basecamp berhasil dihapus'], 200);
    }
}