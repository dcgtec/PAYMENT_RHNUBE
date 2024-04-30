<?php
// Definición del array de menús
$menus = [
    [
        'name' => 'Mi información',
        'link' => 'perfil', // El enlace a la página de inicio
        'icon' => 'fas fa-user', // Nombre del icono de Ionicons
    ],
    [
        'name' => 'Mis referidos',
        'link' => 'referidos', // El enlace a la página de "Acerca de"
        'icon' => 'fas fa-users', // Icono para la sección "Acerca de"
    ],
    [
        'name' => 'Mis retiros',
        'link' => 'retiros', // El enlace a la página de habilidades
        'icon' => 'fas fa-wallet', // Icono para la sección de habilidades
    ],
    [
        'name' => 'Cerrar sesión',
        'link' => '', // El enlace a la página del portafolio
        'icon' => 'fas fa-sign-out-alt', // Icono para el portafolio
    ],
];

$propietarios = session()->get('detalleUusario');
$nombrePro = $propietarios['nombres'];
$nombre = explode(' ', $propietarios['nombres'])[0];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('influencers/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('influencers/css/layaouts.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <title>Menu responsive</title>
</head>



<body cz-shortcut-listen="true">
    <header class="header">
        <a href="#" class="header__logo"> <img src="{{ asset('influencers/images/logoRhnube.png') }}" alt=""></a>

        <ion-icon name="menu-outline" class="header__toggle" id="nav-toggle"></ion-icon>

        <nav class="nav d-block" id="nav-menu">
            <div class="nav__content bd-grid">

                <ion-icon name="close-outline" class="nav__close" id="nav-close"></ion-icon>

                <div class="nav__perfil">
                    <div>
                        <img src="{{ asset('influencers/images/logoRhnube.png') }}" alt="">
                    </div>
                </div>

                <div class="nav__menu">
                    {{-- <ul class="nav__list">
                        <li class="nav__item"><a href="#" class="nav__link active">Home</a></li>
                        <li class="nav__item"><a href="#" class="nav__link">About</a></li>
                        <li class="nav__item"><a href="#" class="nav__link">Skills</a></li>
                        <li class="nav__item"><a href="#" class="nav__link">Portfolio</a></li>
                        <li class="nav__item"><a href="#" class="nav__link">Contact</a></li>
                    </ul> --}}
                </div>

                <div class="nav__social">


                    {{-- <a href="#" class="nav__social-icon"><ion-icon name="logo-linkedin"></ion-icon></a>
                    <a href="#" class="nav__social-icon"><ion-icon name="logo-github"></ion-icon></a>
                    <a href="#" class="nav__social-icon"><ion-icon name="logo-behance"></ion-icon></a>
                 --}}


                </div>
            </div>
        </nav>
    </header>

    <main role="main" class="mb-5">


        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-5 perfilPort">
                    <img src="{{ asset('influencers/images/editPortada.png') }}" alt="imgPort">
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 perfilInf text-center">
                            <div class="perfilPhoto">
                                <input type="file" id="inputFile" accept=".png, .jpg, .jpeg" style="display: none;" />
                                <img src="{{ asset('influencers/images/editPerfil.png') }}" id="changeImgPerfil"
                                    alt="perfilPhoto">
                                <img src="{{ asset('influencers/images/savePerfil.png') }}" id="savemgPerfil"
                                    alt="savePhoto">
                            </div>

                            <div class="perfilName">
                                <h3>{{ $nombre }} {{ $propietarios['apellido_paterno'] }}</h3>
                            </div>

                            <!-- <div class="perfilRedesSociales">
                                <ul>
                                    <li class="py-1"> <img src="{{ asset('influencers/images/iconFacebook.png') }}" alt="perfilPhoto"> <span>@david.rodriguez2024</span> </li>
                                    <li class="py-1"> <img src="{{ asset('influencers/images/iconInstragram.png') }}" alt="perfilPhoto"> <span>@david.rodriguez2024</span></li>
                                    <li class="py-1"> <img src="{{ asset('influencers/images/iconTikTok.png') }}" alt="perfilPhoto">
                                        <span>@david.rodriguez2024</span>
                                    </li>
                                </ul>
                            </div> -->
                            <div class="perfilMenus mt-4">
                                @foreach ($menus as $menu)
                                {{-- Verifica si el enlace del menú coincide con la URL actual --}}
                                <a href="{{ url($menu['link']) }}">
                                    <button class="mb-4 align-items-center btn w-100 opciones d-flex
                                        @if (request()->path() == $menu['link']) active @endif">
                                        {{-- Agrega la clase 'active' si es el menú activo --}}

                                        <i class="ml-3 mr-2 {{ $menu['icon'] }}"></i>
                                        <span class="ml-1">{{ $menu['name'] }}</span>
                                    </button>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-9">

                            <script>
                            $("#changeImgPerfil").on("click", function() {
                                // Activa el campo de entrada para archivos (abre el diálogo de selección de archivos)
                                $("#inputFile").click();
                            });

                            $("#inputFile").on("change", function() {
                                const file = this.files[0];
                                if (file) {
                                    // Verificar el tipo de archivo
                                    const allowedExtensions = ["image/png", "image/jpeg"];
                                    if (!allowedExtensions.includes(file.type)) {
                                        alert("Por favor, selecciona solo archivos PNG, JPG, o JPEG.");
                                        this.value = ''; // Restablecer el campo de entrada
                                        return;
                                    }

                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const img = new Image();
                                        img.src = e.target.result;

                                        // Verificar que la imagen tenga dimensiones cuadradas
                                        img.onload = function() {
                                            if (img.width !== img.height) {
                                                alert(
                                                    "La imagen debe ser cuadrada (mismo ancho y alto)."
                                                );
                                                $("#inputFile").val(
                                                    ''); // Restablecer el campo de entrada
                                                return;
                                            }

                                            // Si la imagen es válida, establecer la imagen de fondo
                                            $(".perfilPhoto").css("background-image",
                                                `url(${e.target.result})`);

                                        };
                                    };
                                    reader.readAsDataURL(file); // Leer el archivo como datos URL
                                }
                            });
                            </script>