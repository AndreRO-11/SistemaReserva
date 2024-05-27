<div>

    <div class="container mt-3">

        <form wire:submit="store">
            <div class="row row-cols-1 row-cols-md-4 justify-content-center">
                <div class="col mb-2">
                    <input wire:model="campus.campus" type="text"
                        class="form-control @error('campus.campus') is-invalid @enderror" placeholder="Campus">
                    @error('campus.campus')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-2">
                    <input wire:model="campus.address" type="text"
                        class="form-control @error('campus.address') is-invalid @enderror" placeholder="Dirección">
                    @error('campus.address')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-2">
                    <input wire:model="campus.city" type="text"
                        class="form-control @error('campus.city') is-invalid @enderror" placeholder="Ciudad">
                    @error('campus.city')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-2 opciones_boton">
                    <button class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </form>

        <div class="card mt-3">
            @if ($campuses === null)
                <div class="text-center">
                    <h5>No existen sedes registradas.</h5>
                </div>
            @else
                <div class="table-responsive card-body">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Campus</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Ciudad</th>
                                <th scope="col" class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
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
                                                    <button wire:confirmation="¿Esta seguro de eliminar este campus?"
                                                        wire:click="delete({{ $campus->id }})"
                                                        class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                                @else
                                                    <button wire:click="setActive({{ $campus->id }})"
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
                        </tbody>
                    </table>
                </div>
        </div>
        @endif

    </div>

</div>
