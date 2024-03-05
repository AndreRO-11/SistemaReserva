<div>
    <div class="container mt-3">

        <div class="opciones_boton mb-3">
            <button wire:click="reportPlace()" class="btn btn-primary">Generar reporte de un espacio</button>
            <button wire:click="reportDate()" class="btn btn-primary">Generar reporte por fecha</button>
        </div>

        @switch($option)
        @case('place')
            <div class="opciones_boton row">
                <div class="col-2">
                    <label for="dateFrom">Fecha desde:</label>
                    <input wire:model="dateFrom" id="dateFrom" type="date" class="form-control">
                </div>
                <div class="col-2">
                    <label for="dateTo">Fecha hasta:</label>
                    <input wire:model="dateTo" id="dateTo" type="date" class="form-control">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Ubicación</th>
                            <th class="text-center" scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$places)
                            <div class="mx-auto">
                                <h5>No se encuentran espacios registrados.</h5>
                            </div>
                        @else
                            @foreach ($places as $place)
                                <tr>
                                    <td>{{ $place->code }}</td>
                                    <td>{{ $place->building->building }} - {{ $place->building->campus }}, {{ $place->building->city }}</td>
                                    <td>
                                        <div class="opciones_boton">
                                            <a wire:click="downloadPlace({{ $place->id }}, '{{ $dateFrom }}', '{{ $dateTo }}')" class="btn btn-danger" style="font-size: small; font-weight: bold;">
                                                GENERAR PDF </i><i class="bi bi-filetype-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @break

        @case('date')
            <div class="opciones_boton row">
                <div class="col-2">
                    <label for="dateFrom">Fecha desde:</label>
                    <input wire:model="dateFrom" id="dateFrom" type="date" class="form-control">
                </div>
            </div>

            <div class="opciones_boton" style="margin-top: 30px">
                <a wire:click="downloadDates()" class="btn btn-danger" style="font-size: small; font-weight: bold;">
                    GENERAR PDF <i class="bi bi-filetype-pdf"></i>
                </a>
            </div>

            @break

    @endswitch

    </div>
</div>
