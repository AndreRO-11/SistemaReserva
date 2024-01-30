<?php

namespace App\Livewire;

use App\Models\Building;
use App\Models\Client;
use App\Models\Date;
use App\Models\Detail;
use App\Models\Email;
use App\Models\Hour;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Service;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Places extends Component
{
    public $editPlace = null, $dateFilter, $selectedDate, $reservationPlace, $localDate, $unreservedPlaces = [], $availableHours = [];
    public $places, $details, $buildings, $types, $seats, $services;
    public $selectedDetails = [], $selectedServices = [], $selectedHours = [], $selectedDates = [];

    // #[Validate([
    //     'placeEdit.code' => 'required',
    //     'placeEdit.capacity' => 'required',
    //     'placeEdit.floor' => 'required',
    //     'placeEdit.type_id' => 'required',
    //     'placeEdit.seat_id' => 'required',
    //     'placeEdit.building_id' => 'required',
    //     'reservationEdit.name' => 'required',
    //     'reservationEdit.email' => 'required|email',
    //     'reservationEdit.userType' => 'required',
    //     'reservationEdit.activity' => 'required',
    //     'reservationEdit.assistants' => 'required|numeric|min:1',
    //     'selectedDates' => 'required|array|min:1',
    //     'selectedHours' => 'required|array|min:1',
    // ])]
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
        $this->validate([
            'placeEdit.code' => 'required',
            'placeEdit.capacity' => 'required',
            'placeEdit.floor' => 'required',
            'placeEdit.type_id' => 'required',
            'placeEdit.seat_id' => 'required',
            'placeEdit.building_id' => 'required',
        ]);
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
        $this->validate([
            'placeEdit.code' => 'required',
            'placeEdit.capacity' => 'required',
            'placeEdit.floor' => 'required',
            'placeEdit.type_id' => 'required',
            'placeEdit.seat_id' => 'required',
            'placeEdit.building_id' => 'required',
        ]);
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
        $place = Place::with('building', 'details')->find($id);
        $this->reservationPlace = $place;
        $this->availableHours = $this->getAvailableHours();
    }

    public function bookSave()
    {
        $this->validate([
            'reservationEdit.name' => 'required',
            'reservationEdit.email' => 'required',
            'reservationEdit.userType' => 'required',
            'reservationEdit.activity' => 'required',
            'reservationEdit.assistants' => 'required|numeric|min:1',
            'selectedDates' => 'required',
            'selectedHours' => 'required',
        ]);

        $emailId = Email::create();

        $clientExists = Client::where('email', $this->reservationEdit['email'])->exists();
        if($clientExists)
        {
            $clientId = Client::where('email', $this->reservationEdit['email'])->first();
        } else {
            $clientId = Client::create([
                'name' => $this->reservationEdit['name'],
                'email' => $this->reservationEdit['email'],
                'user_type' => $this->reservationEdit['userType']
            ]);
        }

        $reservation = Reservation::create([
            'comment' => $this->reservationEdit['comment'],
            'activity' => $this->reservationEdit['activity'],
            'associated_project' => $this->reservationEdit['associated_project'],
            'assistants' => $this->reservationEdit['assistants'],
            'client_id' => $clientId->id,
            'email_id' => $emailId->id,
            'place_id' => $this->reservationPlace->id,
        ]);

        $reservation->services()->attach($this->selectedServices);

        if (!is_array($this->selectedDates)) {
            $this->selectedDates = [$this->selectedDates];
        }
        foreach ($this->selectedDates as $selectedDate) {
            $date = Date::firstOrCreate(['date' => $selectedDate]);
            $reservation->dates()->attach($date->id);
        }
        $reservation->hours()->attach($this->selectedHours);
        // $reservation->dates()->attach($this->selectedDates);
        $this->selectedDates = [];
        $this->selectedHours = [];
        $this->reset();
    }

    public function getAvailableHours()
    {
        if (!$this->selectedDates || !$this->editPlace) {
            return [];
        }
        $selectedPlace = Place::with(['reservations.dates', 'reservations.hours'])
        ->where('id', $this->editPlace)
        ->first();
        $reservedHours = $selectedPlace->reservations
        ->flatMap(function ($reservation) {
            return $reservation->hours->pluck('id')->toArray();
        })
        ->toArray();

        $allHours = Hour::all()->pluck('id')->toArray();
        $availableHours = array_diff($allHours, $reservedHours);

        $formattedHours = collect($availableHours)->map(function ($hourId) {
            $hour = Hour::find($hourId);
            return [
                'hour' => $hour,
                'formatted_hour' => Carbon::parse($hour->hour)->format('H:i'),
            ];
        });
        return $formattedHours;
    }

    public function render()
    {
        $this->selectedDates = Carbon::now()->format('Y-m-d');
        $this->buildings = Building::where('active', true)->get();
        $this->types = Type::all();
        $this->seats = Seat::all();
        $this->details = Detail::all();
        $this->services = Service::where('active', true)->get();

        $this->unreservedPlaces = Place::with(['details', 'building', 'reservations.dates', 'reservations.hours'])
        ->whereDoesntHave('reservations', function ($query) {
            $query->whereHas('dates', function ($subquery) {
                $subquery->where('date', $this->selectedDates);
            });
        })
        ->get();



        return view('livewire.places', [
            'places' => $this->unreservedPlaces,
            'details' => $this->details,
            'buildings' => $this->buildings,
            'types' => $this->types,
            'seats' => $this->seats,
            'services' => $this->services,
            'reservationPlace' => $this->reservationPlace
        ]);
    }
}
