<?php

namespace App\Livewire\Building;

use App\Models\Building;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowBuilding extends Component
{
    public $buildings;

    #[On('building-success')]
    public function render()
    {
        $this->buildings = Building::where('active', true)->get();
        return view('livewire.building.show-building');
    }
}
