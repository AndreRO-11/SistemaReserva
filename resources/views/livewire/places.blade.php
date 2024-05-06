<div>

    <div class="container">

        <div class="opciones_boton mb-3 row row-cols-1 row-cols-md-4 row-cols-lg-4">
            <div class="col text-center pt-3">
                <label for="selectedDates">
                    <h6>Fecha a buscar:</h6>
                </label>
            </div>

            <div class="col mt-2">
                <input wire:model="selectedDates" wire:change="actualizarUnreservedPlaces" class="form-control"
                    type="date" id="selectedDates" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
            </div>

            @guest
                <div class="col mt-2">
                    <select wire:model="cityFilter" wire:change="actualizarUnreservedPlaces" class="form-select">
                        <option value="" selected>Filtrar por ciudad.</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
            @endguest

            @auth
                @if (!$updatePlace && !$bookPlace)
                    <div class="col opciones_boton mt-2">
                        <button wire:click="$set('addPlace', true)" class="btn btn-primary">
                            Agregar Espacio
                        </button>
                    </div>
                @endif
            @endauth

        </div>

        @if (!$unreservedPlaces)
            <div class="mx-auto">
                <h5>No se encuentran espacios disponibles</h5>
            </div>
        @else
            @if (!$addPlace && !$updatePlace)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($unreservedPlaces as $place)
                        @if ($place->availableHours > 0)
                            <div class="col">
                                <div class="card @if (!$place->active) border-danger text-bg-danger @endif">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">{{ $place->code ?? '' }} <span
                                                class="badge text-bg-info">{{ $place->capacity }}</span></h5>
                                        <div class="card-text">
                                            <div class="m-1 text-center">
                                                <p style="margin-top:0; margin-bottom:0;">Edificio
                                                    {{ $place->building->building }}, Piso
                                                    {{ $place->floor }}</p>
                                                <p style="margin-top:0; margin-bottom:0;">
                                                    {{ $place->building->campus->campus }},
                                                    {{ $place->building->campus->city }}</p>
                                            </div>
                                            <div class="m-1 text-center">
                                                @foreach ($place->details as $detail)
                                                    <span class="m-1 badge text-bg-info">{{ $detail->detail }}</span>
                                                @endforeach
                                            </div>
                                            <div class="text-center">
                                                <p style="margin-top:0; margin-bottom:0;">Horarios disponibles:</p>
                                                @foreach ($place->availableHours as $hour)
                                                    <button class="btn btn-sm btn-secondary mt-1"
                                                        disabled>{{ $hour['formatted_hour'] }}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mt-2 opciones_boton">
                                            @if ($place->active)
                                                <button wire:click="book({{ $place->id }})" class="btn btn-success">
                                                    Reservar
                                                </button>
                                            @endif

                                            @auth
                                                <button wire:click="edit({{ $place->id }})" class="btn btn-warning"><i
                                                        class="bi bi-pencil-square text-dark"></i></button>
                                                @if ($place->active)
                                                    <button wire:click="delete({{ $place->id }})"
                                                        class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                                @else
                                                    <button wire:click="setActive({{ $place->id }})"
                                                        class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            @endif
        @endif

        {{-- CREAR EDITAR ESPACIO --}}
        @if ($addPlace || $updatePlace)
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="col">
                                @if ($addPlace)
                                    <h5>Nuevo Espacio</h5>
                                @elseif ($updatePlace)
                                    <h5>Editar Espacio {{ $place['code'] }}</h5>
                                @endif
                            </div>
                            <div class="text-end">
                                <button wire:click="close" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                            <div class="col">
                                <div class="">
                                    <label class="form-label" for="place.code">Código de sala</label>
                                    <input wire:model="place.code" id="place.code" class="form-control" type="text"
                                        @if ($addPlace) required @else disabled @endif>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="place.capacity">Capacidad</label>
                                    <input wire:model="place.capacity" id="place.capacity" class="form-control"
                                        type="number" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="place.floor">Piso</label>
                                    <input wire:model="place.floor" id="place.floor" class="form-control" type="number"
                                        name="floor" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="place.type_id">Tipo de espacio</label>
                                    <select wire:model="place.type_id" id="place.type_id" class="form-select">
                                        <option value="" disabled>Seleccione el tipo de espacio.</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}" required>{{ $type->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="place.seat_id">Tipo de asiento</label>
                                    <select wire:model="place.seat_id" id="place.seat_id" class="form-select">
                                        <option value="" disabled>Seleccione el tipo de asientos.</option>
                                        @foreach ($seats as $seat)
                                            <option value="{{ $seat->id }}" required>{{ $seat->seat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mt-2">
                                    <label class="form-label" for="place.building_id">Ubicación</label>
                                    <select wire:model="place.building_id" id="place.building_id"
                                        class="form-select">
                                        <option value="" disabled>Seleccione una ubicación.</option>
                                        @foreach ($buildings as $building)
                                            <option value="{{ $building->id }}" required>
                                                {{ $building->building }},
                                                {{ $building->campus->campus }}, {{ $building->campus->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Detalles del espacio:</label>
                                    <ul style="list-style-type: none;">
                                        @foreach ($details as $detail)
                                            <li>
                                                <label class="form-check-label"
                                                    for="selectedDetails{{ $detail->id }}">
                                                    <input wire:model="selectedDetails"
                                                        id="selectedDetails{{ $detail->id }}"
                                                        class="form-check-input" type="checkbox"
                                                        value="{{ $detail->id }}">
                                                    {{ $detail->detail }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="opciones_boton mt-3">
                            @if (!$updatePlace)
                                <button wire:click="store" class="btn btn-primary">Agregar</button>
                            @else
                                <button wire:click="update" class="btn btn-primary">Actualizar</button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        @endif

        {{-- RESERVAR --}}
        @if ($bookPlace)
            <div class="container mt-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="col">
                                <h5>{{ $selectedDates }}, Espacio {{ $placeReservation->code ?? '' }}</h5>
                            </div>
                            <div class="text-end">
                                <button wire:click="close" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                            <div class="col">
                                <div class="mt-2">
                                    <label class="form-label" for="reservationPlace.data">Espacio:</label>
                                    <input class="form-control" id="reservationPlace.data" type="text"
                                        value="{{ $placeReservation->code ?? '' }}, {{ $placeReservation->building->building ?? '' }} - {{ $placeReservation->building->campus->campus ?? '' }}, {{ $placeReservation->building->campus->city ?? '' }}"
                                        disabled>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservation.name">Nombre</label>
                                    <input wire:model="reservation.name" id="reservation.name" class="form-control"
                                        type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservation.email">Correo</label>
                                    <input wire:model="reservation.email" id="reservation.email"
                                        class="form-control @error('reservation.email') is-invalid @enderror"
                                        type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservation.userType">Cargo</label>
                                    <input wire:model="reservation.userType" id="reservation.userType"
                                        class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservation.activity">Actividad</label>
                                    <input wire:model="reservation.activity" id="reservation.activity"
                                        class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label" for="reservation.assistants">Cantidad
                                        asistentes</label>
                                    <input wire:model="reservation.assistants" id="reservation.assistants"
                                        class="form-control" type="number" required>
                                    @error('reservation.assistants')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-2">
                                    <label class="form-check-label" for="reservation.associated_project">Proyecto
                                        asociado (Si
                                        hay)</label>
                                    <input wire:model="reservation.associated_project"
                                        id="reservation.associated_project" class="form-check-input" type="checkbox">
                                </div>
                            </div>
                            <div class="col">
                                <div class="col">
                                    <div class="mt-2">
                                        <label class="form-label" for="reservation.comment">Observaciones:</label>
                                        <textarea wire:model="reservation.comment" id="reservation.comment" class="form-control" rows="5"></textarea>
                                    </div>
                                    <div class="mt-2 cols-sm-2 d-grid">
                                        <p>Servicios disponibles:</p>
                                        @if (empty($services))
                                            <h6>No hay servicios disponibles</h6>
                                        @else
                                            <ul style="list-style-type: none;">
                                                @foreach ($services as $service)
                                                    <li>
                                                        <label class="form-check-label"
                                                            for="selectedServices{{ $service->id }}">
                                                            <input wire:model="selectedServices"
                                                                id="selectedServices{{ $service->id }}"
                                                                class="form-check-input" type="checkbox"
                                                                value="{{ $service->id }}">
                                                            {{ $service->service }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <p>Horas disponibles:</p>
                                    <div class="form-check">
                                        @foreach ($availableHours as $hour)
                                            <input wire:model="selectedHours" class="btn-check" type="checkbox"
                                                id="selectedHours{{ $hour['hour']['id'] }}"
                                                value="{{ $hour['hour']['id'] }}">
                                            <label class="btn btn-outline-secondary btn-sm m-1"
                                                for="selectedHours{{ $hour['hour']['id'] }}">{{ $hour['formatted_hour'] }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="opciones_boton mt-3">
                            <button wire:click="bookSave" class="btn btn-primary">Reservar</button>
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </div>

</div>
