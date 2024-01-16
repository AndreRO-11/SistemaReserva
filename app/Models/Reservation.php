<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'client_id',
        'email_id',
        'place_id'
    ];

    protected $casts = [
        'status' => ReservationStatusEnum::class
    ];

    //1:1
    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    //N:M
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function dates()
    {
        return $this->belongsToMany(Date::class);
    }
    public function hours()
    {
        return $this->belongsToMany(Hour::class);
    }
}
