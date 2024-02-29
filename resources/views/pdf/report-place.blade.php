<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        html {
            margin: 0;
        }
        header {
            display: block;
            width: 100%;
            height: 100px;
            position: fixed;
            top: 0;
        }
        .container {
            margin: 2px 50px;
        }
        .report_header_container {
            display: flex;
            padding: 20px;
            justify-content: space-between;
        }
        .report_break {
            display: block;
            width: 100%;
            margin-top: 100px;
            background-color: #1B2E51;
            height: 15px;
        }
        .report_footer {
            display: block;
            width: 100%;
            background-color: #1B2E51;
            height: 20px;
            position: fixed;
            bottom: 0;
        }
        h4 {
            font-size: larger;
        }
        table{
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

    {{-- <header>
        <div class="report_header_container">
            <img src="{{ asset('/images/Logo_VRIP.png') }}" alt="">
            <img src="{{ asset('/images/escudo-color-gradiente.png') }}" alt="">
        </div>
    </header> --}}

    <div class="report_break" style="margin-top: 0"></div>

    <div class="container">

        @foreach ($data as $place)
            <h4>Espacio: {{ $place->code }}</h4>
            <p class="mt-2">{{ $place->building->building }}, Piso {{ $place->floor }} - {{ $place->building->campus }}, {{ $place->building->city }}</p>
            <p>
                Detalles del espacio:
                {{ implode(', ', $place->details->pluck('detail')->toArray()) }}.
            </p>
            <div class="table-responsive" style="margin-top: 20px">
                <table class="table table-sm">
                    <tr class="tr_divider">
                        <th scope="col">Total de reservas: {{ count($place->reservations) }}</th>
                        <th scope="col">Pendientes: {{ $pending }}</th>
                        <th scope="col">Aprobados: {{ $approved }}</th>
                        <th scope="col">Rechazados: {{ $rejected }}</th>
                    </tr>
                </table>
            </div>

            <div class="table-responsive" style="margin-top: 20px">
                <table class="table table-sm">
                    <thead>
                        <tr class="text_left">
                            <th scope="col">Fecha:</th>
                            <th scope="col">Horario:</th>
                            <th scope="col">Actividad:</th>
                            <th scope="col">Proyecto asociado:</th>
                            <th scope="col">Asistentes:</th>
                            <th scope="col">Servicios:</th>
                            <th scope="col">Estatus:</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($place->reservations as $reservation)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse(($reservation->dates->first())->date)->format('d-m-Y') }}</td>
                                <td>
                                    @if ($reservation->hours->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($reservation->hours->min('hour'))->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($reservation->hours->max('hour'))->addMinutes(40)->format('H:i') }}
                                    @endif
                                </td>
                                <td>{{ $reservation->activity }}</td>
                                <td>
                                    @if ($reservation->associated_project) Sí @else No @endif
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
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @endforeach

    </div>

    <div class="report_footer"></div>

</body>

</html>
