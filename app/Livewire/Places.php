<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Detail;
use App\Models\Place;
use App\Models\Seat;
use App\Models\Type;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Places extends Component
{
    public $editPlace = null;
    public $places, $details, $buildings, $types, $seats;
    public $selectedDetails = [];

    #[Rule([
        'placeEdit.code' => 'required',
        'placeEdit.capacity' => 'required',
        'placeEdit.floor' => 'required',
        'placeEdit.type_id' => 'required',
        'placeEdit.seat_id' => 'required',
        'placeEdit.building_id' => 'required',
    ])]
    public $placeEdit = [
        'code' => '',
        'capacity' => '',
        'floor' => '',
        'type_id' => '',
        'seat_id' => '',
        'building_id' => ''
    ];

    public function store()
    {
        $this->validate();
        $place = Place::create([
            'code' => $this->placeEdit['code'],
            'capacity' => $this->placeEdit['capacity'],
            'floor' => $this->placeEdit['floor'],
            'type_id' => $this->placeEdit['type_id'],
            'seat_id' => $this->placeEdit['seat_id'],
            'building_id' => $this->placeEdit['building_id'],
            'active' => true,
        ]);
        $place->details()->attach($this->selectedDetails);
        $this->reset();
        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $this->editPlace = $id;
        $place = Place::find($id);
        $this->placeEdit['code'] = $place->code;
        $this->placeEdit['capacity'] = $place->capacity;
        $this->placeEdit['floor'] = $place->floor;
        $this->placeEdit['type_id'] = $place->type_id;
        $this->placeEdit['seat_id'] = $place->seat_id;
        $this->placeEdit['building_id'] = $place->building_id;
        $this->selectedDetails = $place->details->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate();
        $place = Place::find($this->editPlace);
        $place->update([
            'code' => $this->placeEdit['code'],
            'capacity' => $this->placeEdit['capacity'],
            'floor' => $this->placeEdit['floor'],
            'type_id' => $this->placeEdit['type_id'],
            'seat_id' => $this->placeEdit['seat_id'],
            'building_id' => $this->placeEdit['building_id']
        ]);
        $place->details()->sync($this->selectedDetails);
        $this->reset();
        $this->editPlace = null;
        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        $place = Place::find($id);
        $place->update([
            'active' => false
        ]);
    }

    #[On('reset-modal')]
    public function close()
    {
        $this->reset();
    }

    public function render()
    {
        $this->buildings = Building::where('active', true)->get();
        $this->types = Type::all();
        $this->seats = Seat::all();
        $this->details = Detail::all();


        $this->places = Place::where('places.active', true)
            ->with('details', 'building')
            ->get();

        return view('livewire.places', [
            'details' => $this->details,
            'places' => $this->places,
            'buildings' => $this->buildings,
            'types' => $this->types,
            'seats' => $this->seats
        ]);
    }
}
