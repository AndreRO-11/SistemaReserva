<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'capaciy',
        'floor',
        'active',
        'types_id',
        'buildings_id',
        'seats_id'
    ];

    // 1:1
    public function building()
    {
        return $this->belongsToTo(Building::class);
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
    public function placeDetails()
    {
        return $this->belongsToMany(Detail::class);
    }
}
