<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'building',
        'active',
        'campus_id'
    ];

    // 1:N
    public function places() : HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function campus() : BelongsTo
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
