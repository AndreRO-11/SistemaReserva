<div>

    <div class="container">
        <div class="login_center">
            <div class="card col-6">
                <div class="card-body">

                    <form wire:submit.prevent="login">
                        <h5 class="card-title">Ingresar</h5>
                        <div class="m-2 mt-4">
                            <label for="email">Correo electrónico</label>
                            <input wire:model="email" type="email" id="email" class="form-control">
                            @error('email') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="m-2">
                            <label for="password">Contraseña</label>
                            <input wire:model="password" type="password" id="password" class="form-control">
                            @error('password') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                Ingresar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
