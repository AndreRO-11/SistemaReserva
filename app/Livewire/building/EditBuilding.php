<?php

namespace App\Livewire\Building;

use App\Models\Building;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditBuilding extends Component
{
    public $buildingId;

    #[Validate('required')]
    public $building = [
        'campus' => '',
        'address' => '',
        'building' => '',
        'city' => ''
    ];

    public function render()
    {
        $building = Building::find($this->buildingId);

        if ($building) {
            $this->building = [
                'campus' => $building->campus,
                'address' => $building->address,
                'building' => $building->building,
                'city' => $building->city
            ];
        }

        return view('livewire.building.edit-building');
    }

    public function update()
    {
        $this->validate();

        Building::find($this->buildingId)->update([
            'building' => $this->building['building'],
            'campus' => $this->building['campus'],
            'address' => $this->building['address'],
            'city' => $this->building['city'],
        ]);

        $this->reset();
        $this->dispatch('building-success');
    }

}
