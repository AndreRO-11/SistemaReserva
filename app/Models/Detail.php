<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'detail'
    ];

    // N:M
    public function place(): BelongsToMany
    {
        return $this->belongsToMany(Place::class);
    }
}
