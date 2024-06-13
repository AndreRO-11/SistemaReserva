<div>

    <div class="login_container mt-4">
        @if (!$forgotPassword)
            <div class="row row-cols-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Ingresar</h5>
                        <div class="col mt-2">
                            <label for="email">Correo electrónico</label>
                            <input wire:model="user.email" type="email" id="email" class="form-control">
                            @error('user.email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col mt-2">
                            <label for="password">Contraseña</label>
                            <input wire:model="user.password" type="password" id="password" class="form-control">
                            @error('user.password')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="d-flex justify-content-end">
                            <p><a wire:click="$toggle('forgotPassword')" class="link-opacity-100" href="#">¿Olvidó
                                    su contraseña?</a></p>
                        </div>
                        <div wire:loading wire:target="login" class="spinner_container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                        </div>
                        <div class="col mt-2 opciones_boton">
                            <button wire:loading.attr="disabled" wire:click="login" type="submit"
                                class="btn btn-primary" style="font-weight: bold;">
                                INGRESAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($forgotPassword)
            <div class="row row-cols-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Olvidé mi contraseña</h5>
                        <div class="col mt-2">
                            <label for="email">Correo electrónico</label>
                            <input wire:model="email" type="email" id="email" class="form-control">
                            @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:loading wire:target="sendPasswordResetLink" class="spinner_container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                        </div>
                        <div class="col mt-2 opciones_boton">
                            <button wire:loading.attr="disabled" wire:click="sendPasswordResetLink" type="submit"
                                class="btn btn-primary" style="font-weight: bold;">
                                ENVIAR CORREO
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
