<div>

    @if (!$dataReservation)
        <div class="d-md-flex gap-2 justify-content-center">
            <div class="d-grid mt-2">
                <input wire:model.live="dateFilter" class="form-control" type="date">
            </div>
            <div class="d-grid mt-2">
                <select wire:model="placeFilter" wire:change="filterByPlace" class="form-select">
                    <option value="">Espacio</option>
                    @foreach ($uniquePlaces as $place)
                        <option value="{{ $place->id }}">{{ $place->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-grid mt-2">
                <select wire:model="campusFilter" wire:change="filterByCampus" class="form-select">
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->id }}">{{ $campus->campus }}, {{ $campus->city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex gap-2 justify-content-between mt-2">
                <button wire:click="filterByStatus('PENDIENTE')"
                    class="btn btn-secondary @if ($statusFilter === 'PENDIENTE') active @endif" data-bs-toggle="button"
                    style="font-weight: bold;">PENDIENTE</button>
                <button wire:click="filterByStatus('APROBADO')"
                    class="btn btn-success @if ($statusFilter === 'APROBADO') active @endif" data-bs-toggle="button"
                    style="font-weight: bold;">APROBADO</button>
                <button wire:click="filterByStatus('RECHAZADO')"
                    class="btn btn-danger @if ($statusFilter === 'RECHAZADO') active @endif" data-bs-toggle="button"
                    style="font-weight: bold;">RECHAZADO</button>
            </div>
            <div class="d-grid mt-2">
                <button wire:click="filterByActive" class="btn btn-warning" style="font-weight: bold;">
                    @if ($activeFilter)
                        <i class="bi bi-toggle-off text-dark"></i>
                    @else
                        <i class="bi bi-toggle-on text-dark"></i>
                    @endif
                    ACTIVOS
                </button>
            </div>
        </div>
        <div class="d-grid mt-2">
            <input wire:model.live="search" class="form-control" placeholder="Búsqueda por cliente" type="text">
        </div>

        <div class="card mt-2">
            <div class="table-responsive card-body">
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
                        @if ($reservationsCount === 0)
                            <tr>
                                <td colspan="12" class="text-center">No existen reservaciones.</td>
                            </tr>
                        @else
                            @foreach ($reservations as $reservation)
                                <tr class="@if (!$reservation->active) table-danger @endif">
                                    <td>{{ $reservation->dates->first()->date ?? '' }}</td>
                                    <td class="text-center">
                                        @if ($reservation->hours->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($reservation->hours->min('hour'))->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($reservation->hours->max('hour'))->addMinutes(40)->format('H:i') }}
                                        @endif
                                    </td>
                                    <td>{{ $reservation->place->code }} -
                                        {{ $reservation->place->building->building }},
                                        {{ $reservation->place->building->campus->campus }} -
                                        {{ $reservation->place->building->campus->city }}</td>
                                    <td>{{ $reservation->client->name }}</td>
                                    <td>{{ $reservation->activity }}</td>
                                    <td class="text-center">{{ $reservation->assistants }}</td>
                                    <td class="text-center">
                                        @switch($reservation->status->value)
                                            @case('APROBADO')
                                                <h5><span class="badge bg-success">{{ $reservation->status }}</span>
                                                </h5>
                                            @break

                                            @case('RECHAZADO')
                                                <h5><span class="badge bg-danger">{{ $reservation->status }}</span>
                                                </h5>
                                            @break

                                            @default
                                                <h5><span class="badge bg-secondary">{{ $reservation->status }}</span>
                                                </h5>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="opciones_boton">
                                            <button wire:click="show({{ $reservation->id }})"
                                                class="btn btn-primary"><i class="bi bi-eye"></i></button>
                                            @if ($reservation->active)
                                                <button wire:click="delete({{ $reservation->id }})"
                                                    wire:confirm="¿Está seguro de eliminar la reserva seleccionada?"
                                                    class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                            @else
                                                <button wire:click="setActive({{ $reservation->id }})"
                                                    wire:confirm="¿Está seguro de activar la reserva seleccionada?"
                                                    class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div wire:loading class="spinner_container">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
        </div>
        <div class="mt-2">
            {{ $reservations->links() }}
        </div>
    @endif

    {{-- VER RESERVA --}}
    @if ($dataReservation)
        <div class="mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="col">
                            <h5 class="card-title">
                                Espacio: {{ $reservation->place->code ?? '' }} -
                                {{ $reservation->place->building->building ?? '' }},
                                {{ $reservation->place->building->campus->campus ?? '' }} -
                                {{ $reservation->place->building->campus->city ?? '' }}
                                @switch($reservation->status->value)
                                    @case('APROBADO')
                                        <span class="badge text-bg-success">{{ $reservation->status }}</span>
                                    @break

                                    @case('RECHAZADO')
                                        <span class="badge text-bg-danger">{{ $reservation->status }}</span>
                                    @break

                                    @default
                                        <span class="badge text-bg-secondary">{{ $reservation->status }}</span>
                                @endswitch
                                / {{ $reservation->user->name ?? '' }}
                            </h5>
                        </div>
                        <div wire:loading class="spinner_container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="text-end">
                            @if (!$editReservation)
                                <button wire:click="edit({{ $reservation->id }})" class="btn btn-warning"><i
                                        class="bi bi-pencil-square text-dark"></i></button>
                            @endif
                            <button wire:click="close" class="btn btn-secondary">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    @if ($showReservation)
                        <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                            <div class="col">
                                <div class="mt-2">
                                    <p>Nombre: {{ $reservation->client->name }}</p>
                                </div>
                                <div class="mt-2">
                                    <p>Correo: {{ $reservation->client->email }}</p>
                                </div>
                                <div class="mt-2">
                                    <p>Tipo de usuario: {{ $reservation->client->user_type }}</p>
                                </div>
                                <div class="mt-2">
                                    <p>Actividad: {{ $reservation->activity }}</p>
                                </div>
                                <div class="mt-2">
                                    <p>Asistentes: {{ $reservation->assistants }}</p>
                                </div>
                                <div class="mt-2">
                                    <p>Proyecto asociado: @if ($reservation->associated_project)
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
                                            {{ \Carbon\Carbon::parse($hours->min('hour'))->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($hours->max('hour'))->addMinutes(40)->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <p>Observaciones:</p>
                                    <p>{!! nl2br($reservation->comment) !!}</p>
                                </div>
                                <div class="mt-2">
                                    <p>Servicios:</p>
                                    @if (!$selectedServices)
                                        <p>No se solicitaron servicios.</p>
                                    @else
                                        <ul>
                                            @foreach ($selectedServices as $service)
                                                <li>
                                                    <p>{{ $service->service }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="opciones_boton mt-2">
                            <button wire:loading.attr="disabled" wire:click="statusApproved({{ $reservation->id }})"
                                class="btn btn-success" style="font-weight: bold;">APROBAR</button>
                            <button wire:loading.attr="disabled" wire:click="statusReject({{ $reservation->id }})"
                                class="btn btn-danger" style="font-weight: bold;">RECHAZAR</button>
                        </div>
                    @endif

                    {{-- EDITAR RESERVA --}}
                    @if ($editReservation)
                        <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                            <div class="col">
                                <div class="mt-2">
                                    <label class="form-label" for="clientForm.name">Nombre:</label>
                                    <input type="text" id="clientForm.name" class="form-control"
                                        value="{{ $reservation->client->name }}" disabled>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="clientForm.email">Correo:</label>
                                    <input type="text" id="clientForm.email" class="form-control"
                                        value="{{ $reservation->client->email }}" disabled>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="clientForm.userType">Tipo de
                                        usuario:</label>
                                    <input type="text" id="clientForm.userType" class="form-control"
                                        value="{{ $reservation->client->user_type }}" disabled>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservationForm.activity">Actividad:</label>
                                    <input wire:model="reservationForm.activity" type="text"
                                        id="reservationForm.activity"
                                        class="form-control @error('reservationForm.activity') is-invalid @enderror"
                                        value="{{ $reservationForm['activity'] }}" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservationForm.assistants">Cantidad de
                                        asistentes:</label>
                                    <input wire:model="reservationForm.assistants" type="text"
                                        id="reservationForm.assistants"
                                        class="form-control @error('reservationForm.assistants') is-invalid @enderror"
                                        value="{{ $reservationForm['assistants'] }}" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-check-label" for="reservationForm.associated_project">Proyecto
                                        asociado (Si
                                        hay)</label>
                                    <input wire:model="reservationForm.associated_project"
                                        id="reservationForm.associated_project" class="form-check-input"
                                        @if ($reservationForm['associated_project']) checked @endif type="checkbox">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mt-2">
                                    <p>
                                        Horario seleccionado:
                                        @if ($hours && $hours->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($hours->min('hour'))->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($hours->max('hour'))->addMinutes(40)->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservationForm.comment">Observaciones:</label>
                                    <textarea wire:model="reservationForm.comment" id="reservationForm.comment" class="form-control" rows="5">{!! nl2br($reservationForm['comment']) !!}</textarea>
                                </div>
                                <div class="mt-2 cols-sm-2 d-grid">
                                    <p>Servicios disponibles:</p>
                                    <ul style="list-style-type: none;">
                                        @foreach ($allServices as $service)
                                            <li>
                                                <label class="form-check-label"
                                                    for="selectedServices{{ $service->id }}">
                                                    <input wire:model="selectedServices" class="form-check-input"
                                                        type="checkbox" value="{{ $service->id }}"
                                                        id="selectedServices{{ $service->id }}"
                                                        {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}>
                                                    {{ $service->service }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div wire:loading wire:target="update" class="spinner_container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="opciones_boton mt-2">
                            <button wire:loading.attr="disabled" wire:click="update" class="btn btn-primary"
                                style="font-weight: bold;">ACTUALIZAR</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
