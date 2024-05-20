<?php

namespace App\Livewire;

use App\Enums\ReservationStatusEnum;
use App\Mail\ReservationStatusEmail;
use App\Models\Campus;
use App\Models\Reservation;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Reservations extends Component
{
    public $reservations, $selectedHours = [], $allServices = [], $showServices = [];
    public $placeEdit, $clientEdit, $hours = [], $reservationId, $comment;

    public $reservation ,$place, $clientForm, $selectedServices = [], $campuses;
    public $dataReservation = false ,$showReservation = false, $editReservation = false;

    // FILTROS
    public $statusFilter = null, $campusFilter, $placeFilter = null, $uniquePlaces, $dateFilter = null;

    use WithPagination;

    public $reservationEdit = [
        'activity' => '',
        'assistants' => '',
        'associated_project' => '',
        'comment' => '',
        'selectedServices' => []
    ];

    public $reservationForm = [
        'activity' => '',
        'assistants' => '',
        'associated_project' => '',
        'comment' => ''
    ];

    public function show($id)
    {
        $this->dataReservation();
        $this->showReservation();

        $this->reservation = Reservation::with('place', 'client', 'hours', 'services')->find($id);

        $this->hours = $this->reservation->hours;
        $this->selectedServices = $this->reservation->services;
    }

    public function edit($id)
    {
        $this->showReservation();
        $this->editReservation();

        $this->reservation = Reservation::with('place', 'client', 'hours', 'services')->find($id);
        $this->reservationId = $id;

        $this->reservationForm['activity'] = $this->reservation->activity;
        $this->reservationForm['assistants'] = $this->reservation->assistants;
        $this->reservationForm['associated_project'] = $this->reservation->associated_project;
        $this->reservationForm['comment'] = $this->reservation->comment;

        $this->hours = $this->reservation->hours;
        $this->selectedServices = $this->reservation->services->pluck('id')->toArray();

        $this->allServices = Service::where('active', true)->get();
    }

    public function update()
    {
        $this->validate([
            'reservationForm.activity' => 'required',
            'reservationForm.assistants' => 'required',
        ]);

        $id = $this->reservationId;

        $reservation = Reservation::find($id);

        $reservation->comment = $this->reservationForm['comment'];
        $reservation->activity = $this->reservationForm['activity'];
        $reservation->associated_project = $this->reservationForm['associated_project'];
        $reservation->assistants = $this->reservationForm['assistants'];
        $reservation->services()->sync($this->selectedServices);
        $reservation->save();

        $this->close();
        $this->reset();
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        $reservation->active = false;
        $reservation->save();
    }

    public function close()
    {
        $this->dataReservation = false;
        $this->showReservation = false;
        $this->editReservation = false;

        $this->reset();
        $this->render();
    }

    public function dataReservation()
    {
        $this->dataReservation = !$this->dataReservation;
    }

    public function showReservation()
    {
        $this->showReservation = !$this->showReservation;
    }

    public function editReservation()
    {
        $this->editReservation = !$this->editReservation;
    }

    public function statusApproved($id)
    {
        $reservation = Reservation::with(['client'])->where('id', $id)->first();
        $reservation->status = ReservationStatusEnum::approved;
        $reservation->user_id = auth()->user()->id;
        $reservation->save();

        // Email
        Mail::to($reservation->client->email)->send(new ReservationStatusEmail($reservation->id));

        $this->close();
        $this->mount();
    }

    public function statusReject($id)
    {
        $reservation = Reservation::with(['client'])->where('id', $id)->first();
        $reservation->status = ReservationStatusEnum::rejected;
        $reservation->user_id = auth()->user()->id;
        $reservation->save();

        // Email
        Mail::to($reservation->client->email)->send(new ReservationStatusEmail($reservation->id));

        $this->close();
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = ($this->statusFilter == $status) ? null : $status;
        $this->updateReservations();
    }

    public function filterByPlace($place)
    {
        $this->placeFilter = ($this->placeFilter == $place) ? null : $place;
        $this->updateReservations();
    }

    public function updateReservations()
    {
        $reservationsQuery = Reservation::with('place.building.campus', 'client', 'hours', 'dates')
            ->where('active', true)
            ->whereHas('place.building.campus', function ($query) {
                $query->where('campus_id', $this->campusFilter);
            })
            ->orderByRaw("FIELD(reservations.status, 'PENDIENTE', 'APROBADO', 'RECHAZADO') ASC");

        $reservations = $reservationsQuery->get();

        $this->uniquePlaces = $reservations->pluck('place')->unique('id')->values();

        if ($this->placeFilter != null) {
            $reservationsQuery->when($this->placeFilter, function ($query) {
                $query->whereHas('place', function ($subquery) {
                    $subquery->where('id', $this->placeFilter);
                });
            });
        }

        if ($this->dateFilter != null) {
            $reservationsQuery->whereHas('dates', function ($query) {
                $query->where('date', $this->dateFilter);
            });
        }

        $this->reservations = $reservationsQuery->get();
    }

    public function mount()
    {
        $this->campusFilter = auth()->user()->campus_id;
        $this->placeFilter = '';
        $this->updateReservations();
    }

    public function render()
    {
        $this->campuses = Campus::where('active', true)->get();

        return view('livewire.reservations', [
            'reservations' => $this->reservations,
            'campuses' => $this->campuses
        ]);
    }
}
