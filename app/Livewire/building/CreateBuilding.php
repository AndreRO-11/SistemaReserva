<?php

namespace App\Livewire\Building;

use App\Models\Building;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateBuilding extends Component
{
    #[Validate('required')]
    public $campus, $address, $building, $city;

    public $buildings;

    public function save()
    {
        $this->validate();

        Building::create([
            'campus' => $this->campus,
            'address' => $this->address,
            'building' => $this->building,
            'city' => $this->city,
            'active' => true
        ]);

        $this->reset();
        // return redirect()->to('show-building')->with('status', 'Edificio agreagado correctamente.');
        $this->dispatch('building-success');
    }

    public function render()
    {
        return view('livewire.building.create-building');
    }
}
