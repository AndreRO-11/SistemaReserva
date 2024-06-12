<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @vite(['/resources/css/app.css'])

    @livewireStyles

    <title>Sistema de reservas VRIP</title>

    <link rel="icon" href="images/escudo-icono.png">

</head>

<body>

    <div>
        <header class="header fixed-top">
            <div class="container_header">
                <a href="https://vrip.ubiobio.cl/inicio/">
                    <img src="{{ asset('images/Logo_VRIP.png') }}" alt="">
                </a>
                <img src="{{ asset('images/escudo-color-gradiente.png') }}" alt="">
            </div>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span style="color: white;"><i class="bi bi-list"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        @auth
                            <ul class="navbar-nav mb-2 mb-lg-0 m-auto">
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/users">USUARIOS</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/reports">REPORTES</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/reservations">RESERVAS</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/places">ESPACIOS</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/services">SERVICIOS</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="buildings">EDIFICIOS</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="campus">SEDES</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/seats">ASIENTOS</a>
                                </li>
                                <li class="nav-item">
                                    <a wire:navigate class="nav-link" href="/types">TIPO DE ESPACIOS</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/logout" class="btn"><i class="bi bi-door-closed"></i></a>
                                </li>
                            </ul>
                        @endauth
                        @guest
                            <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                                <li class="nav-item ms-auto">
                                    <a href="/login" class="btn" style="font-size: small">INICIAR SESIÓN</i></a>
                                </li>
                            </ul>
                        @endguest
                    </div>
                </div>
            </nav>
        </header>

        <div class="container">
            {{ $slot }}
        </div>

        <footer class="footer fixed-bottom">
            <div class="container_footer">
                @auth
                    <p style="color: white">Usuario: {{ Auth::user()->name }} - {{ Auth::user()->email }}</p>
                @endauth
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        // TOASTS
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('success', function(message) {
                Toastify({
                    text: message,
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: 'top',
                    position: 'right',
                    stopOnFocus: true,
                    style: {
                        background: '#20c997'
                    },
                }).showToast();
            });

            Livewire.on('failed', function(message) {
                Toastify({
                    text: message,
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: 'top',
                    position: 'right',
                    stopOnFocus: true,
                    style: {
                        background: '#dc3545'
                    },
                }).showToast();
            });

            Livewire.on('warning', function(message) {
                Toastify({
                    text: message,
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: 'top',
                    position: 'right',
                    stopOnFocus: true,
                    style: {
                        background: '#ffc107'
                    },
                }).showToast();
            });
        });
    </script>

    @livewireScripts()

</body>

</html>
