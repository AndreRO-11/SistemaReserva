<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Campus;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Buildings extends Component
{
    public $editBuilding = null;
    public $campus_id;
    public $campus, $user;
    public $activeFilter = true;

    use WithPagination;

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
            'buildingStore.building' => 'required|unique:buildings,building',
        ]));

        $building = Building::where('campus_id', $this->user->campus_id)->where('building', $this->buildingStore['building'])->exists();

        if (!$building) {
            $building = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->buildingStore['building'])));

            $buildingStore = new Building();
            $buildingStore->building = $building;
            $buildingStore->campus_id = $this->user->campus_id;
            $buildingStore->save();

            $this->reset();
            $this->dispatch('success', 'Edificio agregado correctamente.');
        } else {
            $this->addError('buildingStore.building', 'Edificio ya existe.');
            $this->dispatch('failed', 'Error en datos.');
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
            'buildingEdit.building' => 'required|unique:buildings,building'
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
            $this->dispatch('success', 'Edificio actualizado correctamente.');
        } else {
            $this->addError('buildingEdit.building', 'Edificio ya existe.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function close()
    {
        $this->editBuilding = null;
        $this->reset();
        $this->dispatch('warning', 'No se han guardado los cambios.');
    }

    public function delete($id)
    {
        $building = Building::find($id);
        $building->active = false;
        $building->save();
        $this->dispatch('warning', 'Edificio desactivado.');
    }

    public function setActive($id)
    {
        $building = Building::find($id);
        $building->active = true;
        $building->save();
        $this->dispatch('success', 'Edificio activado.');
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);
        $this->user = Auth::user();

        $buildings = Building::where('campus_id', $this->user->campus_id)->orderBy('active', 'desc');
        $this->campus = Campus::where('id', $this->user->campus_id)->first();

        if ($this->activeFilter) {
            $buildings = $buildings->where('active', true);
        }

        $buildings = $buildings->paginate(10);

        return view('livewire.buildings', [
            'buildings' => $buildings,
            'campus' => $this->campus,
        ]);
    }
}
