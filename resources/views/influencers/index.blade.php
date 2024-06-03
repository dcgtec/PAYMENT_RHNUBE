<!DOCTYPE html>
<html lang="en">

<head>
    <title>Iniciar sesión Influencer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/iconoLogoRhNubeActual.png') }}">
    <link rel="stylesheet" href="{{ asset('influencers/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <script src="{{ asset('influencers/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="{{ asset('influencers/css/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    {!! htmlScriptTagJsApi() !!}
</head>

<body>

    <div class="container-fluid m-0 p-0 d-flex align-items-center justify-content-center h-100">
        <div class="row  w-100 h-100 justify-content-center">
            <!-- Agrega la clase justify-content-center a la fila -->
            <div class="col-xl-6 col-lg-6 col-sm-12 cardEfe">
                <div class="carousel">
                    <ul class="carousel__list h-100">
                        <li class="carousel__item" data-pos="-2">
                        </li>
                        <li class="carousel__item" data-pos="-1">
                        </li>
                        <li class="carousel__item" data-pos="0">
                        </li>
                        <li class="carousel__item" data-pos="1">
                        </li>
                        <li class="carousel__item" data-pos="2">
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-sm-12 align-content-center">
                <div class="panel bg-white p-5 w-75" style="margin: 0 auto;">
                    <div class="panel-heading">
                        <h3 class="font-weight-bold">INICIA SESIÓN</h3>
                    </div>
                    <div class="panel-body pt-3">
                        <form id="loginForm" autocomplete="off">
                            <div class="form-group py-2">
                                <div class="input-field"> <span class="far fa-user p-2"></span>
                                    <input id="user" name="user" type="email"
                                        placeholder="Ingrese su correo electrónico" autocomplete="anyrandominvalidvalue"
                                        required="">
                                </div>
                            </div>
                            <div class="form-group py-1 pb-2">
                                <div class="input-field"> <span class="fas fa-lock px-2"></span> <input
                                        autocomplete="anyrandominvalidvalue" type="password" name="passwordInput"
                                        id="passwordInput" placeholder="Ingrese su contraseña" required="">
                                    <button id="togglePassword" class="btn bg-white text-muted" type="button"> <span
                                            class="far fa-eye-slash"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-inline">
                                <a href="#" data-target="#exampleModal" id="forgot"
                                    class="font-weight-bold">¿Olvidaste tu Contraseña?</a>
                            </div>
                            <div class="row py-1 mt-4 mv-3 px-3 justify-content-center text-center">
                                {!! htmlFormSnippet() !!} @error('g-recaptcha-response')
                                    <span class="invalid-feedback" style="display: block !important;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary btn-block mt-3" type="submit">Ingresar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


</body>

</html>


<script src="{{ asset('influencers/js/login.js') }}"></script>
