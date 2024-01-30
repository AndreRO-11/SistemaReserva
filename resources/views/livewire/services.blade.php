<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-sm-1 row-cols-md-3 justify-content-center">
                <div class="col-md-5">
                    <input wire:model="serviceEdit.service" type="text" class="form-control" placeholder="Servicio" required>
                </div>
                <div class="col-md-5">
                    <textarea wire:model="serviceEdit.description" class="form-control" placeholder="Descripción"></textarea>
                </div>
                <div class="col-md-1 opciones_boton align-items-start">
                    <button class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </form>

        @if (empty($services))
        <div class="text-center mt-3">
            <h5>No existen registros.</h5>
        </div>
        @else
        <div class="table-responsive mt-3">
            <table class="table table-sm table-hover align-top">
                <thead>
                    <tr>
                        <th scope="col">Servicio</th>
                        <th scope="col">Descripción</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($services as $service)
                    <tr>
                        <td>
                            @if ($editService !== $service->id)
                            {{ $service->service }}
                            @else
                            <input wire:model="serviceEdit.service" type="text" class="form-control @error('serviceEdit.service') is-invalid @enderror" required>
                            @endif
                        </td>
                        <td>
                            @if ($editService !== $service->id)
                            {!!nl2br($service->description) !!}
                            @else
                            <textarea wire:model="serviceEdit.description" class="form-control" placeholder="Descripción"></textarea>
                            @endif
                        </td>
                        <td>
                            <div class="opciones_boton">
                                @if ($editService !== $service->id)
                                <button wire:click="edit({{ $service->id }})" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
                                <button wire:confirmation="¿Desea eliminar este Servicio?" wire:click="delete({{ $service->id }})" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
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
