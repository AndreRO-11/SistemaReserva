<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation.email_id');
    }
}
