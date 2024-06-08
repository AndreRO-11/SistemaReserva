<?php

namespace App\Livewire;

use App\Models\Seat;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Seats extends Component
{
    public $editSeat = null, $activeFilter = false;
    public $seatEdit;

    public $seat;

    use WithPagination;

    protected $messages = [
        'seat.required' => 'El campo de tipo de asiento es obligatorio.',
    ];
    public function store()
    {
        $this->validate([
            'seat' => 'required',
        ]);

        $seatExists = Seat::where('seat', $this->seat)->exists();

        if (!$seatExists) {
            $seat = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->seat)));

            $seatStore = new Seat();
            $seatStore->seat = $seat;
            $seatStore->save();

            $this->reset();
            $this->dispatch('success', 'Tipo de asiento agregado correctamente.');
        } else {
            $this->addError('seat', 'Asiento ya existe.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function edit($id)
    {
        $this->editSeat = $id;
        $seat = Seat::find($id);

        $this->seatEdit = $seat->seat;
    }

    public function update()
    {
        $this->validate([
            'seatEdit' => 'required'
        ]);

        $id = $this->editSeat;
        $seat = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U', 'Ñ'], $this->seatEdit)));

        $seatExists = Seat::where('seat' ,$this->seatEdit)->exists();

        if (!$seatExists) {
            $seatUpdate = Seat::find($id);
            $seatUpdate->seat = $seat;
            $seatUpdate->save();

            $this->reset();
            $this->editSeat = null;
            $this->dispatch('success', 'Tipo de asiento actualizado correctamente.');
        } else {
            $this->addError('seatEdit', 'Asiento ya existe.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function close()
    {
        $this->editSeat = null;
        $this->reset();
        $this->resetPage();
    }

    public function delete($id)
    {
        $seat = Seat::find($id);
        $seat->active = false;
        $seat->save();
        $this->resetPage();
        $this->dispatch('warning', 'Tipo de asiento desactivado.');
    }

    public function setActive($id)
    {
        $seat = Seat::find($id);
        $seat->active = true;
        $seat->save();
        $this->resetPage();
        $this->dispatch('success', 'Tipo de asiento activado.');
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);
        $allSeats = Seat::all();

        $allSeats = Seat::query();

        if (!$this->activeFilter) {
            $allSeats->where('active', true);
        }

        $allSeats = $allSeats->orderBy('active', 'desc')
            ->orderBy('seat', 'asc');

        $seats = $allSeats->paginate(10);

        return view('livewire.seats', [
            'seats' => $seats
        ]);
    }
}
