<div>
    <div class="container">

        <form wire:submit="register">
            <div class="row row-cols-sm-1 row-cols-md-5 justify-content-center mt-3">
                <div class="col">
                    <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nombre" required>
                </div>
                <div class="col">
                    <input wire:model="email" type="text" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Correo" required>
                </div>
                <div class="col">
                    <input wire:model="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" required>
                    @error('password')
                        <span class="error text-danger">Mínimo 6 caracteres.</span>
                    @enderror
                </div>
                <div class="col">
                    <input wire:model="campus" type="text" class="form-control @error('campus') is-invalid @enderror"
                        placeholder="Campus" required>
                </div>
                <div class="col">
                    <select wire:model='city' class="form-select @error('city') is-invalid @enderror" required>
                        <option value="">Ciudad</option>
                        <option value="CHILLAN">CHILLAN</option>
                        <option value="CONCEPCION">CONCEPCION</option>
                    </select>
                </div>
                <div class="col opciones_boton m-2">
                    <button class="btn btn-primary">Añadir Usuario</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        @if ($changePassword)
                            <th scope="col">
                                Contraseña
                            </th>
                        @endif
                        <th scope="col">Campus</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $user)
                        <tr class="@if (!$user->active) table-danger @endif">

                            <td>
                                @if ($editUser !== $user->id)
                                    {{ $user->name }}
                                @else
                                    <input wire:model="user.name" type="text"
                                        class="form-control @error('user.name') is-invalid @enderror" required>
                                @endif
                            </td>
                            <td>
                                @if ($editUser !== $user->id)
                                    {{ $user->email }}
                                @else
                                    <input wire:model="user.email" type="text"
                                        class="form-control @error('user.email') is-invalid @enderror" required>
                                @endif
                            </td>
                            @if ($changePassword && $auth->id === $user->id)
                                <td>
                                    <input wire:model="newPassword" type="text"
                                        class="form-control @error('newPassword') is-invalid @enderror" required>
                                </td>
                            @endif
                            <td>
                                @if ($editUser !== $user->id)
                                    {{ $user->campus }}
                                @else
                                    <input wire:model="user.campus" type="text"
                                        class="form-control @error('user.campus') is-invalid @enderror" required>
                                @endif
                            </td>
                            <td>
                                @if ($editUser !== $user->id)
                                    {{ $user->city }}
                                @else
                                    <select wire:model='user.city'
                                        class="form-select @error('user.city') is-invalid @enderror" required>
                                        <option value="CHILLAN">CHILLAN</option>
                                        <option value="CONCEPCION">CONCEPCION</option>
                                    </select>
                                @endif
                            </td>
                            <td>
                                <div class="opciones_boton">
                                    @if ($editUser !== $user->id)
                                        @if ($auth->email === $user->email)
                                            @if (!$changePassword)
                                                <button wire:click="toggleChangePassword" class="btn btn-primary">
                                                    <i class="bi bi-key-fill"></i>
                                                </button>
                                            @else
                                                <button wire:click="setPassword" class="btn btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif
                                        @endif
                                        <button wire:click="edit({{ $user->id }})" class="btn btn-warning"><i
                                                class="bi bi-pencil-square text-dark"></i></button>

                                        @if (!$user->active)
                                            <button wire:click="setActive({{ $user->id }})" class="btn btn-success">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @else
                                            <button wire:click="setInactive({{ $user->id }})"
                                                class="btn btn-danger">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    @else
                                        <button wire:click="update" class="btn btn-success"><i
                                                class="bi bi-check-lg"></i></button>
                                        <button wire:click="close" class="btn btn-secondary"><i
                                                class="bi bi-x-lg"></i></button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
