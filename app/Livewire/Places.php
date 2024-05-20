<?php

namespace App\Livewire;

use App\Mail\ReservationEmail;
use App\Models\Building;
use App\Models\Campus;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Places extends Component
{
    public $editPlace = null, $dateFilter, $selectedDate, $reservationPlace, $localDate, $unreservedPlaces = [], $availableHours = [];
    public $places, $details, $buildings, $types, $seats, $services;
    public $selectedDetails = [], $selectedServices = [], $selectedHours = [], $selectedDates, $availablePlaces, $allHours;
    public $cityFilter = null, $campus, $cities;

    public $addPlace = false, $updatePlace = false, $bookPlace = false;

    public $place = [
        'code' => '',
        'capacity' => '',
        'floor' => '',
        'type_id' => '',
        'building_id' => '',
        'seat_id' => '',
        'user_id' => ''
    ];

    public $placeReservation = [
        'code' => '',
        'capacity' => '',
        'floor' => '',
        'type_id' => '',
        'building_id' => '',
        'seat_id' => '',
        'user_id' => ''
    ];

    public $reservation = [
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
            'place.code' => 'required',
            'place.capacity' => 'required',
            'place.floor' => 'required',
            'place.type_id' => 'required',
            'place.seat_id' => 'required',
            'place.building_id' => 'required',
        ]);

        $code = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->place['code'])));
        $user = Auth::user();

        $placeExists = Place::where('code', $code)
            ->where('building_id', $this->place['building_id'])
            ->exists();

        if (!$placeExists) {
            $placeStore = new Place();
            $placeStore->code = $code;
            $placeStore->capacity = $this->place['capacity'];
            $placeStore->floor = $this->place['floor'];
            $placeStore->type_id = $this->place['type_id'];
            $placeStore->seat_id = $this->place['seat_id'];
            $placeStore->building_id = $this->place['building_id'];
            $placeStore->user_id = $user->id;
            $placeStore->save();
            $placeStore->details()->attach($this->selectedDetails);

            $this->reset();
            $this->addPlace = false;
            $this->mount();
        } else {
            $this->addError('place.code', 'Espacio existente.');
        }
    }

    public function edit($id)
    {
        $this->editPlace = $id;
        $this->updatePlace = true;
        $place = Place::find($id);

        $this->place['code'] = $place->code;
        $this->place['capacity'] = $place->capacity;
        $this->place['floor'] = $place->floor;
        $this->place['type_id'] = $place->type_id;
        $this->place['seat_id'] = $place->seat_id;
        $this->place['building_id'] = $place->building_id;
        $this->selectedDetails = $place->details->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'place.code' => 'required',
            'place.capacity' => 'required',
            'place.floor' => 'required',
            'place.type_id' => 'required',
            'place.seat_id' => 'required',
            'place.building_id' => 'required',
        ]);

        $id = $this->editPlace;

        $user = Auth::user();

        $placeUpdate = Place::find($id);
        $placeUpdate->capacity = $this->place['capacity'];
        $placeUpdate->floor = $this->place['floor'];
        $placeUpdate->type_id = $this->place['type_id'];
        $placeUpdate->seat_id = $this->place['seat_id'];
        $placeUpdate->building_id = $this->place['building_id'];
        $placeUpdate->user_id = $user->id;
        $placeUpdate->details()->sync($this->selectedDetails);
        $placeUpdate->save();

        $this->reset();
        $this->mount();
    }

    public function delete($id)
    {
        $place = Place::find($id);
        $place->active = false;
        $place->save();

        $this->mount();
    }

    public function setActive($id)
    {
        $place = Place::find($id);
        $place->active = true;
        $place->save();

        $this->mount();
    }

    public function close()
    {
        $this->addPlace = false;
        $this->updatePlace = false;
        $this->bookPlace = false;
        $this->editPlace = null;

        $this->reset();
        $this->mount();
    }

    public function book($id)
    {
        $this->validate([
            'selectedDates' => 'required|date|after_or_equal:tomorrow'
        ]);


        $this->editPlace = $id;
        $this->bookPlace = true;

        $place = Place::with('building', 'details')->find($id);

        $this->placeReservation = $place;
        $this->availableHours = $this->getAvailableHours($id);
    }

    public function bookSave()
    {
        $this->validate([
            'reservation.name' => 'required',
            'reservation.email' => 'required|email|ends_with:@ubiobio.cl,.ubiobio.cl',
            'reservation.userType' => 'required',
            'reservation.activity' => 'required',
            'reservation.assistants' => 'required|numeric|min:1',
            'selectedDates' => 'required|date|after_or_equal:tomorrow',
            'selectedHours' => 'required|array',
        ]);

        $this->place = Place::where('id', $this->editPlace)->first();
        $placeAssistants = $this->reservation['assistants'];

        if ($placeAssistants > $this->place->capacity) {
            $this->addError('reservation.assistants', 'La cantidad de asistentes excede la capacidad del lugar.');
        } else {
            $emailId = Email::create();

            $clientExists = Client::where('email', $this->reservation['email'])->exists();
            if ($clientExists) {
                $clientId = Client::where('email', $this->reservation['email'])->first();
            } else {
                $clientId = Client::create([
                    'name' => $this->reservation['name'],
                    'email' => $this->reservation['email'],
                    'user_type' => $this->reservation['userType']
                ]);
            }

            $reservationBook = new Reservation();
            $reservationBook->comment = $this->reservation['comment'];
            $reservationBook->activity = $this->reservation['activity'];
            $reservationBook->associated_project = $this->reservation['associated_project'];
            $reservationBook->assistants = $this->reservation['assistants'];
            $reservationBook->client_id = $clientId->id;
            $reservationBook->email_id = $emailId->id;
            $reservationBook->place_id = $this->place->id;
            $reservationBook->save();

            $selectedDatesArray = is_array($this->selectedDates) ? $this->selectedDates : [$this->selectedDates];
            $this->availableHours = $this->getAvailableHours($this->editPlace);

            $reservationBook->services()->attach($this->selectedServices);

            if (!is_array($this->selectedDates)) {
                $this->selectedDates = [$this->selectedDates];
            }
            foreach ($this->selectedDates as $selectedDate) {
                $date = Date::firstOrCreate(['date' => $selectedDate]);
                $reservationBook->dates()->attach($date->id);
            }
            $reservationBook->hours()->attach($this->selectedHours);
            $this->selectedDates = [];
            $this->selectedHours = [];

            // Email
            Mail::to($clientId->email)->send(new ReservationEmail($reservationBook->id));

            $this->reset();
            $this->selectedDates = Carbon::tomorrow()->toDateString();
            $this->mount();
        }

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
        if (auth()->check()) {
            $placesQuery = Place::with(['details', 'building', 'reservations.dates', 'reservations.hours']);
            $user = auth()->user();
            $user = User::find($user->id);
            $this->cityFilter = $user->campus->city;
            $this->campus = $user->campus->campus;

            $placesQuery->whereHas('building', function ($query) {
                $query->whereHas('campus', function ($subQuery) {
                    $subQuery->where('city', $this->cityFilter)
                        ->where('campus', $this->campus);
                });
            });
        } else {
            $placesQuery = Place::where('active', true)
                ->with(['details', 'building', 'reservations.dates', 'reservations.hours']);
            if (!empty($this->cityFilter)) {
                $placesQuery->whereHas('building.campus', function ($query) {
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
            // VISTA PARA USUARIOS
            $user = Auth::user();
            $user = User::find($user->id);
            $this->cityFilter = $user->campus->city;
            $this->campus = $user->campus->campus;

            $this->buildings = Building::whereHas('campus', function ($query) {
                $query->where('city', $this->cityFilter)
                    ->where('campus', $this->campus);
            })->get();
        } else {
            // VISTA PARA VISITAS
            $this->buildings = Building::where('active', true)->get();
        }

        $this->types = Type::all();
        $this->seats = Seat::all();
        $this->details = Detail::all();
        $this->services = Service::where('active', true)->get();
        $this->cities = Campus::select('city')->distinct()->pluck('city');

        return view('livewire.places', [
            'places' => $this->unreservedPlaces,
            'details' => $this->details,
            'buildings' => $this->buildings,
            'types' => $this->types,
            'seats' => $this->seats,
            'services' => $this->services,
            'reservationPlace' => $this->reservationPlace,
            'cities' => $this->cities
        ]);
    }
}
