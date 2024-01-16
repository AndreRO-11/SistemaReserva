<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['seat'];

    // 1:N
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
