<?php

namespace App\Livewire;

use App\Models\Seat;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Seats extends Component
{
    public $editSeat = null;
    public $seats;

    #[Validate('required')]
    public $seat;

    public function store()
    {
        $this->validate();
        Seat::create([
            'seat' => $this->seat
        ]);

        $this->reset();
    }

    public function edit($id)
    {
        $this->editSeat = $id;
        $seat = Seat::find($id);

        $this->seat = $seat->seat;
    }

    public function update()
    {
        $this->validate();
        Seat::find($this->editSeat)->update([
            'seat' => $this->seat,
        ]);
        $this->reset();
        $this->editSeat = null;
    }

    public function delete($id)
    {
        $seat = Seat::find($id);
        $seat->delete();
    }


    public function render()
    {
        $this->seats = Seat::all();
        return view('livewire.seats');
    }
}
