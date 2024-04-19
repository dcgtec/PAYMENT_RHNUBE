<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> <!-- Agregamos el archivo CSS externo -->
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
                        <form autocomplete="off">
                            <div class="form-group py-2">
                                <div class="input-field"> <span class="far fa-user p-2"></span> <input type="text"
                                        placeholder="Username or Email" autocomplete="anyrandominvalidvalue"
                                        required="">
                                </div>
                            </div>
                            <div class="form-group py-1 pb-2">
                                <div class="input-field"> <span class="fas fa-lock px-2"></span> <input
                                        autocomplete="anyrandominvalidvalue" type="password" id="passwordInput"
                                        placeholder="Enter your Password" required="">
                                    <button id="togglePassword" class="btn bg-white text-muted" type="button"> <span
                                            class="far fa-eye-slash"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-inline"> <input type="checkbox" name="remember" id="remember"> <label
                                    for="remember" class="text-muted">Recuerda este dispositivo</label> <a
                                    href="#" id="forgot" class="font-weight-bold">¿Olvidaste tu Contraseña?</a>
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


</body>

</html>


<style>
    html,
    body {
        padding: 0;
        margin: 0;
        background-color: #F8FAFC;
        font-family: 'Poppins', sans-serif
    }

    html {
        height: 100vh;
    }

    /* Para los inputs con autocompletado */
    input:-webkit-autofill {
        -webkit-text-fill-color: #000;
        /* Color del texto */
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        /* Fondo blanco */
    }

    /* Para quitar el fondo azul cuando está enfocado */
    input:focus:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        /* Fondo blanco */
        outline: none;
        /* Quita el contorno */
    }


    body {
        height: 100vh;
    }

    .container-fluid {
        height: 100vh;
    }

    .carousel {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        font-family: Arial;
    }

    .carousel__list {
        display: flex;
        list-style: none;
        position: relative;
        width: 100%;
        height: 300px;
        justify-content: center;
        perspective: 300px;
        margin: 0;
        padding: 0;
    }

    .carousel__item {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0px;
        width: 50%;
        height: 100%;
        border-radius: 12px;
        position: absolute;
        transition: all 0.3s ease-in;
    }

    .carousel__item:nth-child(1) {
        background: url(../images/cardImg.png);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .carousel__item:nth-child(2) {
        background: url(../images/cardImg.png);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .carousel__item:nth-child(3) {
        background: url(../images/cardImg.png);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .carousel__item:nth-child(4) {
        background: url(../images/cardImg.png);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .carousel__item:nth-child(5) {
        background: url(../images/cardImg.png);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .carousel__item[data-pos="0"] {
        z-index: 5;
    }

    .carousel__item[data-pos="-1"],
    .carousel__item[data-pos="1"] {
        opacity: 0.7;
        filter: blur(1px) grayscale(10%);
    }

    .carousel__item[data-pos="-1"] {
        transform: translateX(-15%) scale(0.9);
        z-index: 4;
    }

    .carousel__item[data-pos="1"] {
        transform: translateX(15%) scale(0.9);
        z-index: 4;
    }

    .carousel__item[data-pos="-2"],
    .carousel__item[data-pos="2"] {
        opacity: 0.4;
        filter: blur(3px) grayscale(20%);
    }

    .carousel__item[data-pos="-2"] {
        transform: translateX(-30%) scale(0.8);
        z-index: 3;
    }

    .carousel__item[data-pos="2"] {
        transform: translateX(30%) scale(0.8);
        z-index: 3;
    }

    .cardEfe {
        background: rgb(86, 84, 230);
        background: linear-gradient(90deg, rgba(86, 84, 230, 0.25) 0%, rgba(253, 203, 103, 0.25) 100%);
        margin: 0 !important;
        padding: 0 !important;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');


    .panel-heading {
        text-align: center;
        margin-bottom: 10px
    }

    #forgot {
        min-width: 100px;
        margin-left: auto;
        text-decoration: none;
        font-size: 14px;
    }

    a:hover {
        text-decoration: none
    }

    .form-inline label {
        padding-left: 10px;
        margin: 0;
        cursor: pointer;
        font-size: 14px;
    }

    .btn.btn-primary {
        margin-top: 20px;
        border-radius: 10px;
        background-color: #1F71F0;
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .panel {
        min-height: 380px;
        box-shadow: 20px 20px 80px rgb(218, 218, 218);
        border-radius: 12px
    }

    .input-field {
        border-radius: 10px;
        padding: 8px 5px;
        display: flex;
        align-items: center;
        cursor: pointer;
        border: 1px solid #1F71F0;
        color: #AAB4C3
    }

    input[type='text'],
    input[type='password'] {
        border: none;
        outline: none;
        box-shadow: none;
        width: 100%
    }

    .fa-eye-slash.btn {
        border: none;
        outline: none;
        box-shadow: none
    }

    img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        position: relative
    }

    a[target='_blank'] {
        position: relative;
        transition: all 0.1s ease-in-out
    }

    button:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .bordert {
        border-top: 1px solid #aaa;
        position: relative
    }

    .bordert:after {
        content: "or connect with";
        position: absolute;
        top: -13px;
        left: 33%;
        background-color: #fff;
        padding: 0px 8px
    }

    @media(max-width: 360px) {
        #forgot {
            margin-left: 0;
            padding-top: 10px
        }

        body {
            height: 100%
        }

        .container {
            margin: 30px 0
        }

        .bordert:after {
            left: 25%
        }
    }

    @media screen and (max-width: 991px) {
        body {
            overflow-y: scroll;
        }

        .carousel__list {
            height: 400px !important;
        }

        .panel {
            width: 100% !important;
            margin-top: 50px !important;
            margin-bottom: 50px !important;
        }

        .carousel__item {
            width: 60%;
        }
    }
</style>

<script>
    // Obtener elementos
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');

    // Agregar evento click al botón
    togglePassword.addEventListener('click', function() {
        // Cambiar el tipo de entrada de la contraseña
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.innerHTML = '<span class="far fa-eye"></span>'; // Cambiar el icono a un ojo abierto
        } else {
            passwordInput.type = 'password';
            togglePassword.innerHTML =
                '<span class="far fa-eye-slash"></span>'; // Cambiar el icono a un ojo tachado
        }
    });

    const state = {};
    const carouselList = document.querySelector('.carousel__list');
    const carouselItems = document.querySelectorAll('.carousel__item');
    const elems = Array.from(carouselItems);

    carouselList.addEventListener('click', function(event) {
        var newActive = event.target;
        var isItem = newActive.closest('.carousel__item');

        if (!isItem || newActive.classList.contains('carousel__item_active')) {
            return;
        };

        update(newActive);
    });

    const update = function(newActive) {
        const newActivePos = newActive.dataset.pos;

        const current = elems.find((elem) => elem.dataset.pos == 0);
        const prev = elems.find((elem) => elem.dataset.pos == -1);
        const next = elems.find((elem) => elem.dataset.pos == 1);
        const first = elems.find((elem) => elem.dataset.pos == -2);
        const last = elems.find((elem) => elem.dataset.pos == 2);

        current.classList.remove('carousel__item_active');

        [current, prev, next, first, last].forEach(item => {
            var itemPos = item.dataset.pos;

            item.dataset.pos = getPos(itemPos, newActivePos)
        });
    };

    const getPos = function(current, active) {
        const diff = current - active;

        if (Math.abs(current - active) > 2) {
            return -current
        }

        return diff;
    }
</script>
