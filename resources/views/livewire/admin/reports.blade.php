<div>
    <div class="container mt-3">

        <div class="d-grid gap-2 d-md-flex justify-content-center">
            <button wire:click="reportPlace()" class="btn btn-primary">Generar reporte de un espacio</button>
            <button wire:click="reportDate()" class="btn btn-primary">Generar reporte por fecha</button>
        </div>

        @switch($option)
            @case('place')
                <div class="row row-cols-1 row-cols-md-6 justify-content-center mt-3">
                    <div class="col mb-2">
                        <label for="campusFilter"></label>
                        <select wire:model="campusFilter" wire:change="updatePlaces" class="form-select mb-0" id="campusFilter">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->id }}">{{ $campus->campus }}, {{ $campus->city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label for="dateFrom">Fecha desde:</label>
                        <input wire:model="dateFrom" id="dateFrom" type="date" class="form-control">
                    </div>
                    <div class="col mb-2">
                        <label for="dateTo">Fecha hasta:</label>
                        <input wire:model="dateTo" id="dateTo" type="date" class="form-control">
                    </div>
                </div>

                <div class="table-responsive mt-3">
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
                                    <tr class="@if (!$place->active) table-danger @endif">
                                        <td>{{ $place->code }}</td>
                                        <td>{{ $place->building->building }} - {{ $place->building->campus->campus }},
                                            {{ $place->building->campus->city }}</td>
                                        <td>
                                            <div class="opciones_boton">
                                                <a wire:click="downloadPlace({{ $place->id }}, '{{ $dateFrom }}', '{{ $dateTo }}')"
                                                    class="btn btn-danger" style="font-size: small; font-weight: bold;">
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
                <div class="row row-cols-1 row-cols-md-6 justify-content-center mt-3">
                    <div class="col mb-2">
                        <label for="campusFilter"></label>
                        <select wire:model="campusFilter" wire:change="updatePlaces" class="form-select mb-0" id="campusFilter">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->id }}">{{ $campus->campus }}, {{ $campus->city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label for="dateFrom">Fecha desde:</label>
                        <input wire:model="dateFrom" id="dateFrom" type="date" class="form-control">
                    </div>
                    <div class="col mb-2">
                        <label for="dateTo">Fecha hasta:</label>
                        <input wire:model="dateTo" id="dateTo" type="date" class="form-control">
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
