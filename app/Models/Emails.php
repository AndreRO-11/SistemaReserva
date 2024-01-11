<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emails extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation',
        'reservation,_status',
        'attendance',
        'attendance_confirmation'
    ];
}
