<?php

namespace App\Livewire\Admin\Report;

use App\Models\Place;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class ReportPlace extends Component
{
    public $place, $data;

    public function generateReportPlace($id)
    {
        $this->place = Place::where('id', $id)->first();
        $code = $this->place->code;
        $todayDate = Carbon::now()->format('Ymd');

        $pdf = Pdf::loadView('livewire.admin.report.report-place', ['place' => $this->place]);
        $pdf->setPaper('letter');
        return $pdf->download($todayDate.$code.'.pdf');

        return view('livewire.admin.report.report-place');

        // return response()->streamDownload(function () {
        //     echo ('livewire.admin.report.report-place'); // Echo download contents directly...
        // }, $todayDate.$code.'.pdf');
    }

    public function download()
    {
        
    }

    public function render($id)
    {
        $this->data = Place::where('id', $id)
        ->with('details', 'type', 'seat', 'building', 'reservations.dates', 'reservations.hours')
        ->get();

        return view('livewire.admin.report.report-place', [
            'data' => $this->data
        ]);
    }
}
