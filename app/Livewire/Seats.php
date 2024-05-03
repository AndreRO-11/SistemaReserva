<?php

namespace App\Livewire;

use App\Models\Seat;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Seats extends Component
{
    public $editSeat = null;
    public $seats, $seatEdit;

    public $seat;

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
        } else {
            $this->addError('seat', 'Asiento ya existe.');
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
        } else {
            $this->addError('seatEdit', 'Asiento ya existe.');
        }
    }

    public function close()
    {
        $this->editSeat = null;
        $this->reset();
    }

    public function delete($id)
    {
        $seat = Seat::find($id);
        $seat->active = false;
        $seat->save();
    }

    public function setActive($id)
    {
        $seat = Seat::find($id);
        $seat->active = true;
        $seat->save();
    }

    public function render()
    {
        $this->seats = Seat::all();
        return view('livewire.seats');
    }
}
