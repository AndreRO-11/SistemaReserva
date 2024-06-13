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
use Masmerise\Toaster\Toaster;

class Reservations extends Component
{
    public $selectedHours = [], $allServices = [], $showServices = [];
    public $placeEdit, $clientEdit, $hours = [], $reservationId, $comment;

    public $reservation ,$place, $clientForm, $selectedServices = [], $campuses;
    public $dataReservation = false ,$showReservation = false, $editReservation = false;

    // FILTROS
    public $statusFilter = null, $campusFilter, $placeFilter = null, $uniquePlaces, $dateFilter = null, $activeFilter = false;
    public $search = '';
    public $reservationsCount;

    use WithPagination;

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
        $validated = $this->validate([
            'reservationForm.activity' => 'required',
            'reservationForm.assistants' => 'required',
        ]);

        if ($validated) {
            $id = $this->reservationId;

            $reservation = Reservation::find($id);

            $reservation->comment = $this->reservationForm['comment'];
            $reservation->activity = $this->reservationForm['activity'];
            $reservation->associated_project = $this->reservationForm['associated_project'];
            $reservation->assistants = $this->reservationForm['assistants'];
            $reservation->services()->sync($this->selectedServices);
            $reservation->save();

            $this->close();
            $this->dispatch('success', 'Reservación actualizada.');
            $this->mount();
        } else {
            $this->dispatch('failed', 'Error en datos');
        }
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        $reservation->active = false;
        $reservation->save();

        $this->dispatch('success', 'Reservación desactivada.');
    }

    public function setActive($id)
    {
        $reservation = Reservation::find($id);
        $reservation->active = true;
        $reservation->save();

        $this->dispatch('success', 'Reservación activada.');
    }

    public function close()
    {
        $this->dataReservation = false;
        $this->showReservation = false;
        $this->editReservation = false;
        $this->reset();
        $this->mount();
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
        $this->dispatch('success', 'Reservación aprobada.');
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
        $this->dispatch('success', 'Reservación rechazada.');
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = ($this->statusFilter == $status) ? null : $status;
        $this->resetPage();
    }

    public function filterByPlace()
    {
        $this->placeFilter = ($this->placeFilter !== null) ? $this->placeFilter : null;
        $this->resetPage();
    }

    public function filterByCampus()
    {
        $this->campusFilter = ($this->campusFilter == auth()->user()->campus_id) ? auth()->user()->campus_id : $this->campusFilter;
        $this->placeFilter = null;
        $this->resetPage();
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function mount()
    {
        $this->campusFilter = auth()->user()->campus_id;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);
        $this->campuses = Campus::where('active', true)->get();

        $reservationsQuery = Reservation::with('place.building.campus', 'client', 'hours', 'dates');

        // FILTRO ACTIVOS
        if (!$this->activeFilter) {
            $reservationsQuery->where('active', true);
        }

        // FILTRO POR ESTADO DE RESERVA
        if ($this->statusFilter != null) {
            $reservationsQuery->where('status', $this->statusFilter);
        }

        $reservationsQuery->whereHas('place.building.campus', function ($query) {
                $query->where('campus_id', $this->campusFilter);
            });

        $allReservations = $reservationsQuery->get();
        $this->uniquePlaces = $allReservations->pluck('place')->unique('id')->values();

        // FILTRO POR LUGAR
        if ($this->placeFilter != null) {
            $placeFilterValue = $this->placeFilter;
            $reservationsQuery->whereHas('place', function ($subquery) use ($placeFilterValue) {
                $subquery->where('id', $placeFilterValue);
            });
        }

        // FILTRO POR FECHA
        if ($this->dateFilter != null) {
            $reservationsQuery->whereHas('dates', function ($query) {
                $query->where('date', $this->dateFilter);
            });
        }

        if ($this->search) {
            $reservationsQuery->whereHas('client', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // CONTADOR DE RESERVACIONES
        $this->reservationsCount = $reservationsQuery->count();

        // ORDEN
        $reservationsQuery->orderBy('reservations.active', 'desc')
        ->orderByRaw("FIELD(reservations.status, 'PENDIENTE', 'APROBADO', 'RECHAZADO') ASC")
            ->orderBy('created_at', 'asc');

        $reservations = $reservationsQuery->paginate(10);

        return view('livewire.reservations', [
            'campuses' => $this->campuses,
            'reservations' => $reservations
        ]);
    }
}
