<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        @page {
            margin: 20px 0;
        }

        body {
            margin: 20px 50px;
        }

        #header {
            display: block;
            height: 100px;
            position: fixed;
            top: -100px;
            left: -50px;
            right: -50px;
        }

        .report_header_container {
            display: flex;
            padding: 20px;
            justify-content: space-between;
        }

        #footer {
            display: block;
            background-color: #1B2E51;
            height: 20px;
            position: fixed;
            bottom: -20px;
            left: -50px;
            right: -50px;
        }


        .break {
            display: block;
            background-color: #1B2E51;
            position: fixed;
            top: -20px;
            height: 20px;
            left: -50px;
            right: -50px;
        }

        table {
            width: 100%;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .text_left th {
            text-align: left;
        }
    </style>

</head>

<body>

    {{-- <div id="header">
        <div class="report_header_container">
            {{-- <img src="{{ public_path('images/Logo_VRIP.png') }}" alt="">
            <img src="{{ public_path('images/escudo-color-gradiente.png') }}" alt=""> --
        </div>
    </div> --}}
    <div class="break"></div>

    <div id="footer"></div>

    <div class="container">
        @foreach ($data as $place)
            <h2>Espacio: {{ $place->code }}</h2>
            <p style="font-weight: bold">Reservas realizadas entre las fechas:
                {{ \Carbon\Carbon::parse($dateFrom)->format('d-m-Y') }} -
                {{ \Carbon\Carbon::parse($dateTo)->format('d-m-Y') }}</p>
            <p class="mt-2">{{ $place->building->building }}, Piso {{ $place->floor }} -
                {{ $place->building->campus->campus }}, {{ $place->building->campus->city }}</p>
            <p>
                Detalles del espacio:
                {{ implode(', ', $place->details->pluck('detail')->toArray()) }}.
            </p>

            @if ($totalReservations === 0)
                <br>
                <h4>No existen reservas realizadas dentro de las fechas seleccionadas.</h4>
            @else
                <div class="table-responsive" style="margin-top: 20px">
                    <table class="table">
                        <tr class="tr_divider">
                            <th scope="col">Total de reservas: {{ $totalReservations }}</th>
                            <th scope="col">Pendientes: {{ $pending }}</th>
                            <th scope="col">Aprobados: {{ $approved }}</th>
                            <th scope="col">Rechazados: {{ $rejected }}</th>
                        </tr>
                    </table>
                </div>

                <div class="table-responsive" style="margin-top: 20px">
                    <table class="table">
                        <thead>
                            <tr class="text_left">
                                <th scope="col">Fecha:</th>
                                <th scope="col">Horario:</th>
                                <th scope="col">Actividad:</th>
                                <th scope="col">P.A.:</th>
                                <th scope="col">Asist.:</th>
                                <th scope="col">Servicios:</th>
                                <th scope="col">Estatus:</th>
                                <th scope="col">Administrado:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($place->reservations as $reservation)
                                @if ($reservation->dates->first()->date >= $dateFrom && $reservation->dates->first()->date <= $dateTo)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($reservation->dates->first()->date)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            @if ($reservation->hours->isNotEmpty())
                                                {{ \Carbon\Carbon::parse($reservation->hours->min('hour'))->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($reservation->hours->max('hour'))->addMinutes(40)->format('H:i') }}
                                            @endif
                                        </td>
                                        <td>{{ $reservation->activity }}</td>
                                        <td>
                                            @if ($reservation->associated_project)
                                                Sí
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td>{{ $reservation->assistants }}</td>
                                        <td>
                                            @foreach ($reservation->services as $service)
                                                {{ $service->service }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @switch($reservation->status->value)
                                                @case('APROBADO')
                                                    APROBADO
                                                @break

                                                @case('RECHAZADO')
                                                    RECHAZADO
                                                @break

                                                @default
                                                    PENDIENTE
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $reservation->user->name ?? '' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach

    </div>

</body>

</html>
