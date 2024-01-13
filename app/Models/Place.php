<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'capacity',
        'floor',
        'active',
        'type_id',
        'building_id',
        'seat_id'
    ];

    // 1:1
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // N:M
    public function details()
    {
        return $this->belongsToMany(Detail::class);
    }
}
