<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function reportPlace($id)
    {
        // $this->data = Place::where('id', $id)
        // ->with('details', 'type', 'seat', 'building', 'reservations.dates', 'reservations.hours')
        // ->get();

        // $this->pending = $this->data->flatMap(function ($place) {
        //     return $place->reservations->where('status.value', 'PENDIENTE');
        // })->count();
        // $this->approved = $this->data->flatMap(function ($place) {
        //     return $place->reservations->where('status.value', 'APROBADO');
        // })->count();
        // $this->rejected = $this->data->flatMap(function ($place) {
        //     return $place->reservations->where('status.value', 'RECHAZADO');
        // })->count();

        // return view('pdf.report-place', [
        //     'data' => $this->data,
        //     'pending' => $this->pending,
        //     'approved' => $this->approved,
        //     'rejected' => $this->rejected
        // ]);
    }

    public function downloadPlace($id, $dateFrom, $dateTo)
    // public function downloadPlace($id)
    {
        $pathLogo = public_path('images/logo_VRIP.png');
        $pathEscudo = public_path('images/escudo-color-gradiente.png');

        $data = Place::where('id', $id)
        ->with('details', 'type', 'seat', 'building', 'reservations.dates', 'reservations.hours', 'reservations.services')
        // ->whereHas('reservations.dates', function ($query) use ($dateFrom, $dateTo) {
        //     $query->whereBetween('date', [$dateFrom, $dateTo]);
        // })
        ->get();

        $dateReservation = $data->flatMap(function ($place) {
            return $place->reservations->flatMap(function ($reservation) {
                return $reservation->dates->pluck('date');
            });
        });

        if (empty($dateFrom)) {
            $dateFrom = $dateReservation->min();
        }
        if (empty($dateTo)) {
            $dateTo = Carbon::today();
        }

        $pending = $data->flatMap(function ($place) {
            return $place->reservations->where('status.value', 'PENDIENTE');
        })->count();
        $approved = $data->flatMap(function ($place) {
            return $place->reservations->where('status.value', 'APROBADO');
        })->count();
        $rejected = $data->flatMap(function ($place) {
            return $place->reservations->where('status.value', 'RECHAZADO');
        })->count();

        $pdf = new Dompdf();
        $pdf->loadHtml(view('pdf.report-place', [
            'data' => $data,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'pathLogo' => $pathLogo,
            'pathEscudo' => $pathEscudo,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ])
        ->render());

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $pdf->setOptions($options);

        $code = optional($data->first())->code;
        $todayDate = Carbon::now()->format('Ymd');

        $pdf->render();

        return $pdf->stream($todayDate . '_' . $code . '.pdf', ['Attachment' => 0]);
    }

    public function downloadDates()
    {


        $pdf = new Dompdf();
        $pdf->loadHtml(view('pdf.report-dates', [

        ])
        ->render());

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $pdf->setOptions($options);

        $pdf->render();
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
