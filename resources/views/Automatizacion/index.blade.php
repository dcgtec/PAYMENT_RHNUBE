<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RH nube - Tiempo, tareas y asistencia</title>

    <!--- LIBRERIAS -->
    <link rel="stylesheet" href="{{ asset('automatizacionv1/css/bootstrap.min.css') }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,600,700|Lato:300,400,500,600,700" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('automatizacionv1/img/icono-logo.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        /*Barra de progreso para la carga de empleados*/

        label.chanTextLoa {
            font-size: 16px;
            color: #8d9cb2;
            font-weight: 600;
        }

        .progress-bar.bg-success-bar {
            background-color: #33cc99 !important;
            border-radius: 20px !important;
        }

        img.changImgLoad {
            width: 90% !important;
            height: auto !important;
        }

        @media screen and (max-width: 767px) {
            body.m-0.pl-5.pr-5.row.vh-100.justify-content-center.align-items-center {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }

        body,
        html {
            color: #7a7a7a;
            font-family: Poppins, Arial, Helvetica, sans-serif;
        }

        #panel-left {
            background-color: #e7f4fd;
            height: 100vh;
        }

        #header {
            text-align: center;
        }

        #header img {
            width: 230px;
        }

        footer {
            color: #fff;
            font-size: 10px;
        }

        h4.titleModalidad {
            color: #00aafa;
            font-weight: 600 !important;
        }

        .btn-primary,
        .btn-success,
        .btn-secondary {
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 20px;
            padding-right: 20px;
            font-size: 15px;
            border-radius: 25px;
        }

        .btn-primary {
            background-color: #00aafa;
            border: none;
        }

        .btn-secondary {
            background-color: #8d9cb2;
            border: none;
        }

        #wrap {
            height: 100vh;
            background: rgb(255, 255, 255);
            background: linear-gradient(90deg, rgba(255, 255, 255, 1) 0%, rgba(243, 249, 254, 1) 100%);
        }

        * html #wrap {
            height: 100vh;
        }

        #inner-wrap {
            display: flex;
            align-items: center;
            min-height: 76vh;
        }

        * html #inner-wrap {
            height: 100vh;
        }

        #header {
            width: 100%;
            position: relative;
            top: 0px;
        }

        .form-control,
        form .form-group {
            font-size: 13px;
            border-radius: 19px;
        }

        #footer {
            position: absolute;
            bottom: 0;
            width: 100%;

            font-size: 10px;
            text-align: center;
        }

        #content {
            height: 100%;
            text-align: center;
            width: 100%;
        }

        .formDev {
            width: 360px;
            margin: 0 auto;
        }

        form i {
            margin-left: -30px;
            cursor: pointer;
        }

        #password,
        #passwordConfR {
            display: unset;
        }

        .bi-eye-slash::before {
            position: relative;
            bottom: 3px;
        }

        .form-2 .formDev {
            width: 350px;
            margin: 0 auto;
        }

        .form-3 .formDev {
            width: 599px;
            margin: 0 auto;
        }

        .person {
            position: absolute;
            bottom: 0;
            left: 15%;
        }

        div#panel-left img.person-1,
        div#panel-left img.person-2 {
            width: 70vh;
        }

        /*Switch*/

        .switches-container {
            width: 210px;
            position: relative;
            display: flex;
            padding: 0;
            position: relative;
            background: #fff;
            line-height: 3rem;
            border-radius: 3rem;
            height: 50px;
            margin-left: auto;
            margin-right: auto;
            font-size: 15px;
            border: 1px solid #00aafa;
        }

        .switches-container input {
            visibility: hidden;
            position: absolute;
            top: 0;
        }

        .switches-container label {
            width: 50%;
            padding: 0;
            margin: 0;
            text-align: center;
            cursor: pointer;
            color: #00aafa;
            font-weight: 600;
        }

        .switch-wrapper {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            padding: 0.15rem;
            z-index: 3;
            transition: transform 0.5s cubic-bezier(0.77, 0, 0.175, 1);
            /* transition: transform 1s; */
        }

        .switch {
            border-radius: 3rem;
            background: #00aafa;
            height: 100%;
        }

        .switch div {
            width: 100%;
            text-align: center;
            opacity: 0;
            display: block;
            color: #fff;
            transition: opacity 0.2s cubic-bezier(0.77, 0, 0.175, 1) 0.125s;
            will-change: opacity;
            position: absolute;
            top: 0;
            font-weight: bold;
            left: 0;
        }

        .switches-container input:nth-of-type(1):checked~.switch-wrapper {
            transform: translateX(0%);
        }

        .switches-container input:nth-of-type(2):checked~.switch-wrapper {
            transform: translateX(100%);
        }

        .switches-container input:nth-of-type(1):checked~.switch-wrapper .switch div:nth-of-type(1) {
            opacity: 1;
        }

        .switches-container input:nth-of-type(2):checked~.switch-wrapper .switch div:nth-of-type(2) {
            opacity: 1;
        }

        .btn-success:hover,
        .btn-success:not(:disabled):not(.disabled).active,
        .btn-success:not(:disabled):not(.disabled):active,
        .show>.btn-success.dropdown-toggle {
            background-color: #1f71f0 !important;
            border-color: #1f71f0 !important;
        }

        .btn-success {
            color: #fff;
            background-color: #00aafa;
            border-color: #00aafa;
            height: 50px;
        }

        .tooltip-inner {
            border-radius: 15px;
            background: #00aafa;
            border: 1px solid #00aafa;
            font-size: 12px;
            font-family: Poppins;
            color: #fff;
            text-align: left;
            font-weight: 200;
            margin-left: 5px;
            padding: 0.6rem;
        }

        .tooltip.show {
            opacity: 1;
        }

        .tooltip .arrow {
            display: none !important;
        }

        img.float-1 {
            width: 170px;
            position: absolute;
            bottom: 39%;
            left: 10%;
            z-index: 99;
        }

        img.float-2 {
            width: 210px;
            position: absolute;
            bottom: 30%;
            z-index: 9;
            right: 5%;
        }

        img.float-3 {
            width: 170px;
            position: absolute;
            bottom: 39%;
            left: 17%;
            z-index: 99;
        }

        img.float-4 {
            width: 218px;
            position: absolute;
            bottom: 26%;
            left: 65%;
            z-index: 99;
        }

        img.float-5 {
            width: 349px;
            position: absolute;
            bottom: 10%;
            left: 35%;
            z-index: 99;
        }

        img.float-6 {
            width: 150px;
            position: absolute;
            bottom: 42%;
            left: 20%;
            z-index: 99;
        }

        img.float-7 {
            width: 152px;
            position: absolute;
            bottom: 34%;
            right: 8%;
            z-index: 99;
        }

        img.float-8 {
            width: 363px;
            position: absolute;
            bottom: 19%;
            left: 38%;
            z-index: 99;
        }

        .float-animation-1 {
            animation: float 6s 1s ease-in-out infinite;
        }

        .float-animation-2 {
            animation: float 6s 2s ease-in-out infinite;
        }

        .float-animation-3 {
            animation: float 6s 3s ease-in-out infinite;
        }

        .btn-secondary {
            color: #fff;
            background-color: #003147;
            border-color: #003147;
            height: 50px;
        }

        .btn-primary {
            color: #fff;
            background-color: #00aafa;
            border-color: #00aafa;
            height: 50px;
        }

        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .show>.btn-primary.dropdown-toggle {
            background-color: #1f71f0 !important;
            border-color: #1f71f0 !important;
        }

        .btn-secondary:hover,
        .btn-primary:hover,
        .btn-secondary:not(:disabled):not(.disabled).active,
        .btn-secondary:not(:disabled):not(.disabled):active,
        .show>.btn-secondary.dropdown-toggle {
            background-color: #00628d !important;
            border-color: #00628d !important;
        }

        @keyframes float {
            0% {
                transform: translatey(0px);
            }

            50% {
                transform: translatey(-20px);
            }

            100% {
                transform: translatey(0px);
            }
        }

        @media screen and (min-width: 1800px) {
            img.float-3 {
                width: 225px;
                bottom: 50%;
                left: 17%;
            }

            img.float-4 {
                width: 260px;
                bottom: 40%;
                left: 70%;
            }

            img.float-5 {
                width: 410px;
                bottom: 10%;
                left: 41%;
            }
        }

        @media screen and (max-width: 1500px) and (min-width: 1400px) {
            img.float-1 {
                bottom: 44%;
                left: 15%;
            }

            img.float-2 {
                right: 11%;
            }

            img.float-3 {
                left: 17%;
            }

            img.float-4 {
                bottom: 26%;
                left: 69%;
            }

            img.float-5 {
                bottom: 10%;
                left: 38%;
            }

            div#panel-left img.person-1,
            div#panel-left img.person-2 {
                width: 52vh;
            }
        }

        @media screen and (max-width: 768px) {
            p br {
                display: none;
            }

            .formDev {
                width: 100%;
                margin: 0 auto;
            }

            #footer {
                width: 100%;
            }

            h4.titleModalidad {
                margin-top: 0 !important;
            }

            div#panel-left {
                display: none;
            }

            .formDev {
                width: 100% !important;
            }
        }

        @media screen and (max-height: 940px) {
            #footer {
                position: unset;
            }

            #panel-left,
            #wrap {
                height: auto;
            }
        }
    </style>
    @extends('layouts.metautomati')
