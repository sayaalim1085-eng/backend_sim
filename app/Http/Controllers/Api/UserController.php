<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data'   => User::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password ?? 'password123'),
            'role'     => $request->role ?? 'Staff',
            'status'   => $request->status ?? 'Aktif',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil ditambahkan',
            'data'    => $user
        ], 201);
    }

    // 1. TAMBAHKAN METHOD UPDATE UNTUK FITUR EDIT USER
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role ?? $user->role,
            'status' => $request->status ?? $user->status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil diperbarui',
            'data'    => $user
        ], 200);
    }

    // 2. TAMBAHKAN METHOD DESTROY UNTUK FITUR HAPUS USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'User berhasil dihapus'
        ], 200);
    }
}