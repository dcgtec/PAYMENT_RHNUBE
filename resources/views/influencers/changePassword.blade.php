<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cambiar contraseña</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/iconoLogoRhNubeActual.png') }}">
    <link rel="stylesheet" href="{{ asset('influencers/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <script src="{{ asset('influencers/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        header {
            background: #fff !important;
            height: 100px;
            line-height: 65px;
            box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        }


        .swal2-container.swal2-center.swal2-backdrop-show {
            z-index: 999999 !important;
        }

        .card-header {
            background: #00aafa;
            color: #fff;
            border-radius: 20px 20px 0px 0px !important;
            text-transform: uppercase;
        }

        .card-header h4 {
            font-weight: bold;
        }

        .card.mx-auto {
            border: none;
        }

        .input-group-append {
            position: absolute;
            top: 0px;
            height: 50px;
            right: 0;
            background: none !important;
            border: none;
            z-index: 99999 !important;
        }

        .input-group-text {
            background: none;
            border-radius: 10px !important;
        }

        .form-control {
            border-radius: 10px !important;
            padding: 8px 5px;
            display: flex;
            align-items: center;
            border: 1px solid #1f71f0;
            color: #000 !important;
            margin-bottom: 0;
            height: 50px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .form-group label {
            font-weight: bold;
        }

        .input-group-text {
            background-color: none !important;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            border-radius: 10px;
            background-color: #1f71f0;
            padding-top: 12px;
            padding-bottom: 12px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 17px;
            margin-top: 40px;
        }

        .form-control.is-invalid,
        .was-validated .form-control:invalid,
        .form-control.is-valid,
        .was-validated .form-control:valid {
            background: none !important;
        }
    </style>

    {!! htmlScriptTagJsApi() !!}
</head>

<body>
    <!-- Header -->
    <header class="py-3">
        <div class="container text-center">
            <a href="/">
                <img src="{{ asset('influencers/images/logoRhnube.png') }}" alt="">
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header text-center">
                <h4>Cambiar Contraseña</h4>
            </div>
            <div class="card-body">
                <form id="change-password-form">
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control" disabled id="token" value="{{ $ce }}"
                            name="token" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePasswordVisibility('#password', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm-password" name="confirm_password"
                                required>
                            <div class="input-group-append">
                                <span class="input-group-text"
                                    onclick="togglePasswordVisibility('#confirm-password', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="codigo-validacion">Código de Validación</label>
                        <input type="text" class="form-control" id="codigo-validacion" name="codigo_validacion"
                            required minlength="12" maxlength="12">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block enviarForm">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light py-3">
        <div class="container text-center">
            <p class="mb-0">© 2020 RH nube Corp - USA | Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="{{ asset('influencers/js/changePassword.js') }}"></script>
</body>

</html>
