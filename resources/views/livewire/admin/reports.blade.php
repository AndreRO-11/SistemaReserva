<div>

    <div class="d-grid gap-2 d-md-flex justify-content-center">
        <button wire:click="reportPlace()" class="btn btn-primary" style="font-weight: bold;">REPORTE POR ESPACIO</button>
        <button wire:click="reportDate()" class="btn btn-primary" style="font-weight: bold;">REPORTE POR FECHA</button>
    </div>

    @switch($option)
        @case('place')
            @livewire('admin.report.reports-by-places')
        @break

        @case('date')
            @livewire('admin.report.reports-by-dates')
        @break
    @endswitch

    <div wire:loading class="spinner_container">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

</div>
