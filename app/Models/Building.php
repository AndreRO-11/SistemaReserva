<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus',
        'address',
        'building',
        'city',
        'active'
    ];

    // 1:N
    public function places()
    {
        return $this->belongsToMany(Place::class);
    }
}
