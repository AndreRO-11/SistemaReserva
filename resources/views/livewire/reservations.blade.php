<div>

    <div class="container mt-3">

        <div class="opciones_boton mb-3">
            <button class="btn btn-primary" data-bs-toggle="button">PENDIENTE</button>
            <button class="btn btn-primary" data-bs-toggle="button">APROBADO</button>
            <button class="btn btn-primary" data-bs-toggle="button">RECHAZADO</button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-top">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Espacio</th>
                        <th scope="col">Reservado por</th>
                        <th scope="col">Actividad</th>
                        <th scope="col">Asistentes</th>
                        <th scope="col">Estado</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->dates->first() }}</td>
                        <td>{{ $reservation->place->building->building }}, {{ $reservation->place->building->campus }}</td>
                        <td>{{ $reservation->user->name }}</td>
                        <td>{{ $reservation->activity }}</td>
                        <td>{{ $reservation->assistans }}</td>
                        <td></td>
                        <td>
                            <div class="opciones_boton">
                                <button class="btn btn-primary"><i class="bi bi-eye"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
