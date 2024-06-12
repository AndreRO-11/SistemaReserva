<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use App\Models\Place;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    public $option, $campusFilter, $campuses;
    public $dateFrom;
    public $dateTo;

    public $placesCount;
    public $places;

    use WithPagination;

    public function reportPlace()
    {
        $this->option = 'place';
    }

    public function reportDate()
    {
        $this->option = 'date';
    }

    public function render()
    {
        return view('livewire.admin.reports');
    }
}
