<div>

    <div class="d-grid gap-2 d-md-flex justify-content-center">
        <div>
            <input wire:model="campus.campus" type="text"
                class="form-control @error('campus.campus') is-invalid @enderror" placeholder="Sede">
            @error('campus.campus')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <input wire:model="campus.address" type="text"
                class="form-control @error('campus.address') is-invalid @enderror" placeholder="Dirección">
            @error('campus.address')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <input wire:model="campus.city" type="text"
                class="form-control @error('campus.city') is-invalid @enderror" placeholder="Ciudad">
            @error('campus.city')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <button wire:click="store" class="btn btn-primary"
                    style="font-weight: bold;">AÑADIR</button>
            <button wire:click="filterByActive" class="btn btn-warning" style="font-weight: bold;">
                @if ($activeFilter)
                    <i class="bi bi-toggle-off text-dark"></i>
                @else
                    <i class="bi bi-toggle-on text-dark"></i>
                @endif
                ACTIVOS
            </button>
        </div>
    </div>

    <div class="card mt-2">
        <div class="table-responsive card-body">
            <table class="table table-sm table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Sede</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @if (count($campuses) === 0)
                        <tr>
                            <td colspan="12" class="text-center">No existen sedes registradas.</td>
                        </tr>
                    @else
                        @foreach ($campuses as $campus)
                            <tr class="@if (!$campus->active) table-danger @endif">
                                @if ($editCampus !== $campus->id)
                                    <td>{{ $campus->campus }}</td>
                                    <td>{{ $campus->address }}</td>
                                    <td>{{ $campus->city }}</td>
                                    <td>
                                        <div class="opciones_boton">
                                            <button wire:click="edit({{ $campus->id }})"
                                                class="btn btn-warning"><i
                                                    class="bi bi-pencil-square text-dark"></i></button>
                                            @if ($campus->active)
                                                <button wire:confirm="¿Está seguro de eliminar la sede seleccionada?"
                                                    wire:click="delete({{ $campus->id }})"
                                                    class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                            @else
                                                <button wire:click="setActive({{ $campus->id }})"
                                                    wire:confirm="¿Está seguro de activar la sede seleccionada?"
                                                    class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        <input wire:model="campusEdit.campus" type="text"
                                            class="form-control @error('campusEdit.campus') is-invalid @enderror">
                                        @error('campusEdit.campus')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input wire:model="campusEdit.address" type="text"
                                            class="form-control @error('campusEdit.address') is-invalid @enderror">
                                        @error('campusEdit.address')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input wire:model="campusEdit.city" type="text"
                                            class="form-control @error('campusEdit.city') is-invalid @enderror">
                                        @error('campusEdit.city')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
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
        {{ $campuses->links() }}
    </div>

</div>
