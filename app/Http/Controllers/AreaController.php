<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        return response()->json(Area::with('basecamps')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_area' => 'required|string|max:250',
            'kode_area' => 'required|string|max:50|unique:areas,kode_area',
        ]);

        $area = Area::create($validated);

        return response()->json([
            'message' => 'Data Area berhasil ditambahkan',
            'data' => $area
        ], 201);
    }

    public function show($id)
    {
        $area = Area::with('basecamps')->findOrFail($id);
        return response()->json($area, 200);
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $validated = $request->validate([
            'nama_area' => 'required|string|max:250',
            'kode_area' => 'required|string|max:50|unique:areas,kode_area,' . $id,
        ]);

        $area->update($validated);

        return response()->json([
            'message' => 'Data Area berhasil diperbarui',
            'data' => $area
        ], 200);
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return response()->json(['message' => 'Data Area berhasil dihapus'], 200);
    }
}