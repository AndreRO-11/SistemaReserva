<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Date extends Model
{
    use HasFactory;
    protected $fillable = ['date'];

    //N:M
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Date::class);
    }
}
