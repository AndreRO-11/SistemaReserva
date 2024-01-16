<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;
    protected $fillable = ['hour'];

    //N:M
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }
}
