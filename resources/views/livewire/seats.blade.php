<div>

    <div class="d-grid gap-2 d-md-flex justify-content-center">
        <div>
            <input wire:model="seat" type="text" class="form-control @error('seat') is-invalid @enderror"
                placeholder="Tipo de asiento" required>
            @error('seat')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <button wire:click="store" class="btn btn-primary" style="font-weight: bold;">AÑADIR</button>
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

    <div class="card mt-3">
        <div class="table-responsive card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Tipo de asiento</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @if (count($seats) === 0)
                        <tr>
                            <td colspan="12" class="text-center">No se han registrado tipos de asientos.</td>
                        </tr>
                    @else
                        @foreach ($seats as $seat)
                            <tr class="@if (!$seat->active) table-danger @endif">
                                <td>
                                    @if ($editSeat !== $seat->id)
                                        {{ $seat->seat }}
                                    @else
                                        <input wire:model="seatEdit" type="text"
                                            class="form-control @error('seatEdit') is-invalid @enderror" required>
                                        @error('seatEdit')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </td>
                                <td>
                                    <div class="opciones_boton">
                                        @if ($editSeat !== $seat->id)
                                            <button wire:click="edit({{ $seat->id }})"
                                                class="btn btn-warning"><i
                                                    class="bi bi-pencil-square text-dark"></i></button>
                                            @if ($seat->active)
                                                <button
                                                    wire:confirmation="¿Esta seguro de eliminar este tipo de asiento?"
                                                    wire:click="delete({{ $seat->id }})"
                                                    class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                            @else
                                                <button wire:click="setActive({{ $seat->id }})"
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
        {{ $seats->links() }}
    </div>

</div>
