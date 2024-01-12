<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Detail;
use App\Models\Place;
use App\Models\Seat;
use App\Models\Type;
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
        'placeEdit.types_id' => 'required',
        'placeEdit.seats_id' => 'required',
        'placeEdit.buildings_id' => 'required',
    ])]
    public $placeEdit = [
        'code' => '',
        'capacity' => null,
        'floor' => '',
        'types_id' => '',
        'seats_id' => '',
        'buildings_id' => ''
    ];

    public function store()
    {
        $this->validate();
        $place = Place::create([
            'code' => $this->placeEdit['code'],
            'capacity' => $this->placeEdit['capacity'],
            'floor' => $this->placeEdit['floor'],
            'types_id' => $this->placeEdit['types_id'],
            'seats_id' => $this->placeEdit['seats_id'],
            'buildings_id' => $this->placeEdit['buildings_id'],
            'active' => true,
        ]);
        $place->placeDetails()->attach($this->selectedDetails);
        dd($place);
        $this->reset();
    }

    public function edit($id)
    {
        $this->editPlace = $id;
        $place = Place::find($id);


    }

    public function update()
    {
        $this->validate();
    }

    public function delete($id)
    {
        $place = Place::find();
        $place->update([
            'active' => false
        ]);
    }

    public function render()
    {
        $this->buildings = Building::where('active', true)->get();
        $this->types = Type::all();
        $this->seats = Seat::all();
        $this->details = Detail::all();


        $this->places = Place::where('places.active', true)
        ->join('place_detail','place_detail.places_id', 'places.id')
        ->join('buildings','buildings.id','places.buildings_id')
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
