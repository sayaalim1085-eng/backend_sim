<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basecamp extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Basecamp terhubung ke 1 Area
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}