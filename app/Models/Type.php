<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = ['type'];

    // 1:1
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
