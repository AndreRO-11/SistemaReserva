<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'detail'
    ];

    // N:M
    public function place()
    {
        return $this->belongsToMany(Place::class);
    }
}
