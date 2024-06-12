<?php

namespace App\Livewire\Admin\Report;

use App\Models\Campus;
use App\Models\Place;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Livewire\Component;

class ReportsByDates extends Component
{
    public $campuses, $dateFrom, $dateTo;
    public $campusFilter, $activeFilter = false;

    public function download()
    {
        $data = Place::with([
            'details',
            'type',
            'seat',
            'building',
            'reservations' => function ($query) {
                $query->where('active', true);
            },
            'reservations.dates',
            'reservations.hours',
            'reservations.services',
        ])
            ->whereHas('building.campus', function ($query) {
                $query->where('id', $this->campusFilter);
            });

        if (!$this->activeFilter) {
            $data->where('active', true);
        }

        $data->orderBy('code', 'asc');

        $data = $data->get();

        $campus = Campus::find($this->campusFilter);

        $dateReservation = $data->flatMap(function ($place) {
            return $place->reservations->flatMap(function ($reservation) {
                return $reservation->dates->pluck('date');
            });
        });

        if (empty($this->dateFrom)) {
            $dateFrom = $dateReservation->min();
        } else {
            $dateFrom = $this->dateFrom;
        }
        if (empty($this->dateTo)) {
            $dateTo = Carbon::today();
        } else {
            $dateTo = $this->dateTo;
        }

        foreach ($data as $place) {
            $totalReservations = 0;
            $pending = 0;
            $approved = 0;
            $rejected = 0;

            foreach ($place->reservations as $reservation) {
                foreach ($reservation->dates as $date) {
                    if ($date->date >= $dateFrom && $date->date <= $dateTo) {
                        $totalReservations++;
                        switch ($reservation->status->value) {
                            case 'PENDIENTE':
                                $pending++;
                                break;
                            case 'APROBADO':
                                $approved++;
                                break;
                            case 'RECHAZADO':
                                $rejected++;
                                break;
                        }
                    }
                }
            }
            $place->totalReservations = $totalReservations;
            $place->pending = $pending;
            $place->approved = $approved;
            $place->rejected = $rejected;
        }

        $pdf = new Dompdf();
        $pdf->loadHtml(view('pdf.report-dates', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data,
            'campus' => $campus
        ])
            ->render());

        $this->dispatch('success', 'Reporte generado correctamente.');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $pdf->setOptions($options);

        $dateFrom = Carbon::now()->format('Ymd');

        $pdf->render();
        $pdfOutput = $pdf->output();
        return response()->streamDownload(
            function () use ($pdfOutput) {
                echo $pdfOutput;
            },
            'Reporte' . $dateFrom . '.pdf'
        );
    }

    public function filterByCampus()
    {
        $this->campusFilter = ($this->campusFilter == auth()->user()->campus_id) ? auth()->user()->campus_id : $this->campusFilter;
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
    }

    public function mount()
    {
        $this->campusFilter = auth()->user()->campus_id;
    }

    public function render()
    {
        $this->campuses = Campus::where('active', true)->get();


        return view('livewire..admin.report.reports-by-dates');
    }
}
