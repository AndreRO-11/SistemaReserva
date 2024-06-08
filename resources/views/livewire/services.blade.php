<div>

    <div class="container mt-3">

        <div class="row row-cols-1 row-cols-md-3 justify-content-center">
            <div class="col mb-2">
                <input wire:model="service.service" type="text"
                    class="form-control @error('service.service') is-invalid @enderror" placeholder="Servicio" required>
                @error('service.service')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col mb-2">
                <textarea wire:model="service.description" class="form-control" placeholder="Descripción"></textarea>
            </div>
            <div class="col opciones_boton align-items-start">
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

        <div class="card mt-3">
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
                        @if (count($services) === 0)
                            <tr>
                                <td colspan="12" class="text-center">No existen servicios registrados.</td>
                            </tr>
                        @else
                            @foreach ($services as $service)
                                <tr class="@if (!$service->active) table-danger @endif">
                                    @if ($editService !== $service->id)
                                        <td>{{ $service->service }}</td>
                                        <td>{!! nl2br($service->description) !!}</td>
                                        <td>
                                            <div class="opciones_boton">
                                                <button wire:click="edit({{ $service->id }})"
                                                    class="btn btn-warning"><i
                                                        class="bi bi-pencil-square text-dark"></i></button>
                                                @if ($service->active)
                                                    <button wire:confirmation="¿Desea eliminar este Servicio?"
                                                        wire:click="delete({{ $service->id }})"
                                                        class="btn btn-danger"><i class="bi bi-trash3"></i></button>
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
                                            @error('serviceEdit.service')
                                                <span class="error text-danger">{{ $message }}</span>
                                            @enderror
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
            {{ $services->links() }}
        </div>

    </div>

</div>
