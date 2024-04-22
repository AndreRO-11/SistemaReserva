<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'used',
        'comment',
        'activity',
        'associated_project',
        'assistants',
        'status',
        'active',
        'client_id',
        'email_id',
        'place_id'
    ];

    protected $casts = [
        'status' => ReservationStatusEnum::class
    ];

    //1:1
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //N:M
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
    public function dates(): BelongsToMany
    {
        return $this->belongsToMany(Date:: class)->withTimestamps();
    }
    public function hours(): BelongsToMany
    {
        return $this->belongsToMany(Hour::class);
    }
}
