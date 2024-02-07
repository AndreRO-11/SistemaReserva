<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'service',
        'description',
        'active'
    ];

    //N:M
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class);
    }
}