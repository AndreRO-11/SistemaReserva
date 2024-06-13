<div>

    <div class="login_container mt-4">
        <div class="row row-cols-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Actualizar contraseña</h5>
                    <input type="hidden" wire:model="token">
                    <div class="col mt-2">
                        <label for="email">Correo electrónico</label>
                        <input wire:model="email" type="email" id="email" class="form-control">
                        @error('email')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col mt-2">
                        <label for="password">Contraseña</label>
                        <input wire:model="password" type="password" id="password" class="form-control">
                        @error('password')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col mt-2">
                        <label for="password_confirmation">Confirme contraseña</label>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation" class="form-control">
                        @error('password_confirmation')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div wire:loading wire:target="resetPassword" class="spinner_container">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                    <div class="col mt-2 opciones_boton">
                        <button wire:loading.attr="disabled" wire:click="resetPassword" type="submit" class="btn btn-primary"
                            style="font-weight: bold;">
                            CAMBIAR CONTRASEÑA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
