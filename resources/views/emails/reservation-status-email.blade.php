<div>

    <div class="container">
        <p>Estimado(a) {{ $reservation->client->name }}:</p>
        <br>
        <p>Su reserva del espacio '{{ $reservation->place->code }}' en el edificio
            {{ $reservation->place->building->building }} en el campus
            {{ $reservation->place->building->campus->campus }} con dirección
            {{ $reservation->place->building->campus->address }}.</p>
        <p>Fecha de la reservación: {{ \Carbon\Carbon::parse($reservation->dates->first()->date)->format('d-m-Y') }}</p>
        <p>Horario de la reservación: {{ \Carbon\Carbon::parse($reservation->hours->min('hour'))->format('H:i') }} -
            {{ \Carbon\Carbon::parse($reservation->hours->max('hour'))->addMinutes(40)->format('H:i') }}</p>
        <br>
        <p>Ha sido
            @switch($reservation->status->value)
                @case('APROBADO')
                    <span style="font-weight: bold">APROBADA</span>
                @break

                @case('RECHAZADO')
                    <span style="font-weight: bold">RECHAZADA</span>
                @break
            @endswitch
            .
        </p>
        <br>
        <p>Para más información contáctenos al siguiente correo: {{ $reservation->user->email }}.</p>
    </div>

</div>
