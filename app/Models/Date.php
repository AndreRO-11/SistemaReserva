<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    protected $fillable = ['date'];

    //N:M
    public function reservations()
    {
        return $this->belongsToMany(Date::class);
    }
}
