<?php
// Definición del array de menús
$menus = [
    [
        'name' => 'Mi perfil',
        'link' => 'pl?cid=' . $cid, // El enlace a la página de inicio
        'icon' => 'fas fa-user', // Nombre del icono de Ionicons
        'estado' => 1,
    ],
];

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
    <title>Influencer - RHNUBE</title>
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
                    <ul class="nav__list">

                        @foreach ($menus as $menu)
                        @if ($menu['estado'] == 1)
                        <li class="nav__item"><a href="{{ url($menu['link']) }}"
                                class="nav__link  active ">{{ $menu['name'] }}</a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>

                <div class="nav__social">

                    <a href="#" class="nav__social-icon"><img src="../../influencers/images/imgDefault.png" alt="">
                        <label for="" class="nombrePer">Nuevo usuario</label></a>
                </div>
            </div>
        </nav>
    </header>

    <main role="main" class="mb-5">


        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-5 perfilPort"
                    style="background-image: url(../../influencers/images/imgPortada.png)">
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 perfilInf text-center">
                            <div class="perfilPhoto"
                                style="background-image: url('../../influencers/images/imgDefault.png')">

                            </div>

                            <div class="perfilName">
                                <h3>Nuevo usuario</h3>
                            </div>

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