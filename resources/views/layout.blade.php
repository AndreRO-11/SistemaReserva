<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @livewireStyles
</head>
<body>

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
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0 mx-auto nav-underline">
                            <li class="nav-item">
                                <a class="nav-link" href="/">ESPACIOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/reservation">RESERVAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/service">SERVICIOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/building">UBICACIONES</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="contenido">
        @livewire('buildings.show-building')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    @livewireScripts()

</body>
</html>
