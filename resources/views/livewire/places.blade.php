<div>

    <div class="container mt-3">

        <div class="opciones_boton mb-3">
            <div class="col-2">
                <input wire:model="selectedDate" class="form-control" type="date" value="">
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#placeModal">
                Agregar Espacio
            </button>
        </div>

        @if (!$unreservedPlaces)
        <div class="mx-auto">
            <h5>No existen espacios disponibles</h5>
        </div>
        @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($unreservedPlaces as $place)
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $place->code }} <span class="badge text-bg-info">{{ $place->capacity }}</span></h5>
                        <div class="card-text">
                            <div class="m-1">
                                <h7>Edificio {{ $place->building->building }}, Piso {{ $place->floor }}</h7>
                                <br>
                                <h7>{{ $place->building->campus }}, {{ $place->building->city }}</h7>
                            </div>
                            <div class="m-2">
                                @foreach ($place->details as $detail)
                                <span class="m-1 badge text-bg-info">{{ $detail->detail}}</span>
                                @endforeach
                            </div>
                            <div class="m-2">

                            </div>
                            <div class="opciones_boton">
                                <button wire:click="book({{ $place->id }})" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal">Reservar</button>
                                <button wire:click="edit({{ $place->id }})" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#placeModal"><i class="bi bi-pencil-square"></i></button>
                                <button wire:click="delete({{ $place->id }})" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif


        <div wire:ignore.self class="modal fade" id="placeModal" tabindex="-1" aria-labelledby="placeModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="placeModalTitle">

                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session() }}
                            </div>
                        @endif

                        <form wire:submit="store">
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                <div class="col">
                                    <div class="mt-2">
                                        <label class="form-label" for="placeEdit.code">Código de sala</label>
                                        <input wire:model="placeEdit.code" id="placeEdit.code" class="form-control" type="text" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="placeEdit.capacity">Capacidad</label>
                                        <input wire:model="placeEdit.capacity" id="placeEdit.capacity" class="form-control" type="number" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="placeEdit.floor">Piso</label>
                                        <input wire:model="placeEdit.floor" id="placeEdit.floor" class="form-control" type="number" name="floor" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="placeEdit.type_id">Tipo de espacio</label>
                                        <select wire:model="placeEdit.type_id" id="placeEdit.type_id" class="form-select">
                                            <option value="" disabled>Seleccione el tipo de espacio.</option>
                                            @foreach ($types as $type)
                                            <option value="{{ $type->id }}" required>{{ $type->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="placeEdit.seat_id">Tipo de asiento</label>
                                        <select wire:model="placeEdit.seat_id" id="placeEdit.seat_id" class="form-select">
                                            <option value="" disabled>Seleccione el tipo de asientos.</option>
                                            @foreach ($seats as $seat)
                                            <option value="{{ $seat->id }}" required>{{ $seat->seat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mt-2">
                                        <label class="form-label" for="placeEdit.building_id">Ubicación</label>
                                        <select wire:model="placeEdit.building_id" id="placeEdit.building_id" class="form-select">
                                            <option value="" disabled>Seleccione una ubicación.</option>
                                            @foreach ($buildings as $building)
                                            <option value="{{ $building->id }}" required>{{ $building->building }}, {{ $building->campus }}, {{ $building->city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Detalles del espacio:</label>
                                        <ul style="list-style-type: none;">
                                            @foreach ($details as $detail)
                                            <li>
                                                <label class="form-check-label" for="selectedDetails{{ $detail->id }}">
                                                <input wire:model="selectedDetails" id="selectedDetails{{ $detail->id }}" class="form-check-input" type="checkbox" value="{{ $detail->id }}">
                                                    {{ $detail->detail }}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="opciones_boton mt-3">
                                @if (!$editPlace)
                                <button class="btn btn-primary" type="submit">Agregar</button>
                                @else
                                <button wire:click="update" class="btn btn-primary">Actualizar</button>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="reservationModalTitle">
                            {{ $selectedDate }}, Espacio {{ $reservationPlace->code ?? '' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session() }}
                            </div>
                        @endif

                        <form wire:submit="store">
                            <div class="row row-cols-1 row-cols-md-2 g-4 m-2">
                                <div class="col">
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationPlace.data">Espacio:</label>
                                        <input class="form-control" id="reservationPlace.data" type="text" value="{{ $reservationPlace->code ?? '' }}, {{ $reservationPlace->building->building ?? '' }} - {{ $reservationPlace->building->campus ?? '' }}, {{ $reservationPlace->building->city ?? '' }}" disabled>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.name">Nombre</label>
                                        <input wire:model="reservationEdit.name" id="reservationEdit.name" class="form-control" type="text">
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.email">Correo</label>
                                        <input wire:model="reservationEdit.email" id="reservationEdit.email" class="form-control" type="text">
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.userType">Cargo</label>
                                        <input wire:model="reservationEdit.userType" id="reservationEdit.userType" class="form-control" type="text">
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.activity">Actividad</label>
                                        <input wire:model="reservationEdit.activity" id="reservationEdit.activity" class="form-control" type="text">
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label" for="reservationEdit.assistants">Cantidad asistentes</label>
                                        <input wire:model="reservationEdit.assistants" id="reservationEdit.assistants" class="form-control" type="number">
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-check-label" for="reservationEdit.associated_project">Proyecto asociado (Si hay)</label>
                                        <input wire:model="reservationEdit.associated_project" id="reservationEdit.associated_project" class="form-check-input" type="checkbox">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col">
                                        <div class="mt-2">
                                            <label class="form-label" for="reservationEdit.comment">Observaciones:</label>
                                            <textarea wire:model="reservationEdit.comment" id="reservationEdit.comment" class="form-control"  rows="5"></textarea>
                                        </div>
                                        <div class="mt-2 cols-sm-2 d-grid">
                                            @if (empty($services))
                                            <h6>No hay servicios disponibles</h6>
                                            @else
                                            <p>Servicios disponibles:</p>
                                            <ul style="list-style-type: none;">
                                                @foreach ($services as $service)
                                                <li>
                                                    <label class="form-check-label" for="selectedServices">
                                                        <input wire:model="selectedServices" id="selectedServices" class="form-check-input" type="checkbox" value="{{ $service->id }}">
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
                                            {{-- @foreach($availableHours as $hour)
                                            <input wire:model="selectedHours" class="btn-check" type="checkbox" id="selectedHours" value="{{ $hour['hour'] }}">
                                            <label class="btn btn-outline-secondary" for="selectedHours">{{ $hour['formatted_hour'] }}</label>
                                            @endforeach --}}
                                    </div>
                                </div>
                            </div>
                            <div class="opciones_boton mt-3">
                                <button wire:click="bookSave" class="btn btn-primary">Reservar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@script
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('close-modal', (event) => {
            $('#placeModal').modal('hide');
        });
        $wire.dispatch('reset-modal');
    });
</script>
@endscript
