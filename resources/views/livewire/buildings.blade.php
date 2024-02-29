<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-sm-1 row-cols-md-5 justify-content-center">
                <div class="col">
                    <input wire:model="buildingEdit.building" type="text" class="form-control" placeholder="Edificio" required>
                </div>
                <div class="col">
                    <input wire:model="buildingEdit.campus" type="text" class="form-control" placeholder="Campus" required>
                </div>
                <div class="col">
                    <input wire:model="buildingEdit.address" type="text" class="form-control" placeholder="Dirección" required>
                </div>
                <div class="col">
                    <input wire:model="buildingEdit.city" type="text" class="form-control" placeholder="Ciudad" required>
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
                    <tr>
                        <td>
                            @if ($editBuilding !== $building->id)
                            {{ $building->building }}
                            @else
                            <input wire:model="buildingEdit.building" type="text" class="form-control @error('buildingEdit.building') is-invalid @enderror" required>
                            @endif
                        </td>
                        <td>
                            @if ($editBuilding !== $building->id)
                            {{ $building->campus }}
                            @else
                            <input wire:model="buildingEdit.campus" type="text" class="form-control @error('buildingEdit.campus') is-invalid @enderror" required>
                            @endif
                        </td>
                        <td>
                            @if ($editBuilding !== $building->id)
                            {{ $building->address }}
                            @else
                            <input wire:model="buildingEdit.address" type="text" class="form-control @error('buildingEdit.address') is-invalid @enderror" required>
                            @endif
                        </td>
                        <td>
                            @if ($editBuilding !== $building->id)
                            {{ $building->city }}
                            @else
                            <input wire:model="buildingEdit.city" type="text" class="form-control @error('buildingEdit.city') is-invalid @enderror" required>
                            @endif
                        </td>
                        <td>
                            <div class="opciones_boton">
                                @if ($editBuilding !== $building->id)
                                <button wire:click="edit({{ $building->id }})" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
                                <button wire:confirmation="¿Esta seguro de eliminar este Edificio?" wire:click="delete({{ $building->id }})" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                @else
                                <button wire:click="update" class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                                <button wire:click="close" class="btn btn-secondary"><i class="bi bi-x-lg"></i></button>
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
