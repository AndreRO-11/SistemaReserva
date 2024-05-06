<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Campus;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Buildings extends Component
{
    public $editBuilding = null;
    public $buildings, $campus_id;
    public $campus, $user;

    public $buildingStore = [
        'building' => '',
        'campus_id' => '',
    ];

    public $buildingEdit = [
        'building' => '',
        'campus_id' => '',
    ];

    public function store()
    {
        $this->validate(([
            'buildingStore.building' => 'required',
            //'buildingStore.campus_id' => 'required',
        ]));

        $building = Building::where('campus_id', $this->user->campus_id)->where('building', $this->buildingStore['building'])->exists();

        if (!$building) {
            $building = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->buildingStore['building'])));

            $buildingStore = new Building();
            $buildingStore->building = $building;
            $buildingStore->campus_id = $this->user->campus_id;
            $buildingStore->save();

            $this->reset();
        } else {
            $this->addError('buildingStore.building', 'Edificio ya existe.');
        }
    }

    public function edit($id)
    {
        $this->editBuilding = $id;
        $building = Building::find($id);

        $this->buildingEdit['building'] = $building->building;
        $this->buildingEdit['campus_id'] = $building->campus->id;
    }

    public function update()
    {
        $this->validate(([
            'buildingEdit.building' => 'required',
            'buildingEdit.campus_id' => 'required',
        ]));

        $building = Building::where('campus_id', $this->user->campus_id)->where('building', $this->buildingStore['building'])->exists();

        if (!$building) {
            $id = $this->editBuilding;
            $building = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->buildingEdit['building'])));

            $buildingUpdate = Building::find($id);
            $buildingUpdate->building = $building;
            $buildingUpdate->campus_id = $this->user->campus_id;
            $buildingUpdate->save();

            $this->reset();
            $this->editBuilding = null;
        } else {
            $this->addError('buildingEdit.building', 'Edificio ya existe.');
        }
    }

    public function close()
    {
        $this->editBuilding = null;
        $this->reset();
    }

    public function delete($id)
    {
        $building = Building::find($id);
        $building->active = false;
        $building->save();
    }

    public function setActive($id)
    {
        $building = Building::find($id);
        $building->active = true;
        $building->save();
    }

    public function render()
    {
        $this->user = Auth::user();

        $this->buildings = Building::where('campus_id', $this->user->campus_id)->get();
        $this->campus = Campus::where('id', $this->user->campus_id)->first();

        return view('livewire.buildings', [
            'buildings' => $this->buildings,
            'campus' => $this->campus,
        ]);
    }
}
