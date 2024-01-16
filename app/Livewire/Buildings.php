<?php

namespace App\Livewire;

use App\Models\Building;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Buildings extends Component
{
    public $editBuilding = null;
    public $buildings;

    #[Validate([
        'buildingEdit.campus' => 'required',
        'buildingEdit.address' => 'required',
        'buildingEdit.building' => 'required',
        'buildingEdit.city' => 'required'
    ])]
    public $buildingEdit = [
        'campus' => '',
        'address' => '',
        'building' => '',
        'city' => '',

    ];

    public function store()
    {
        $this->validate();
        Building::create([
            'campus' => $this->buildingEdit['campus'],
            'address' => $this->buildingEdit['address'],
            'building' => $this->buildingEdit['building'],
            'city' => $this->buildingEdit['city']
        ]);
        $this->reset();
    }

    public function edit($id)
    {
        $this->editBuilding = $id;
        $building = Building::find($id);

        $this->buildingEdit['campus'] = $building->campus;
        $this->buildingEdit['address'] = $building->address;
        $this->buildingEdit['building'] = $building->building;
        $this->buildingEdit['city'] = $building->city;
    }

    public function update()
    {
        $this->validate();
        Building::find($this->editBuilding)->update([
            'building' => $this->buildingEdit['building'],
            'campus' => $this->buildingEdit['campus'],
            'address' => $this->buildingEdit['address'],
            'city' => $this->buildingEdit['city'],
        ]);
        $this->reset();
        $this->editBuilding = null;
    }

    public function delete($id)
    {
        $building = Building::find($id);
        $building->update([
            'active' => false
        ]);
    }

    public function render()
    {
        $this->buildings = Building::where('active', true)->get();
        return view('livewire.buildings');
    }
}
