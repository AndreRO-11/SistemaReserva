<div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-4 justify-content-center">
                <div class="col mb-2">
                    <label for="campusFilter"></label>
                    <select wire:model="campusFilter" wire:change="filterByCampus" class="form-select mb-0"
                        id="campusFilter">
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
                <div class="col d-flex align-items-end mb-2 justify-content-center">
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

            <div class="table-responsive mt-2">
                <table class="table table-sm table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Ubicación</th>
                            <th class="text-center" scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($placesCount === 0)
                            <tr>
                                <td colspan="12" class="text-center">No existen espacios registrados.</td>
                            </tr>
                        @else
                            @foreach ($places as $place)
                                <tr class="@if (!$place->active) table-danger @endif">
                                    <td>{{ $place->code }}</td>
                                    <td>{{ $place->building->building }} - {{ $place->building->campus->campus }},
                                        {{ $place->building->campus->city }}</td>
                                    <td>
                                        <div class="opciones_boton">
                                            <button wire:loading.attr="disabled" wire:click="download({{ $place->id }}, '{{ $dateFrom }}', '{{ $dateTo }}')"
                                                class="btn btn-danger" style="font-weight: bold;">
                                                <span wire:loading wire:target="download({{ $place->id }}, '{{ $dateFrom }}', '{{ $dateTo }}')"
                                                    class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                                GENERAR PDF <i class="bi bi-filetype-pdf"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div wire:loading wire:target="filterByCampus" class="spinner_container">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2">
        {{ $places->links() }}
    </div>

</div>
