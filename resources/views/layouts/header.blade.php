<!-- resources/views/layouts/header.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RHNUBE PAYMENT</title>
    <link rel="shortcut icon" href="{{ asset('images/iconoLogoRhNubeActual.png') }}">
    <!-- Enlaces a Bootstrap CSS y estilos personalizados -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900%7cOswald:400,700%7cRaleway:300,400,400i,500,600,700,900" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <!-- Agrega esto en tu vista o layout -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="
        https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js
        "></script>
</head>

<body>
    <!-- Puedes personalizar el contenido del encabezado según tus necesidades -->
    <header>
        @if (Route::has('login'))
            <nav class="py-3 navbar justify-content-center navbar-expand-lg navbar-light bg-light">
                @auth

                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo del Sitio" class="logo" />
                    </a>
                    <!-- Agrega aquí tus elementos de navegación, por ejemplo, menú de navegación -->
                @else
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo del Sitio" class="logo" />
                    </a>

                    <!-- <a href="{{ route('login') }}">Login</a>

                                        @if (Route::has('register'))
    <a href="{{ route('register') }}">Register</a>
    @endif  -->
                @endauth
            </nav>
        @endif
    </header>
