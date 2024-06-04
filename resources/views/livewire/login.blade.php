<div>

    <div class="container">
        <div class="login_container mt-4">
            <div class="row row-cols-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Ingresar</h5>
                        <div class="col mt-2">
                            <label for="email">Correo electrónico</label>
                            <input wire:model="user.email" type="email" id="email" class="form-control">
                            @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mt-2">
                            <label for="password">Contraseña</label>
                            <input wire:model="user.password" type="password" id="password" class="form-control">
                            @error('password')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div wire:loading wire:target="login" class="spinner_container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                        </div>
                        <div class="col mt-4 opciones_boton">
                            <button wire:loading.attr="disabled" wire:click="login" type="submit" class="btn btn-primary">
                                Ingresar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
