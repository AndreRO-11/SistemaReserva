<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'air_conditioning',
        'disabled_access',
        'projector',
        'curtain',
        'interactive_screen',
        'board',
        'amplification',
        'computer'
    ];
}
