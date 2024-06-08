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
    //public $places;

    use WithPagination;

    public function reportPlace()
    {
        $this->option = 'place';

        $this->campusFilter = auth()->user()->campus_id;
        $this->updatePlaces();
    }

    public function updatePlaces()
    {
        $places = Place::with(['building.campus', 'details', 'reservations.dates'])
            ->when($this->campusFilter, function ($query, $campusId) {
                $query->whereHas('building.campus', function ($subquery) use ($campusId) {
                    $subquery->where('campus_id', $campusId);
                });
            });

        $this->placesCount = $places->count();

        $places->paginate(3);

    }

    public function downloadPlace($id)
    {
        $pathLogo = public_path('images/logo_VRIP.png');
        $pathEscudo = public_path('images/escudo-color-gradiente.png');

        $data = Place::where('id', $id)
            ->with([
                'details',
                'type',
                'seat',
                'building',
                'reservations' => function ($query) {
                    $query->where('active', true)->with('user');
                },
                'reservations.dates',
                'reservations.hours',
                'reservations.services',
            ])
            ->get();

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

        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $totalReservations = 0;

        foreach ($data as $place) {
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
        }

        $pdf = new Dompdf();
        $pdf->loadHtml(view('pdf.report-place', [
            'data' => $data,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'pathLogo' => $pathLogo,
            'pathEscudo' => $pathEscudo,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'totalReservations' => $totalReservations
        ])
            ->render());

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $pdf->setOptions($options);

        $code = optional($data->first())->code;
        $todayDate = Carbon::now()->format('Ymd');

        $pdf->render();
        $pdfOutput = $pdf->output();
        return response()->streamDownload(
            function () use ($pdfOutput) {
                echo $pdfOutput;
            },
            $todayDate . '_' . $code . '.pdf'
        );
    }

    public function reportDate()
    {
        $this->option = 'date';
        $this->campusFilter = auth()->user()->campus_id;
    }

    public function downloadDates()
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
            })
            ->get();

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

    public function render()
    {
        $this->campuses = Campus::where('active', true)->get();

        return view('livewire.admin.reports');
    }
}