</head>

<body class="container-fluid">
    <section id="contact">
        <input type="hidden" id="nombre_pais" value="{{ session('pais') }}" />
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-6 p-0" id="wrap">
                    <div class="container">
                        <div id="header">
                            <img class="mt-4 mb-4 logoPrin" src="{{ asset('automatizacionv1/img/logo-principal.png') }}"
                                alt="" />
                        </div>

                        <div id="inner-wrap" class="mt-5 mb-5 form-1" data-aos="zoom-out" data-aos-duration="1500">
                            <div id="content">
                                <p style="color: #00aafa; font-weight: 600;">1 de 3</p>

                                <h4 class="h4 mb-3 mt-3 font-weight-bold titleModalidad">Empecemos creando tu usuario
                                    principal</h4>

                                <p>
                                    Aqui crearas tu cuenta para acceder a la plataforma <br />
                                    de gestión de personal.
                                </p>

                                <div class="col-md-12 mt-5 formDev">
                                    <form id="validarRegistro" method="post">
                                        <div class="row">
                                            <div class="col-sm-12 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Correo Electrónico: <span
                                                            class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input type="email" class="form-control"
                                                            onblur="comprobarEmail()" id="emailReg" name="emailReg"
                                                            required />
                                                        <div id="correo-error"
                                                            class="invalid-feedback animated fadeInUp"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <div class="">
                                                        <input type="checkbox" name="test[]" id="test" required />
                                                        Acepto los <a href="#"><b style="color: #00aafa;">
                                                                Terminos y Condiciones</b></a>
                                                    </div>
                                                    <span id="checkTe-error" class="invalid-feedback animated fadeInUp"
                                                        style="display: none;"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2 mb-3 gap-2 col-12 text-center mx-auto">
                                            <button type="button" class="previous btn btn-secondary mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="7.344" height="13.451"
                                                    viewBox="0 0 7.344 13.451"
                                                    style="margin-right: 10px; margin-top: -4px;">
                                                    <path id="Trazado_250" data-name="Trazado 250"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(7.566 2.109)" fill="none" stroke="#fff"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
                                                    </path>
                                                </svg>
                                                Atrás
                                            </button>
                                            <button id="registroUsuario" class="btn btn-primary ml-2" type="submit">
                                                Continuar
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-left: 10px; margin-top: -4px;" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451">
                                                    <path id="Trazado_298" data-name="Trazado 298"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(-0.222 11.342) rotate(180)" fill="none"
                                                        stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- inicio --}}
                        <div id="inner-wrap" class="mt-5 mb-5 form-5" data-aos="zoom-out" data-aos-duration="1500"
                            style="display: none;">
                            <div id="content">
                                <p style="color: #00aafa; font-weight: 600;">2 de 3</p>

                                <h4 class="h4 mb-3 mt-3 font-weight-bold titleModalidad">Empecemos creando tu usuario
                                    principal</h4>

                                <p id="texto"></p>

                                <div class="col-md-12 mt-5 formDev">
                                    <form method="post" action="javascript:validarUsuario()">
                                        <div class="row">
                                            <span id="usuario_error" class="invalid-feedback animated fadeInUp"
                                                style="display: none;">Contraseña incorrecta, debe ingresar la
                                                contraseña para el usuario ingresado.</span>
                                            <div class="col-sm-12 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Contraseña: <span
                                                            class="text-danger">*</span></label>
                                                    <div class="passwordInp">
                                                        <input type="password" class="form-control" name="password"
                                                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,20}$"
                                                            title="Debe ingresar contraseña segura" id="password"
                                                            required />
                                                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                                                        <span id="contra-error"
                                                            class="invalid-feedback animated fadeInUp"
                                                            style="display: none;">Por favor, introduce su
                                                            contraseña</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 mb-sm-3 text-left usuario_registrado">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Confirmar contraseña: <span
                                                            class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input type="password" class="form-control camposRequeridos"
                                                            name="passwordConfR"
                                                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,20}$"
                                                            title="Debe ingresar contraseña segura"
                                                            id="passwordConfR" />
                                                        <i class="bi bi-eye-slash" id="conf-togglePassword"></i>
                                                    </div>
                                                    <span id="confcontra-error"
                                                        class="invalid-feedback animated fadeInUp"
                                                        style="display: none;"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2 mb-3 gap-2 col-12 text-center mx-auto">
                                            <button type="button" class="regresar btn btn-secondary mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451"
                                                    style="margin-right: 10px; margin-top: -4px;">
                                                    <path id="Trazado_250" data-name="Trazado 250"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(7.566 2.109)" fill="none"
                                                        stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3"></path>
                                                </svg>
                                                Atrás
                                            </button>
                                            <button class="btn btn-primary ml-2" type="submit">
                                                Continuar
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-left: 10px; margin-top: -4px;" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451">
                                                    <path id="Trazado_298" data-name="Trazado 298"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(-0.222 11.342) rotate(180)"
                                                        fill="none" stroke="#fff" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="3"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- fin --}}
                        <div id="inner-wrap" class="mt-5 mb-5 form-2" style="display: none;">
                            <div id="content">
                                <p style="color: #00aafa; font-weight: 600;">3 de 3</p>

                                <h4 class="h4 mb-3 mt-3 font-weight-bold titleModalidad">Datos de Perfil</h4>

                                <p>
                                    Aqui ingresa la información de tu perfil inicial, posteriormente<br />
                                    en la plataforma podras completar el resto de tus datos de perfil.
                                </p>

                                <!-- <div class="container mt-5">
                                        <div class="switches-container">
                                            <input type="radio" id="switchPersona" name="switchPlan" value="Persona" checked="checked" />
                                            <input type="radio" id="switchEmpresa" name="switchPlan" value="Empresa" />
                                            <label for="switchPersona">Persona</label>
                                            <label for="switchEmpresa">Empresa</label>
                                            <div class="switch-wrapper">
                                                <div class="switch">
                                                    <div>Persona</div>
                                                    <div>Empresa</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                <div class="col-md-12 mt-5 formDev">
                                    <form id="validarRegistroPer" method="post">
                                        <div class="row">
                                            <div class="col-sm-12 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Nombres: <span
                                                            class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input type="text" class="form-control" id="nombres"
                                                            pattern="^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$"
                                                            title="Debe ingresar nombres validos" name="nombres"
                                                            required />
                                                        <div id="n-error" class="invalid-feedback animated fadeInUp"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Apellido paterno: <span
                                                            class="text-danger">*</span></label>
                                                    <div class="passwordInp">
                                                        <input type="text" class="form-control" name="apePa"
                                                            pattern="^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$"
                                                            title="Debe ingresar apellido paterno validos"
                                                            id="apePa" required />
                                                        <div id="ap-error" class="invalid-feedback animated fadeInUp"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Apellido materno: <span
                                                            class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input type="text" class="form-control" name="apeMa"
                                                            pattern="^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$"
                                                            title="Debe ingresar apellido materno validos"
                                                            id="apeMa" required />
                                                        <div id="am-error" class="invalid-feedback animated fadeInUp"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2 mb-3 gap-2 col-12 text-center mx-auto">
                                            <button type="button" class="previous btn btn-secondary mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451"
                                                    style="margin-right: 10px; margin-top: -4px;">
                                                    <path id="Trazado_250" data-name="Trazado 250"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(7.566 2.109)" fill="none"
                                                        stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3"></path>
                                                </svg>
                                                Atrás
                                            </button>
                                            <button class="btn btn-primary ml-2" type="submit">
                                                Continuar
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-left: 10px; margin-top: -4px;" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451">
                                                    <path id="Trazado_298" data-name="Trazado 298"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(-0.222 11.342) rotate(180)"
                                                        fill="none" stroke="#fff" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="3"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="inner-wrap" class="mt-5 mb-5 form-3" style="display: none;">
                            <div id="content">
                                <p style="color: #00aafa; font-weight: 600;">4 de 4</p>

                                <h4 class="h4 mb-3 mt-3 font-weight-bold titleModalidad">Registremos a tu empresa u
                                    organización</h4>

                                <p>
                                    La plataforma está diseñada tanto para empresas,<br />
                                    organizaciones y personas naturales.
                                </p>

                                <div class="col-md-12 mt-5 formDev">
                                    <form id="validarRegistroOrga" method="post">
                                        <div class="row">
                                            <div class="col-sm-6 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Identificador: <span
                                                            class="text-danger">*</span></label>
                                                    <svg data-toggle="tooltip" data-placement="right"
                                                        title="Indica el documento de
                                                        identificación o el RUC de la
                                                        empresa a registrar."
                                                        style="margin-left: 10px;" width="17" height="19"
                                                        viewBox="0 0 17 19" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="8.5" cy="8.5" r="8" fill="white"
                                                            stroke="#00AAFA" />
                                                        <path
                                                            d="M8.68 5.56C8.384 5.56 8.136 5.468 7.936 5.284C7.744 5.092 7.648 4.856 7.648 4.576C7.648 4.296 7.744 4.064 7.936 3.88C8.136 3.688 8.384 3.592 8.68 3.592C8.976 3.592 9.22 3.688 9.412 3.88C9.612 4.064 9.712 4.296 9.712 4.576C9.712 4.856 9.612 5.092 9.412 5.284C9.22 5.468 8.976 5.56 8.68 5.56ZM9.508 6.352V13H7.828V6.352H9.508Z"
                                                            fill="#00AAFA" />
                                                    </svg>

                                                    <div class="">
                                                        <input type="text" class="form-control" id="identificador"
                                                            pattern="^[A-Za-z0-9]+$" minlength="8" maxlength="20"
                                                            title="Debe ingresar identificación o el RUC valido"
                                                            name="identificador" required />

                                                        <div id="ruc-error" class="invalid-feedback animated fadeInUp"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Nombre completo o Razón
                                                        social: <span class="text-danger">*</span></label>
                                                    <svg data-toggle="tooltip" data-placement="right"
                                                        title="Indica el nombre de la
                                                        persona o Razón social de
                                                        la empresaa registrar."
                                                        style="margin-left: 10px;" width="17" height="19"
                                                        viewBox="0 0 17 19" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="8.5" cy="8.5" r="8" fill="white"
                                                            stroke="#00AAFA" />
                                                        <path
                                                            d="M8.68 5.56C8.384 5.56 8.136 5.468 7.936 5.284C7.744 5.092 7.648 4.856 7.648 4.576C7.648 4.296 7.744 4.064 7.936 3.88C8.136 3.688 8.384 3.592 8.68 3.592C8.976 3.592 9.22 3.688 9.412 3.88C9.612 4.064 9.712 4.296 9.712 4.576C9.712 4.856 9.612 5.092 9.412 5.284C9.22 5.468 8.976 5.56 8.68 5.56ZM9.508 6.352V13H7.828V6.352H9.508Z"
                                                            fill="#00AAFA" />
                                                    </svg>
                                                    <div class="passwordInp">
                                                        <input type="text" class="form-control" name="nomRaz"
                                                            pattern="^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s0-9 ]+$"
                                                            onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                            title="Debe ingresar Razón social de la empresaa registrar"
                                                            id="nomRaz" required />
                                                        <div id="razonSocial-error"
                                                            class="invalid-feedback animated fadeInUp"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">País <span
                                                            class="text-danger">*</span></label>
                                                    <svg data-toggle="tooltip" data-placement="right"
                                                        title="Indica el país desde donde
                                                        vas a administrar el personal
                                                        (no necesariamente es el país
                                                        de la empresa registrada)."
                                                        style="margin-left: 10px;" width="17" height="19"
                                                        viewBox="0 0 17 19" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="8.5" cy="8.5" r="8" fill="white"
                                                            stroke="#00AAFA" />
                                                        <path
                                                            d="M8.68 5.56C8.384 5.56 8.136 5.468 7.936 5.284C7.744 5.092 7.648 4.856 7.648 4.576C7.648 4.296 7.744 4.064 7.936 3.88C8.136 3.688 8.384 3.592 8.68 3.592C8.976 3.592 9.22 3.688 9.412 3.88C9.612 4.064 9.712 4.296 9.712 4.576C9.712 4.856 9.612 5.092 9.412 5.284C9.22 5.468 8.976 5.56 8.68 5.56ZM9.508 6.352V13H7.828V6.352H9.508Z"
                                                            fill="#00AAFA" />
                                                    </svg>
                                                    <div class="">
                                                        <select name="pais" id="pais" class="form-control"
                                                            pattern="^[a-zA-Z ]+$" title="Debe seleccionar país"
                                                            required>
                                                            <option value="">-- Seleccione su país --</option>
                                                        </select>
                                                    </div>
                                                    <div id="pais-error" class="invalid-feedback animated fadeInUp"
                                                        style="display: none;"></div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-sm-3 text-left">
                                                <div class="mb-3 form-group">
                                                    <label for="exampleInputEmail1"
                                                        class="form-label font-weight-bold">Tipo de organización<span
                                                            class="text-danger">*</span></label>
                                                    <div class="">
                                                        <select class="form-control" name="organizacionS"
                                                            pattern="^[a-zA-Z ]+$"
                                                            title="Debe seleccionar tipo de organizacion"
                                                            id="organizacionS" required>
                                                            <option value="">- Seleccionar -</option>
                                                            <option value="Persona natural con negocio">Persona natural
                                                                con negocio</option>
                                                            <option value="Empresa">Empresa</option>
                                                            <option value="Gobierno">Gobierno</option>
                                                            <option value="ONG">ONG</option>
                                                            <option value="Asociación">Asociación</option>
                                                            <option value="Clubes">Clubes</option>
                                                            <option value="Otros">Otros</option>
                                                        </select>
                                                    </div>
                                                    <div id="tipo-error" class="invalid-feedback animated fadeInUp"
                                                        style="display: none;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2 mb-3 gap-2 col-12 text-center mx-auto">
                                            <button type="button" class="previous btn btn-secondary mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451"
                                                    style="margin-right: 10px; margin-top: -4px;">
                                                    <path id="Trazado_250" data-name="Trazado 250"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(7.566 2.109)" fill="none"
                                                        stroke="#fff" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3"></path>
                                                </svg>
                                                Atrás
                                            </button>
                                            <button id="RegistrarDatos" class="btn btn-success ml-2" type="submit">
                                                Empezar
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-left: 10px; margin-top: -4px;" width="7.344"
                                                    height="13.451" viewBox="0 0 7.344 13.451">
                                                    <path id="Trazado_298" data-name="Trazado 298"
                                                        d="M-2.333,0-6.066,4.659l3.734,4.573"
                                                        transform="translate(-0.222 11.342) rotate(180)"
                                                        fill="none" stroke="#fff" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="3"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="footer" class="pt-3 pb-3">
                        RH NUBE CORP - TODOS LOS DERECHOS RESERVADOS | 848 BRICKELL AVE, SUITE 950, MIAMI, FL 33131, USA
                    </div>
                </div>
                <div class="col-md-6 text-center" id="panel-left">
                    <div class="usuAdm">
                        <div class="person">
                            <img src="{{ asset('automatizacionv1/img/Crear-usuario.png') }}" class="person-1"
                                alt="" />
                        </div>

                        <div class="flotante">
                            <img id="float-animation" src="{{ asset('automatizacionv1/img/flotan3.png') }}"
                                class="float-1" alt="" />
                            <img id="float-animation" src="{{ asset('automatizacionv1/img/flotan4.png') }}"
                                class="float-2" alt="" />
                        </div>
                    </div>
                    <div class="usuEmpresa" style="display: none;">
                        <div class="person">
                            <img src="{{ asset('automatizacionv1/img/Registro-empresa.png') }}" class="person-1"
                                alt="" />
                        </div>

                        <div class="flotante">
                            <img id="float-animation" src="{{ asset('automatizacionv1/img/flotan8.png') }}"
                                class="float-6" alt="" />
                            <img id="float-animation" src="{{ asset('automatizacionv1/img/flotan9.png') }}"
                                class="float-7" alt="" />
                            <img id="float-animation" src="{{ asset('automatizacionv1/img/flotan10.png') }}"
                                class="float-8" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<!-- Modal carga empresa-->
<div class="modal fade" id="cargaEmpresa" style="padding-right: 0 !important;" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-body row justify-content-center align-items-center pt-4 pb-5"
                style="padding-top: 0; padding-left: 10%; padding-right: 10%; color: #707070 !important;">
                <div class="col-md-12 text-center">
                    <img class="changImgLoad" src="{{ asset('automatizacionv1/img/gifCarga/carga-1.gif') }}"
                        style="height: 60vh;" />
                    <br />
                    <label for="" class="chanTextLoa mt-5">Sincronizando tus datos con el servidor</label>
                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success-bar"
                            role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@extends('layouts.whatsapp-automa')
<script src="{{ asset('automatizacionv1/js/jquery.min.js') }}"></script>
<script src="{{ asset('automatizacionv1/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('automatizacionv1/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('admin/assets/libs/bootstrap-notify-master/bootstrap-notify.min.js') }}"></script>
<script src="{{ URL::asset('admin/assets/libs/bootstrap-notify-master/bootstrap-notify.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('automatizacionv1/js/registro_organizacion.js') }}"></script>

</html>
