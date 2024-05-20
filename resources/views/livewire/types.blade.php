<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-sm-1 row-cols-md-5 justify-content-center">
                <div class="col-8">
                    <input wire:model="type" type="text" class="form-control @error('type') is-invalid @enderror"
                        placeholder="Tipo de espacio" required>
                    @error('type')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 opciones_boton">
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
                            <tr class="@if (!$type->active) table-danger @endif">
                                <td>
                                    @if ($editType !== $type->id)
                                        {{ $type->type }}
                                    @else
                                        <input wire:model="typeEdit" type="text"
                                            class="form-control @error('typeEdit') is-invalid @enderror" required>
                                        @error('typeEdit')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </td>

                                <td>
                                    <div class="opciones_boton">
                                        @if ($editType !== $type->id)
                                            <button wire:click="edit({{ $type->id }})" class="btn btn-warning"><i
                                                    class="bi bi-pencil-square text-dark"></i></button>

                                            @if ($type->active)
                                                <button
                                                    wire:confirmation="¿Esta seguro de eliminar este tipo de espacio?"
                                                    wire:click="delete({{ $type->id }})" class="btn btn-danger"><i
                                                        class="bi bi-trash3"></i></button>
                                            @else
                                                <button wire:click="setActive({{ $type->id }})"
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
