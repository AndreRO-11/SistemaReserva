<div>
    <div class="container mt-3">

        <div class="opciones_boton mb-3">
            <button wire:click="filterByStatus('PENDIENTE')" class="btn btn-secondary"
                data-bs-toggle="button">PENDIENTE</button>
            <button wire:click="filterByStatus('APROBADO')" class="btn btn-success"
                data-bs-toggle="button">APROBADO</button>
            <button wire:click="filterByStatus('RECHAZADO')" class="btn btn-danger"
                data-bs-toggle="button">RECHAZADO</button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col" class="text-center">Hora Inicio - Término</th>
                        <th scope="col">Espacio</th>
                        <th scope="col">Reservado por</th>
                        <th scope="col">Actividad</th>
                        <th scope="col" class="text-center">Asistentes</th>
                        <th scope="col" class="text-center">Estado</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($reservations as $reservation)
                        @if (!$statusFilter || $reservation->status->value === $statusFilter)
                            <tr>
                                <td>{{ $reservation->dates->first()->date ?? '' }}</td>
                                <td class="text-center">
                                    @if ($reservation->hours->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($reservation->hours->first()->hour)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($reservation->hours->last()->hour)->addMinutes(40)->format('H:i') }}
                                    @endif
                                </td>
                                <td>{{ $reservation->place->code }} - {{ $reservation->place->building->building }},
                                    {{ $reservation->place->building->campus }}</td>
                                <td>{{ $reservation->client->name }}</td>
                                <td>{{ $reservation->activity }}</td>
                                <td class="text-center">{{ $reservation->assistants }}</td>
                                <td class="text-center">
                                    @switch($reservation->status->value)
                                        @case('APROBADO')
                                            <h5><span class="badge bg-success">{{ $reservation->status }}</span></h5>
                                        @break

                                        @case('RECHAZADO')
                                            <h5><span class="badge bg-danger">{{ $reservation->status }}</span></h5>
                                        @break

                                        @default
                                            <h5><span class="badge bg-secondary">{{ $reservation->status }}</span></h5>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="opciones_boton">
                                        <button wire:click="show({{ $reservation->id }})" class="btn btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#reservationModal"><i
                                                class="bi bi-eye"></i></button>
                                        <button wire:click="edit({{ $reservation->id }})" class="btn btn-primary"><i
                                                class="bi bi-pencil-square" data-bs-toggle="modal"
                                                data-bs-target="#reservationEditModal"></i></button>
                                        <button wire:click="delete({{ $reservation->id }})" class="btn btn-danger"><i
                                                class="bi bi-trash3"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div wire:ignore.self class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="placeModalTitle">
                            Espacio: {{ $placeEdit->code ?? '' }} - {{ $placeEdit->building->building ?? '' }},
                            {{ $placeEdit->building->campus ?? '' }}, {{ $placeEdit->building->city ?? '' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                                <div class="col">
                                    <div class="mt-2">
                                        <p>Nombre: {{ $clientEdit->name ?? '' }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Correo: {{ $clientEdit->email ?? '' }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Tipo de usuario: {{ $clientEdit->user_type ?? '' }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Actividad: {{ $reservationEdit['activity'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Asistentes: {{ $reservationEdit['assistants'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Proyecto asociado: @if ($reservationEdit['associated_project'])
                                                Sí
                                            @else
                                                No
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mt-2">
                                        <p>
                                            Horario seleccionado:
                                            @if ($hours && $hours->isNotEmpty())
                                                {{ \Carbon\Carbon::parse($hours->first()->hour)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($hours->last()->hour)->addMinutes(40)->format('H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Observaciones: {!! $reservationEdit['comment'] !!}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p>Servicios:</p>
                                        @if (empty($showServices))
                                            <p>No existen servicios.</p>
                                        @else
                                            <ul>
                                                @foreach ($showServices as $service)
                                                    <li>
                                                        <p>{{ $service->service }}</p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="opciones_boton mt-3">
                                <button wire:click="statusApproved({{ $reservationId }})"
                                    class="btn btn-success">APROBAR</button>
                                <button wire:click="statusReject({{ $reservationId }})"
                                    class="btn btn-danger">RECHAZAR</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="reservationEditModal" tabindex="-1"
            aria-labelledby="reservationEditModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="placeModalTitle">
                            Espacio: {{ $placeEdit->code ?? '' }} - {{ $placeEdit->building->building ?? '' }},
                            {{ $placeEdit->building->campus ?? '' }}, {{ $placeEdit->building->city ?? '' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form wire:submit="update">
                            <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                                <div class="col">
                                    <div class="mt-2">
                                        <label class="form-label" for="clientEdit.name">Nombre:</label>
                                        <input wire:model="clientEdit.name" type="text" id="clientEdit.name"
                                            class="form-control" value="{{ $clientEdit->name ?? '' }}" disabled>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="clientEdit.email">Correo:</label>
                                        <input wire:model="clientEdit.email" type="text" id="clientEdit.email"
                                            class="form-control" value="{{ $clientEdit->email ?? '' }}" disabled>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="clientEdit.userType">Tipo de
                                            usuario:</label>
                                        <input wire:model="clientEdit.userType" type="text"
                                            id="clientEdit.userType" class="form-control"
                                            value="{{ $clientEdit->user_type ?? '' }}" disabled>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.activity">Actividad:</label>
                                        <input wire:model="reservationEdit.activity" type="text"
                                            id="reservationEdit.activity"
                                            class="form-control @error('reservationEdit.activity') is-invalid @enderror"
                                            value="{{ $reservationEdit['activity'] }}" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.assistants">Cantidad de
                                            asistentes:</label>
                                        <input wire:model="reservationEdit.assistants" type="text"
                                            id="reservationEdit.assistants"
                                            class="form-control @error('reservationEdit.assistants') is-invalid @enderror"
                                            value="{{ $reservationEdit['assistants'] }}" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-check-label"
                                            for="reservationEdit.associated_project">Proyecto asociado (Si
                                            hay)</label>
                                        <input wire:model="reservationEdit.associated_project"
                                            id="reservationEdit.associated_project" class="form-check-input"
                                            @if ($reservationEdit['associated_project']) checked @endif type="checkbox">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mt-2">
                                        <p>
                                            Horario seleccionado:
                                            @if ($hours && $hours->isNotEmpty())
                                                {{ \Carbon\Carbon::parse($hours->first()->hour)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($hours->last()->hour)->addMinutes(40)->format('H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.comment">Observaciones:</label>
                                        <textarea wire:model="reservationEdit.comment" id="reservationEdit.comment" class="form-control" rows="5">{!! $reservationEdit['comment'] !!}</textarea>
                                    </div>
                                    <div class="mt-2 cols-sm-2 d-grid">
                                        <p>Servicios disponibles:</p>
                                        <ul style="list-style-type: none;">
                                            @foreach ($allServices as $service)
                                                <li>
                                                    <label class="form-check-label" for="selectedServices{{ $service->id }}">
                                                        <input wire:model="reservationEdit.selectedServices" id="selectedServices{{ $service->id }}" class="form-check-input" type="checkbox" value="{{ $service->id }}">
                                                        {{ $service->service }}
                                                    </label>
                                                </li>
                                            @endforeach
                                            {{-- @foreach ($allServices as $service)
                                                <li>
                                                    <label class="form-check-label" for="selectedServices{{ $service->id }}">
                                                        <input wire:model="reservationEdit.selectedServices" id="selectedServices{{ $service->id }}" class="form-check-input" type="checkbox" value="{{ $service->id }}">
                                                        {{ $service->service }}
                                                    </label>
                                                </li>
                                            @endforeach --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="opciones_boton mt-3">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
