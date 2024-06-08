<div>

    <div class="container mt-3">

        <div class="justify-content-center row row-cols-1 row-cols-md-1 row-cols-lg-2">
            <div class="col mt-2">
                <input wire:model="type" type="text" class="form-control @error('type') is-invalid @enderror"
                    placeholder="Tipo de espacio" required>
                @error('type')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col opciones_boton mt-2">
                <button wire:click="store" class="btn btn-primary">Añadir</button>
                <button wire:click="filterByActive" class="btn btn-warning">
                    @if (!$activeFilter)
                        <i class="bi bi-toggle-off text-dark"></i>
                    @else
                        <i class="bi bi-toggle-on text-dark"></i>
                    @endif
                    VER TODO
                </button>
            </div>
        </div>

        <div>
            <div class="card mt-3">
                <div class="table-responsive card-body">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Tipo de espacio</th>
                                <th scope="col" class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @if (count($types) === 0)
                                <tr>
                                    <td colspan="12" class="text-center">No se han registrado tipos de espacio.</td>
                                </tr>
                            @else
                                @foreach ($types as $type)
                                    <tr class="@if (!$type->active) table-danger @endif">
                                        <td>
                                            @if ($editType !== $type->id)
                                                {{ $type->type }}
                                            @else
                                                <input wire:model="typeEdit" type="text"
                                                    class="form-control @error('typeEdit') is-invalid @enderror"
                                                    required>
                                                @error('typeEdit')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            @endif
                                        </td>

                                        <td>
                                            <div class="opciones_boton">
                                                @if ($editType !== $type->id)
                                                    <button wire:click="edit({{ $type->id }})"
                                                        class="btn btn-warning"><i
                                                            class="bi bi-pencil-square text-dark"></i></button>

                                                    @if ($type->active)
                                                        <button
                                                            wire:confirmation="¿Esta seguro de eliminar este tipo de espacio?"
                                                            wire:click="delete({{ $type->id }})"
                                                            class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                                    @else
                                                        <button wire:click="setActive({{ $type->id }})"
                                                            class="btn btn-success"><i
                                                                class="bi bi-check-lg"></i></button>
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
                {{ $types->links() }}
            </div>

        </div>

    </div>
