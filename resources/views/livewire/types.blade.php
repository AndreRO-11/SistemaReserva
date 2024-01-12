<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-sm-1 row-cols-md-5 justify-content-center">
                <div class="col">
                    <input wire:model="type" type="text" class="form-control" placeholder="Tipo de espacio" required>
                </div>
                <div class="col opciones_boton">
                    <button class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </form>

        @if (empty($types))
        <div class="text-center mt-3">
            <h5>No existen registros.</h5>
        </div>
        @else
        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Tipo de espacio</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($types as $type)
                    <tr>
                        <td>
                            @if ($editType !== $type->id)
                            {{ $type->type }}
                            @else
                            <input wire:model="type" type="text" class="form-control @error('type') is-invalid @enderror" required>
                            @endif
                        </td>

                        <td>
                            <div class="opciones_boton">
                                @if ($editType !== $type->id)
                                <button wire:click="edit({{ $type->id }})" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
                                <button wire:click="delete({{ $type->id }})" wire:confirm="¿Esta seguro de eliminar este tipo de espacio?" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                @else
                                <button wire:click="update" class="btn btn-success"><i class="bi bi-check-lg"></i></button>
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
