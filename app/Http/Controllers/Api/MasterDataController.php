<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'name' => 'Barang A', 'category' => 'Elektronik'],
                ['id' => 2, 'name' => 'Barang B', 'category' => 'Mebel'],
            ]
        ]);
    }
}