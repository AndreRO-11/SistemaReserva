<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;

class Reservations extends Component
{
    public $reservations;

    public function render()
    {
        $this->reservations = Reservation::with('places', 'users', 'emails')->get();
        return view('livewire.reservations');
    }
}
