<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-sm-1 row-cols-md-5 justify-content-center">
                <div class="col">
                    <input wire:model="buildingStore.building" type="text" class="form-control" placeholder="Edificio"
                        required>
                    @error('buildingStore.building')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col">
                    <input wire:model="buildingStore.campus_id" type="text" class="form-control" placeholder="{{ $campus->campus }}" disabled>
                </div>
                <div class="col opciones_boton">
                    <button class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </form>


        @if (empty($buildings))
            <div class="text-center mt-3">
                <h5>No existen registros.</h5>
            </div>
        @else
            <div class="table-responsive mt-3">
                <table class="table table-sm table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Edificio</th>
                            <th scope="col">Campus</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col" class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($buildings as $building)
                            <tr class="@if (!$building->active) table-danger @endif">
                                @if ($editBuilding !== $building->id)
                                    <td> {{ $building->building }} </td>
                                    <td> {{ $building->campus->campus }} </td>
                                    <td> {{ $building->campus->address }} </td>
                                    <td> {{ $building->campus->city }} </td>
                                @else
                                    <td>
                                        <input wire:model="buildingEdit.building" type="text"
                                            class="form-control @error('buildingEdit.building') is-invalid @enderror"
                                            required>
                                        @error('buildingEdit.building')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $campus->campus }}" disabled>
                                    </td>
                                @endif
                                <td>
                                    <div class="opciones_boton">
                                        @if ($editBuilding !== $building->id)
                                            <button wire:click="edit({{ $building->id }})" class="btn btn-warning"><i
                                                    class="bi bi-pencil-square text-dark"></i></button>
                                            @if ($building->active)
                                                <button wire:confirmation="¿Esta seguro de eliminar este Edificio?"
                                                    wire:click="delete({{ $building->id }})" class="btn btn-danger"><i
                                                        class="bi bi-trash3"></i></button>
                                            @else
                                                <button wire:click="setActive({{ $building->id }})"
                                                    class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                                            @endif
                                        @else
                                            <button wire:click="update" class="btn btn-success"><i
                                                    class="bi bi-check-lg"></i></button>
                                            <button wire:click="close" class="btn btn-secondary"><i
                                                    class="bi bi-x-lg"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</div>
