<div>

    <div class="container">
        <div class="login_center mt-4">
            <div class="card col-6">
                <div class="card-body">

                    <form wire:submit.prevent="login">
                        <h5 class="card-title">Ingresar</h5>
                        <div class="m-2 mt-4">
                            <label for="email">Correo electrónico</label>
                            <input wire:model="user.email" type="email" id="email" class="form-control">
                            @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="m-2">
                            <label for="password">Contraseña</label>
                            <input wire:model="user.password" type="password" id="password" class="form-control">
                            @error('password')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                Ingresar
                            </button>
                        </div>
                    </form>

                    {{-- @if (!$registerForm)
                        <div>
                            <h5 class="card-title">Ingresar</h5>
                            <div class="m-2 mt-4">
                                <label for="email">Correo electrónico</label>
                                <input wire:model="email" type="email" id="email" class="form-control">
                                @error('email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="m-2">
                                <label for="password">Contraseña</label>
                                <input wire:model="password" type="password" id="password" class="form-control">
                                @error('password')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div>
                            <h5 class="card-text">Registrarse</h5>
                        </div>
                    @endif

                    <div class="text-center mt-4">
                        <button wire:click="toggleRegisterForm" type="button" class="btn btn-primary">
                            Registrarse
                        </button>
                        <button wire:click="login"  type="button" class="btn btn-primary">
                            Ingresar
                        </button>
                    </div> --}}


                </div>
            </div>
        </div>
    </div>

</div>
