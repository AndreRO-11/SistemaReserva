<?php

namespace App\Livewire;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use App\Models\Service;
use Livewire\Component;

class Reservations extends Component
{
    public $reservations, $statusFilter = null, $selectedServices = [], $selectedHours = [], $allServices = [], $showServices = [];
    public $placeEdit, $clientEdit, $hours = [], $reservationId;


    public $reservationEdit = [
        'activity' => '',
        'assistants' => '',
        'associated_project' => '',
        'comment' => '',
        'selectedServices' => []
    ];

    public function show($id)
    {
        $this->reservationId = $id;
        $reservation = Reservation::with('place', 'client', 'hours', 'services')->find($id);
        $this->reservationEdit['activity'] = $reservation->activity;
        $this->reservationEdit['assistants'] = $reservation->assistants;
        $this->reservationEdit['associated_project'] = $reservation->associated_project;
        $this->reservationEdit['comment'] = $reservation->comment;

        $this->placeEdit = $reservation->place;
        $this->clientEdit = $reservation->client;
        $this->hours = $reservation->hours;
        $this->showServices = $reservation->services;
    }

    public function edit($id)
    {
        $reservation = Reservation::with('place', 'client', 'hours', 'services')->find($id);
        $this->reservationEdit['activity'] = $reservation->activity;
        $this->reservationEdit['assistants'] = $reservation->assistants;
        $this->reservationEdit['associated_project'] = $reservation->associated_project;
        $this->reservationEdit['comment'] = $reservation->comment;

        $this->placeEdit = $reservation->place;
        $this->clientEdit = $reservation->client;
        $this->hours = $reservation->hours;
        // $this->selectedServices = $reservation->services;
        $this->reservationEdit['selectedServices'] = $reservation->services->pluck('id')->toArray();

        $allServices = Service::all();
        $this->allServices = $allServices;

    }

    public function update()
    {
        

        $this->reset();
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        $reservation->update([
            'active' => false
        ]);
    }

    public function statusApproved($id)
    {
        $reservation = Reservation::find($id);
        $reservation->update([
            'status' => ReservationStatusEnum::approved
        ]);
    }

    public function statusReject($id)
    {
        $reservation = Reservation::find($id);
        $reservation->update([
            'status' => ReservationStatusEnum::reject
        ]);
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = ($this->statusFilter == $status) ? null : $status;
    }

    public function render()
    {
        $this->reservations = Reservation::where('active', true)
        ->with('place', 'client', 'hours', 'dates')
        ->get();
        return view('livewire.reservations', [
            'reservations' => $this->reservations,
        ]);
    }
}
