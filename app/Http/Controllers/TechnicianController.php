<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource. (GET /api/technicians)
     */
    public function index()
    {
        // Mengambil data teknisi beserta akun user terkait
        $technicians = Technician::with('user')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data teknisi berhasil diambil',
            'data'    => $technicians
        ], 200);
    }

    /**
     * Store a newly created resource in storage. (POST /api/technicians)
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|unique:users,email',
            'password'       => 'required|string|min:6',
            'phone_number'   => 'nullable|string',
            'specialization' => 'nullable|string',
        ]);

        // 2. Transaksi database (simpan ke tabel users dan technicians sekaligus)
        $technician = DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return Technician::create([
                'user_id'        => $user->id,
                'phone_number'   => $request->phone_number,
                'specialization' => $request->specialization,
                'status'         => 'active',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Data teknisi berhasil ditambahkan',
            'data'    => $technician->load('user')
        ], 201);
    }

    /**
     * Display the specified resource. (GET /api/technicians/{id})
     */
    public function show($id)
    {
        $technician = Technician::with('user')->find($id);

        if (!$technician) {
            return response()->json(['message' => 'Data teknisi tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $technician
        ], 200);
    }

    /**
     * Update the specified resource in storage. (PUT/PATCH /api/technicians/{id})
     */
    public function update(Request $request, $id)
    {
        $technician = Technician::with('user')->find($id);

        if (!$technician) {
            return response()->json(['message' => 'Data teknisi tidak ditemukan'], 404);
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|unique:users,email,' . $technician->user_id,
            'phone_number'   => 'nullable|string',
            'specialization' => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($request, $technician) {
            // Update tabel users
            $technician->user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Jika ada perubahan password
            if ($request->filled('password')) {
                $technician->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Update tabel technicians
            $technician->update([
                'phone_number'   => $request->phone_number,
                'specialization' => $request->specialization,
                'status'         => $request->status,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Data teknisi berhasil diperbarui',
            'data'    => $technician->fresh('user')
        ], 200);
    }

    /**
     * Remove the specified resource from storage. (DELETE /api/technicians/{id})
     */
    public function destroy($id)
    {
        $technician = Technician::find($id);

        if (!$technician) {
            return response()->json(['message' => 'Data teknisi tidak ditemukan'], 404);
        }

        // Hapus user (akan otomatis menghapus record di technicians karena onDelete cascade)
        $technician->user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data teknisi berhasil dihapus'
        ], 200);
    }
}