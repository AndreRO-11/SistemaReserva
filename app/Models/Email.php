<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation',
        'reservation,_status',
        'attendance',
        'attendance_confirmation'
    ];

    //1:1
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
