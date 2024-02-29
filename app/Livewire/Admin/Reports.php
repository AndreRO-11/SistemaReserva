<?php

namespace App\Livewire\Admin;

use App\Models\Place;
use Carbon\Carbon;
use Livewire\Component;

class Reports extends Component
{
    public $option;
    public $dateFrom, $dateTo;
    public $places;

    public function reportPlace()
    {
        $this->dateFrom = Carbon::today();
        $this->dateTo = Carbon::today();
        $this->option = 'place';
        $this->places = Place::with(['building', 'details'])->where('places.active', true)->get();
    }

    public function reportDate()
    {
        $this->option = 'date';
    }

    public function render()
    {
        return view('livewire.admin.reports');
    }
}
