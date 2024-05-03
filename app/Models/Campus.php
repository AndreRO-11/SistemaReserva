<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus',
        'address',
        'city',
        'active'
    ];

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
