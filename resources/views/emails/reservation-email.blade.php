<div>

    <div class="container">
        <h3>Estimado(a) {{ $reservation->client->name }}:</h3>
        <br>
        <h3>Su reserva del espacio '{{ $reservation->place->code }}' en el edificio {{ $reservation->place->building->building }} en el campus {{ $reservation->place->building->campus }} en la ciudad de {{ $reservation->place->building->city }} se ha realizado correctamente.</h3>
        <br>
        <h3>Fecha de la reservación: {{ $reservation->dates->date }}</h3>
        <br>
        <h3>Horario de la reservación: {{ \Carbon\Carbon::parse($reservation->hours->min('hour'))->format('H:i')  }} - {{ \Carbon\Carbon::parse($reservation->hours->max('hour'))->addMinutes(40)->format('H:i') }}</h3>
        <br>
        <h3>Queda pendiente para su aprobación.</h3>
    </div>

</div>
