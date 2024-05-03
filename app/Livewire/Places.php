<?php

namespace App\Livewire;

use App\Mail\ReservationEmail;
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
use App\Models\User;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class Places extends Component
{
    public $editPlace = null, $dateFilter, $selectedDate, $reservationPlace, $localDate, $unreservedPlaces = [], $availableHours = [];
    public $places, $details, $buildings, $types, $seats, $services, $place;
    public $selectedDetails = [], $selectedServices = [], $selectedHours = [], $selectedDates, $availablePlaces, $allHours;
    public $cityFilter = null, $campus;

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
            'building_id' => $this->placeEdit['building_id'],
            'user_id' => auth()->user()->id,
        ]);
        $place->details()->attach($this->selectedDetails);
        $this->reset();
        $this->actualizarUnreservedPlaces();
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
        $this->actualizarUnreservedPlaces();
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
        $this->actualizarUnreservedPlaces();
    }

    public function delete($id)
    {
        $place = Place::find($id);
        $place->update([
            'active' => false
        ]);
        $this->actualizarUnreservedPlaces();
    }

    public function setActive($id)
    {
        $place = Place::find($id);
        $place->update([
            'active' => true
        ]);
        $this->actualizarUnreservedPlaces();
    }

    public function book($id)
    {
        $this->validate([
            'selectedDates' => 'required|date|after_or_equal:tomorrow',
        ]);

        $this->editPlace = $id;
        $place = Place::with('building', 'details')->find($id);
        $this->reservationPlace = $place;
        $this->availableHours = $this->getAvailableHours($id);
        $this->actualizarUnreservedPlaces();
    }

    public function bookSave()
    {
        $this->validate([
            'reservationEdit.name' => 'required',
            'reservationEdit.email' => 'required|email|ends_with:@ubiobio.cl,.ubiobio.cl',
            'reservationEdit.userType' => 'required',
            'reservationEdit.activity' => 'required',
            'reservationEdit.assistants' => 'required|numeric|min:1',
            'selectedDates' => 'required|date|after_or_equal:tomorrow',
            'selectedHours' => 'required|array',
        ]);

        $this->place = Place::where('id', $this->editPlace)->first();
        $placeAssistants = $this->reservationEdit['assistants'];

        if ($placeAssistants > $this->place->capacity) {
            $this->addError('assistants', 'La cantidad de asistentes excede la capacidad del lugar.');
        } else {
            $emailId = Email::create();

            $clientExists = Client::where('email', $this->reservationEdit['email'])->exists();
            if ($clientExists) {
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
                'place_id' => $this->reservationPlace->id
            ]);

            $selectedDatesArray = is_array($this->selectedDates) ? $this->selectedDates : [$this->selectedDates];
            $this->availableHours = $this->getAvailableHours($this->editPlace);

            $reservation->services()->attach($this->selectedServices);

            if (!is_array($this->selectedDates)) {
                $this->selectedDates = [$this->selectedDates];
            }
            foreach ($this->selectedDates as $selectedDate) {
                $date = Date::firstOrCreate(['date' => $selectedDate]);
                $reservation->dates()->attach($date->id);
            }
            $reservation->hours()->attach($this->selectedHours);
            $this->selectedDates = [];
            $this->selectedHours = [];

            // Email
            Mail::to($clientId->email)->send(new ReservationEmail($reservation->id));

            $this->reset();
            $this->selectedDates = Carbon::tomorrow()->toDateString();
        }
        $this->actualizarUnreservedPlaces();
    }

    public function getAvailableHours($placeId)
    {
        $selectedDate = Carbon::parse($this->selectedDates)->toDateString();

        $reservations = Reservation::whereHas('dates', function ($query) use ($selectedDate) {
            $query->whereDate('date', $selectedDate);
        })->where('place_id', $placeId)->get();

        $reservedHoursIds = $reservations->flatMap(function ($reservation) {
            return $reservation->hours->pluck('id');
        });

        $allHours = Hour::all();

        $unreservedHours = $allHours->whereNotIn('id', $reservedHoursIds);

        $formattedHours = $unreservedHours->map(function ($hour) {
            return [
                'hour' => $hour,
                'formatted_hour' => Carbon::parse($hour->hour)->format('H:i'),
            ];
        });

        return $formattedHours;
    }

    public function actualizarUnreservedPlaces()
    {
        // $this->unreservedPlaces = Place::where('active', true)
        //     ->with(['details', 'building', 'reservations.dates', 'reservations.hours'])
        //     ->get();
        // foreach ($this->unreservedPlaces as $place) {
        //     $availableHours = $this->getAvailableHours($place->id);
        //     $place->availableHours = $availableHours->toArray();
        // }

        // $placesQuery = Place::where('active', true)
        //     ->with(['details', 'building', 'reservations.dates', 'reservations.hours', 'building.campus']);

        // if (auth()->check()) {
        //     $user = auth()->user();
        //     $user = User::with('campus')->first();
        //     //dd($user);
        //     $this->cityFilter = $user->campus->city;
        //     $this->campus = $user->campus->campus;
        //     $placesQuery->whereHas('building', function ($query) {
        //         $query->where('city', $this->cityFilter)
        //             ->where('campus', $this->campus);
        //     });
        // } else {
        //     if ($this->cityFilter === 'CHILLAN' || $this->cityFilter === 'CONCEPCION') {
        //         $placesQuery->whereHas('building', function ($query) {
        //             $query->where('city', $this->cityFilter);
        //         });
        //     } else {
        //         $this->cityFilter = null;
        //     }
        // }


        // $placesQuery = Place::where('active', true)
        //     ->with(['details', 'building', 'reservations.dates', 'reservations.hours']);

        if (auth()->check()) {
            $placesQuery = Place::with(['details', 'building', 'reservations.dates', 'reservations.hours']);
            $user = auth()->user();
            $user = User::find($user->id);
            $this->cityFilter = $user->campus->city;
            $this->campus = $user->campus->campus;
            // $placesQuery->whereHas('building.campus', function ($query) {
            //     $query->where('city', $this->cityFilter)
            //         ->where('campus', $this->campus);
            // });
            $placesQuery->whereHas('building', function ($query) {
                $query->whereHas('campus', function ($subQuery) {
                    $subQuery->where('city', $this->cityFilter)
                        ->where('campus', $this->campus);
                });
            });
        } else {
            $placesQuery = Place::where('active', true)
                ->with(['details', 'building', 'reservations.dates', 'reservations.hours']);
            if ($this->cityFilter === 'CHILLAN' || $this->cityFilter === 'CONCEPCION') {
                $placesQuery->whereHas('building', function ($query) {
                    $query->where('city', $this->cityFilter);
                });
            } else {
                $this->cityFilter = null;
            }
        }


        $this->unreservedPlaces = $placesQuery->get();

        foreach ($this->unreservedPlaces as $place) {
            $availableHours = $this->getAvailableHours($place->id);
            $place->availableHours = $availableHours->toArray();
        }
    }

    public function mount()
    {
        $this->selectedDates = Carbon::tomorrow()->toDateString();
        $this->actualizarUnreservedPlaces();
    }

    public function render()
    {
        if (auth()->check()) {
            $user = User::with('campus')->first();
            $this->cityFilter = $user->campus->city;
            $this->campus = $user->campus->campus;
            // $this->buildings = Building::where('active', true)
            //     ->whereHas('campus', function ($query) {
            //         $query->where('city', $this->cityFilter)
            //             ->where('campus', $this->campus)
            //             ->get();
            //     });

            $this->buildings = Building::whereHas('campus', function ($query) {
                $query->where('city', $this->cityFilter)
                    ->where('campus', $this->campus);
            })//->where('active', true)
                ->get();
        } else {
            $this->buildings = Building::where('active', true)->get();
        }

        $this->types = Type::all();
        $this->seats = Seat::all();
        $this->details = Detail::all();
        $this->services = Service::where('active', true)->get();

        return view('livewire.places', [
            'places' => $this->unreservedPlaces,
            'details' => $this->details,
            'buildings' => $this->buildings,
            'types' => $this->types,
            'seats' => $this->seats,
            'services' => $this->services,
            'reservationPlace' => $this->reservationPlace,
        ]);
    }
}
