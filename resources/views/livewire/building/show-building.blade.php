<div>

    <div class="container mt-3">

        @livewire('building.create-building')

        <div class="table-responsive mt-2">
            @if ($buildings)
            <table class="table align-top">
                <thead>
                    <tr>
                        <th scope="col">Edificio</th>
                        <th scope="col">Campus</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($buildings as $building)
                    <tr wire:key="{{ $building->id }}">
                        <td>{{ $building->building }}</td>
                        <td>{{ $building->campus }}</td>
                        <td>{{ $building->address }}</td>
                        <td>{{ $building->city }}</td>
                        <td>
                            <div class="opciones_boton">
                                <livewire:building.edit-building :buildingId="$building->id" :key="$building->id">

                                {{$building->id}}


                                <button class="btn btn-danger btn_custom" onclick="return confirm('¿Estás seguro de eliminar esta ubicación?')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="mx-auto">
                <h5>No existen registros</h5>
            </div>
            @endif
        </div>

    </div>


</div>
