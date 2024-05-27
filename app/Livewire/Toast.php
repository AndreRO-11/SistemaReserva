<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Toast extends Component
{
    public $message, $color;

    // protected $listeners = ['showToast'];

    #[On('showToast')]
    public function toast($message, $color)
    {
        $this->message = $message;
        $this->color = $color;
    }

    public function render()
    {
        return view('livewire.toast');
    }
}
