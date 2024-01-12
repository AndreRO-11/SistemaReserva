<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        @vite(['/resources/css/app.css'])

        @livewireStyles

        {{-- <title>{{ $title ?? 'Page Title' }}</title> --}}
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
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span><i class="bi bi-list"></i></span>
                        </button>
                        <div class="container">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mb-2 mb-lg-0 mx-auto nav-underline">
                                    <li class="nav-item">
                                        <a wire:navigate class="nav-link" href="/places">ESPACIOS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:navigate class="nav-link" href="/services">SERVICIOS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:navigate class="nav-link" href="buildings">UBICACIONES</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:navigate class="nav-link" href="/seats">ASIENTOS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:navigate class="nav-link" href="/types">TIPO DE ESPACIOS</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <div class="contenido">
                {{ $slot }}
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

        @livewireScripts()

    </body>
</html>
