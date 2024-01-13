<div>

    <div class="container mt-3">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#placeModal">
                Agregar Espacio
            </button>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($places as $place)
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
                            <div class="opciones_boton">
                                <button wire:click="" class="btn btn-success">Reservar</button>
                                <button wire:click="edit({{ $place->id }})" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#placeModal"><i class="bi bi-pencil-square"></i></button>
                                <button wire:click="delete({{ $place->id }})" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


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
                                        <label class="form-label">Código de sala</label>
                                        <input wire:model="placeEdit.code" class="form-control" type="text" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Capacidad</label>
                                        <input wire:model="placeEdit.capacity" class="form-control" type="number" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Piso</label>
                                        <input wire:model="placeEdit.floor" class="form-control" type="number" name="floor" required>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Tipo de espacio</label>
                                        <select wire:model="placeEdit.type_id" class="form-select">
                                            <option value="" disabled>Seleccione el tipo de espacio.</option>
                                            @foreach ($types as $type)
                                            <option value="{{ $type->id }}" required>{{ $type->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Tipo de asiento</label>
                                        <select wire:model="placeEdit.seat_id" class="form-select">
                                            <option value="" disabled>Seleccione el tipo de asientos.</option>
                                            @foreach ($seats as $seat)
                                            <option value="{{ $seat->id }}" required>{{ $seat->seat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mt-2">
                                        <label class="form-label">Ubicación</label>
                                        <select wire:model="placeEdit.building_id" class="form-select">
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
                                                <label class="form-check-label">
                                                    <input wire:model="selectedDetails" class="form-check-input" type="checkbox" value="{{ $detail->id }}">
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

    </div>

</div>

<script>
    // var mymodal = document.getElementById('placeModal')
    // mymodal.addEventListener('hidden.bs.modal', (event) => {
    //     @this.dispatch('reset-modal');
    // })
</script>

@script
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('close-modal', (event) => {
            $('#placeModal').modal('toggle');
        });
        $wire.dispatch('reset-modal');
    });
</script>
@endscript
