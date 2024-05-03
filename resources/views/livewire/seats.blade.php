<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-sm-1 row-cols-md-5 justify-content-center">
                <div class="col">
                    <input wire:model="seat" type="text" class="form-control @error('seat') is-invalid @enderror" placeholder="Tipo de asiento" required>
                    @error('seat')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col opciones_boton">
                    <button class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </form>

        @if (empty($seats))
            <div class="text-center mt-3">
                <h5>No existen registros.</h5>
            </div>
        @else
            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Tipo de asiento</th>
                            <th scope="col" class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
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
                                            <button wire:click="edit({{ $seat->id }})" class="btn btn-warning"><i
                                                    class="bi bi-pencil-square text-dark"></i></button>
                                            @if ($seat->active)
                                                <button
                                                    wire:confirmation="¿Esta seguro de eliminar este tipo de asiento?"
                                                    wire:click="delete({{ $seat->id }})" class="btn btn-danger"><i
                                                        class="bi bi-trash3"></i></button>
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
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</div>
