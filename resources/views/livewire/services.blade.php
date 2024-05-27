<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-1 row-cols-md-3 justify-content-center">
                <div class="col mb-2">
                    <input wire:model="service.service" type="text" class="form-control" placeholder="Servicio" required>
                </div>
                <div class="col mb-2">
                    <textarea wire:model="service.description" class="form-control" placeholder="Descripción"></textarea>
                </div>
                <div class="col-md-1 opciones_boton align-items-start">
                    <button class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </form>

        <div class="card mt-3">
            @if ($services === null)
            <div class="text-center">
                <h5>No existen servicios registrados.</h5>
            </div>
        @else
            <div class="table-responsive card-body">
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
                            <tr class="@if (!$service->active) table-danger @endif">
                                @if ($editService !== $service->id)
                                    <td>{{ $service->service }}</td>
                                    <td>{!! nl2br($service->description) !!}</td>
                                    {{-- <td>{{ $service->description }}</td> --}}
                                    <td>
                                        <div class="opciones_boton">
                                            <button wire:click="edit({{ $service->id }})" class="btn btn-warning"><i
                                                    class="bi bi-pencil-square text-dark"></i></button>
                                            @if ($service->active)
                                                <button wire:confirmation="¿Desea eliminar este Servicio?"
                                                    wire:click="delete({{ $service->id }})" class="btn btn-danger"><i
                                                        class="bi bi-trash3"></i></button>
                                            @else
                                                <button wire:click="setActive({{ $service->id }})"
                                                    class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        <input wire:model="serviceEdit.service" type="text"
                                            class="form-control @error('serviceEdit.service') is-invalid @enderror"
                                            required>
                                    </td>
                                    <td>
                                        <textarea wire:model="serviceEdit.description" class="form-control" placeholder="Descripción"></textarea>
                                    </td>
                                    <td>
                                        <div class="opciones_boton">
                                            <button wire:click="update" class="btn btn-success"><i
                                                    class="bi bi-check-lg"></i></button>
                                            <button wire:click="close" class="btn btn-secondary"><i
                                                    class="bi bi-x-lg"></i></button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>

    </div>

</div>
