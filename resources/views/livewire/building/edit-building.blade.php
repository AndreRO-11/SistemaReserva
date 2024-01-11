<div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBuilding">
            <i class="bi bi-pencil-square"></i>
        </button>
    </div>

    <div class="modal fade" id="editBuilding" tabindex="-1" aria-labelledby="editBuilding" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">

                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session() }}
                    </div>
                    @endif

                    <form wire:submit="update">
                        <div class="mx-auto">
                            <div class="col">
                                <div class="mt-2">
                                    <label class="form-label">Edificio</label>
                                    <input wire:model="building.building" class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Campus</label>
                                    <input wire:model="building.campus" class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Dirección</label>
                                    <input wire:model="building.address" class="form-control" type="text" required>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Ciudad</label>
                                    <input wire:model="building.city" class="form-control" type="text" required>
                                </div>
                                <div class="mt-3 opciones_boton">
                                    <button data-bs-dismiss="modal" class="btn btn-primary">
                                        Guardar
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
