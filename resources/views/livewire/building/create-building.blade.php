<div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBuilding">
            Añadir Edificio
        </button>
    </div>

    <div class="modal fade" id="createBuilding" tabindex="-1" aria-labelledby="createBuilding" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">

                    <form wire:submit="save">
                        <div class="mx-auto">
                            <div class="col">
                                <div class="mt-2">
                                    <label class="form-label">Edificio</label>
                                    <input wire:model="building" class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Campus</label>
                                    <input wire:model="campus" class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Dirección</label>
                                    <input wire:model="address" class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Ciudad</label>
                                    <input wire:model="city" class="form-control" type="text" required>
                                </div>
                                <div class="mt-3 opciones_boton">
                                    <button class="btn btn-primary">
                                        {{-- data-bs-dismiss="modal" --}}
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
