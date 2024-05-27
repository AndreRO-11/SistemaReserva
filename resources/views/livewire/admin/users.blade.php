<div>
    <div class="container">

        <form wire:submit="register">
            <div class="row row-cols-1 row-cols-md-5 justify-content-center mt-3">
                <div class="col mb-2">
                    <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nombre" required>
                </div>
                <div class="col mb-2">
                    <input wire:model="email" type="text" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Correo" required>
                </div>
                <div class="col mb-2">
                    <input wire:model="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" required>
                    @error('password')
                        <span class="error text-danger">Mínimo 6 caracteres.</span>
                    @enderror
                </div>
                <div class="col mb-2">
                    <select wire:model='campus_id' class="form-select @error('campus_id') is-invalid @enderror"
                        required>
                        <option value="">Campus</option>
                        @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->campus }}, {{ $campus->city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="opciones_boton mb-2">
                    <button class="btn btn-primary">Añadir Usuario</button>
                </div>
            </div>
        </form>

        <div class="card mt-3">
            <div class="table-responsive card-body">
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
                            <th scope="col" class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($users as $user)
                            <tr class="@if (!$user->active) table-danger @endif">
                                @if ($editUser !== $user->id)
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    @if ($changePassword && $auth->id === $user->id)
                                        <td>
                                            <input wire:model="newPassword" type="text"
                                                class="form-control @error('newPassword') is-invalid @enderror" required>
                                        </td>
                                    @endif
                                    <td>{{ $user->campus->campus }}, {{ $user->campus->city }}</td>
                                @else
                                    <td>
                                        <input wire:model="user.name" type="text"
                                            class="form-control @error('user.name') is-invalid @enderror" required>
                                    </td>
                                    <td>
                                        <input wire:model="user.email" type="text"
                                            class="form-control @error('user.email') is-invalid @enderror" required>
                                        @error('user.email')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <select wire:model='user.campus_id'
                                            class="form-select @error('user.campus_id') is-invalid @enderror" required>
                                            @foreach ($campuses as $campus)
                                                <option value="{{ $campus->id }}">{{ $campus->campus }},
                                                    {{ $campus->city }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif

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
                                                    <button wire:click="close" class="btn btn-secondary"><i
                                                            class="bi bi-x-lg"></i></button>
                                                @endif
                                                <button wire:click="edit({{ $user->id }})" class="btn btn-warning"><i
                                                        class="bi bi-pencil-square text-dark"></i></button>
                                            @endif

                                            @if (!$user->active)
                                                <button wire:click="setActive({{ $user->id }})"
                                                    class="btn btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @else
                                                @if (!$changePassword)
                                                    <button wire:click="setInactive({{ $user->id }})"
                                                        class="btn btn-danger">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                @endif
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

</div>
