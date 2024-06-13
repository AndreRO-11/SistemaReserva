<div>

    <div class="card mt-2">
        <div class="card-body">
            @if ($campuses === null)
                <h5 class="text-center">No existen sedes registradas.</h5>
            @else
                <div class="row row-cols-1 row-cols-md-4 justify-content-center">
                    <div class="col mb-2">
                        <label for="campusFilter"></label>
                        <select wire:model="campusFilter" wire:change="filterByCampus" class="form-select mb-0"
                            id="campusFilter">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->id }}">{{ $campus->campus }}, {{ $campus->city }}
                                </option>
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

                <div class="opciones_boton mt-3">
                    <button wire:loading.attr="disabled" wire:click="download()" class="btn btn-danger" style="font-weight: bold;">
                        <span wire:loading wire:target="download()" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        GENERAR PDF <i class="bi bi-filetype-pdf"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>

</div>
