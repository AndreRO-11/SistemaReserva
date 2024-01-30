<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'capacity',
        'floor',
        'active',
        'type_id',
        'building_id',
        'seat_id'
    ];

    // 1:1
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    //1:N
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // N:M
    public function details(): BelongsToMany
    {
        return $this->belongsToMany(Detail::class);
    }
}
