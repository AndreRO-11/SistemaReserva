<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Client;
use App\Models\Detail;
use App\Models\Email;
use App\Models\Hour;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Service;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Places extends Component
{
    public $editPlace = null, $dateFilter, $selectedDate, $reservationPlace, $localDate, $unreservedPlaces = [];
    public $places, $details, $buildings, $types, $seats, $services;
    public $selectedDetails = [], $selectedServices = [], $selectedHours = [];

    #[Validate([
        'placeEdit.code' => 'required',
        'placeEdit.capacity' => 'required',
        'placeEdit.floor' => 'required',
        'placeEdit.type_id' => 'required',
        'placeEdit.seat_id' => 'required',
        'placeEdit.building_id' => 'required'
    ])]
    public $placeEdit = [
        'code' => '',
        'capacity' => '',
        'floor' => '',
        'type_id' => '',
        'seat_id' => '',
        'building_id' => ''
    ];

    public $reservationEdit = [
        'name' => '',
        'email' => '',
        'userType' => '',
        'activity' => '',
        'assistants' => '',
        'associated_project' => '',
        'comment' => '',
    ];

    public function store()
    {
        $this->validate();
        $code = Str::upper($this->placeEdit['code']);
        $place = Place::create([
            'code' => $code,
            'capacity' => $this->placeEdit['capacity'],
            'floor' => $this->placeEdit['floor'],
            'type_id' => $this->placeEdit['type_id'],
            'seat_id' => $this->placeEdit['seat_id'],
            'building_id' => $this->placeEdit['building_id']
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
        $code = Str::upper($this->placeEdit['code']);
        $place->update([
            'code' => $code,
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

    public function book($id)
    {
        $this->editPlace = $id;
        $place = Place::with('building', 'details')->find($id)->first();
        $this->reservationPlace = $place;
    }

    public function bookSave()
    {
        $validated = Validator::make([
            'name' => $this->reservationEdit['name'],
            'email' => $this->reservationEdit['email'],
            'userType' => $this->reservationEdit['userType'],
            'activity' => $this->reservationEdit['activity'],
            'assistants' => $this->reservationEdit['assistants'],
            'dateSelected' => $this->dateSelected,
            'selectedHours' => $this->selectedHours
        ],
        [
            'reservationEdit.name' => 'required',
            'reservationEdit.email' => 'required',
            'reservationEdit.userType' => 'required',
            'reservationEdit.activity' => 'required',
            'reservationEdit.assistants' => 'required',
            'dateSelected' => 'required',
            'selectedHours' => 'required'
        ])->validate();

        $emailId = Email::create();

        $clientId = Client::find($this->reservationEdit['email']);
        if(!$clientId)
        {
            $clientId = Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_type' => $validated['userType']
            ]);
        }

        $reservation = Reservation::create([
            'comment' => $validated['comment'],
            'activity' => $validated['activity'],
            'associated_project' => $validated['associated_project'],
            'assistants' => $validated['assistants'],
            'client_id' => $clientId,
            'email_id' => $emailId,
            'place_id' => $this->reservationPlace->place->id,
        ]);
        // Se cambia el public $dateFilter a arreglo []
        // $dates = $this->dateFilter;
        // foreach ($dateFilter as $date) {
        //     $dates->date = Carbon::parse($date->date)->format('d/m/Y');
        // }
        $date = Carbon::parse($this->selectedDate)->format('Y-m-d');

        $reservation->services()->attach($this->selectedServices);
        $reservation->dates()->attach($date);
        $reservation->hours()->attach($this->selectedHours);
        $this->reset();
        $this->dispatch('close-modal');
    }

    #[On('reset-modal')]
    public function close()
    {
        $this->reset();
    }

    public function getAvailableHours()
    {
        if (!$this->selectedDate || !$this->editPlace) {
            return [];
        }

        $selectedPlace = Place::with(['reservations.dates', 'reservations.hours'])
        ->where('id', $this->editPlace)
        ->first();

        $reservedHours = $selectedPlace->reservations
        ->where('dates.date', $this->selectedDate)
        ->pluck('hours');

        $allHours = Hour::all();

        $availableHours = $allHours->diff($reservedHours)->values();

        foreach ($availableHours as $hour) {
            $hourFormat = Carbon::parse($hour)->format('H:i');
            $formattedHours[] = ['id' => $hour->id, 'formatted_hour' => $hourFormat];
        }
        dd($hourFormat);

        return $formattedHours;
    }

    public function render()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->buildings = Building::where('active', true)->get();
        $this->types = Type::all();
        $this->seats = Seat::all();
        $this->details = Detail::all();
        $this->services = Service::where('active', true)->get();

        $this->unreservedPlaces = Place::with(['details', 'building', 'reservations.dates', 'reservations.hours'])
        ->whereDoesntHave('reservations', function ($query) {
            $query->whereHas('dates', function ($subquery) {
                $subquery->where('date', $this->selectedDate);
            });
        })
        ->get();

        // $availableHours = $this->getAvailableHours();

        return view('livewire.places', [
            'places' => $this->unreservedPlaces,
            'details' => $this->details,
            'buildings' => $this->buildings,
            'types' => $this->types,
            'seats' => $this->seats,
            'services' => $this->services,
            'reservationPlace' => $this->reservationPlace,
            // 'availableHours' => $availableHours,
        ]);
    }
}
