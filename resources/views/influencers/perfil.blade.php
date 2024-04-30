@include('layouts.influencers.header')

@php


// Información básica del propietario
$codigo = $propietario['codigo'];
$nombrePro = $propietario['nombres'];
$apellidosPa = $propietario['apellido_paterno'];
$apellidosMa = $propietario['apellido_materno'];
$email = $propietario['correo'];
$telefono = $propietario['numero_movil'];
$password = $propietario['password'];
$razon_social = $propietario['razon_social'];
// Campos opcionales con valores por defecto
$cargo = $propietario['cargo'] ?? ''; // Si no existe, se asigna una cadena vacía

// Decodificación de redes sociales si existen
$redesSocialArray = [];
if (isset($propietario['redes_sociales'])) {
$redesSocialArray = $propietario['redes_sociales'];
$jsonRedesSociales = str_replace("'", '"', $redesSocialArray);
$redesSocialArray = json_decode($jsonRedesSociales, true);
}

// Extraer las redes sociales si están definidas
$facebook = data_get($redesSocialArray, 'facebook', '');

$linkedin = data_get($redesSocialArray, 'linkedIn', '');
$instagram = data_get($redesSocialArray, 'instagram', '');
$tiktok = data_get($redesSocialArray, 'tiktok', '');

@endphp


<div class="container infoContenido pt-5 pl-md-4 pr-md-4 pb-5">
    <h1>Mi información</h1>

    <form id="editarPerfil" class="mt-5">
        <div class="row">

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="number" class="form-control" value="{{ $codigo }}" id="codigo" name="codigo" aria-describedby="emailHelp" placeholder="DNI / Carnet Extranjeria / RUC">

                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="text" class="form-control" value="{{ $nombrePro }}" id="nombres" name="nombres" aria-describedby="emailHelp" placeholder="Nombres">

                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="text" value="{{ $apellidosPa }}" class="form-control" id="apPaterno" name="apPaterno" aria-describedby="emailHelp" placeholder="Apellidos Paterno">

                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="text" value="{{ $apellidosMa }}" class="form-control" id="apMaterno" name="apMaterno" aria-describedby="emailHelp" placeholder="Apellidos Materno">

                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="email" value="{{ $email }}" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Correo">

                </div>
            </div>



            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="password" class="form-control" value="{{ $password }}" id="password" name="password" aria-describedby="emailHelp" placeholder="Cambiar contraseña">

                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="tel" value="{{ $telefono }}" class="form-control" id="telfono" name="telfono" aria-describedby="emailHelp" placeholder="Número de teléfono">
                    <span id="telfono-error" style="color: red; display: none;">Número de teléfono no válido</span>
                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="text" class="form-control" id="razon_social" value="{{ $razon_social }}" name="razon_social" value="{{ $cargo }}" aria-describedby="emailHelp" placeholder="Razón Social">

                </div>
            </div>

            <div class="col-md-4 my-1">
                <div class="form-group">

                    <input type="text" class="form-control" id="cargo" name="cargo" value="{{ $cargo }}" aria-describedby="emailHelp" placeholder="Cargo/ocupación">

                </div>
            </div>


            <div class="col-md-6 my-1 redSocial">
                <div class="form-group d-flex align-items-center">
                    <div class=" d-flex align-items-center">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.3333 0H1.66667C0.75 0 0 0.75 0 1.66667V13.3333C0 14.2508 0.75 15 1.66667 15H7.5V9.16667H5.83333V7.10417H7.5V5.39583C7.5 3.5925 8.51 2.32583 10.6383 2.32583L12.1408 2.3275V4.49833H11.1433C10.315 4.49833 10 5.12 10 5.69667V7.105H12.14L11.6667 9.16667H10V15H13.3333C14.25 15 15 14.2508 15 13.3333V1.66667C15 0.75 14.25 0 13.3333 0Z" fill="#AAB4C3" />
                        </svg>

                        <span class="ml-2">Facebook | </span>
                    </div>
                    <input type="url" class="form-control" id="facebook" name="facebook" aria-describedby="emailHelp" placeholder="Facebook" value="{{ $facebook }}">

                </div>
            </div>


            <div class="col-md-6 my-1 redSocial">
                <div class="form-group d-flex align-items-center">
                    <div class=" d-flex align-items-center">
                        <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.14795 1.55625C3.14775 1.96878 2.98172 2.36434 2.68639 2.6559C2.39107 2.94746 1.99063 3.11115 1.57319 3.11094C1.15575 3.11073 0.755481 2.94666 0.460451 2.6548C0.16542 2.36295 -0.000208525 1.96723 1.97032e-07 1.55469C0.000208919 1.14216 0.166238 0.746598 0.461564 0.455036C0.756889 0.163475 1.15732 -0.000206073 1.57476 1.94715e-07C1.99221 0.000206463 2.39247 0.164284 2.6875 0.456136C2.98253 0.747989 3.14816 1.14371 3.14795 1.55625ZM3.19517 4.26276H0.0472195V14H3.19517V4.26276ZM8.16894 4.26276H5.03673V14H8.13746V8.89028C8.13746 6.04378 11.8914 5.77935 11.8914 8.89028V14H15V7.83256C15 3.03394 9.44386 3.21282 8.13746 5.56936L8.16894 4.26276Z" fill="#AAB4C3" />
                        </svg>


                        <span class="ml-2">LinkedIn | </span>
                    </div>
                    <input type="url" class="form-control" id="linkedIn" name="linkedIn" aria-describedby="emailHelp" placeholder="LinkedIn" value="{{ $linkedin }}">

                </div>
            </div>


            <div class="col-md-6 my-1 redSocial">
                <div class="form-group d-flex align-items-center">
                    <div class=" d-flex align-items-center">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.4844 0H3.51562C1.574 0 0 1.574 0 3.51562V11.4844C0 13.426 1.574 15 3.51562 15H11.4844C13.426 15 15 13.426 15 11.4844V3.51562C15 1.574 13.426 0 11.4844 0Z" fill="url(#paint0_radial_4064_628)" />
                            <path d="M11.4844 0H3.51562C1.574 0 0 1.574 0 3.51562V11.4844C0 13.426 1.574 15 3.51562 15H11.4844C13.426 15 15 13.426 15 11.4844V3.51562C15 1.574 13.426 0 11.4844 0Z" fill="#AAB4C3" />
                            <path d="M7.50053 1.64062C5.90924 1.64062 5.70949 1.6476 5.08453 1.67602C4.46074 1.70461 4.03494 1.80334 3.6624 1.94824C3.27697 2.09789 2.95008 2.29811 2.62441 2.62389C2.29846 2.94961 2.09824 3.2765 1.94813 3.66176C1.80281 4.03441 1.70396 4.46039 1.6759 5.08389C1.64795 5.70891 1.64062 5.90871 1.64062 7.50006C1.64062 9.09141 1.64766 9.29051 1.67602 9.91547C1.70473 10.5393 1.80346 10.9651 1.94824 11.3376C2.09801 11.723 2.29822 12.0499 2.624 12.3756C2.94961 12.7015 3.2765 12.9022 3.66164 13.0519C4.03447 13.1968 4.46033 13.2955 5.084 13.3241C5.70902 13.3525 5.90859 13.3595 7.49982 13.3595C9.09129 13.3595 9.29039 13.3525 9.91535 13.3241C10.5391 13.2955 10.9654 13.1968 11.3382 13.0519C11.7235 12.9022 12.0499 12.7015 12.3755 12.3756C12.7014 12.0499 12.9016 11.723 13.0518 11.3378C13.1958 10.9651 13.2947 10.5391 13.324 9.91559C13.3521 9.29062 13.3594 9.09141 13.3594 7.50006C13.3594 5.90871 13.3521 5.70902 13.324 5.084C13.2947 4.46021 13.1958 4.03447 13.0518 3.66193C12.9016 3.2765 12.7014 2.94961 12.3755 2.62389C12.0496 2.29799 11.7236 2.09777 11.3379 1.9483C10.9644 1.80334 10.5383 1.70455 9.91453 1.67602C9.28951 1.6476 9.09053 1.64062 7.49871 1.64062H7.50053ZM6.97488 2.69654C7.13092 2.69631 7.305 2.69654 7.50053 2.69654C9.06504 2.69654 9.25043 2.70217 9.86824 2.73023C10.4395 2.75637 10.7496 2.85182 10.9562 2.93203C11.2296 3.0382 11.4246 3.16518 11.6295 3.37031C11.8346 3.57539 11.9615 3.77068 12.068 4.04414C12.1482 4.25039 12.2438 4.56047 12.2698 5.13176C12.2978 5.74945 12.3039 5.93496 12.3039 7.49871C12.3039 9.06246 12.2978 9.24803 12.2698 9.86566C12.2436 10.437 12.1482 10.747 12.068 10.9533C11.9618 11.2268 11.8346 11.4215 11.6295 11.6265C11.4244 11.8315 11.2297 11.9585 10.9562 12.0647C10.7498 12.1453 10.4395 12.2405 9.86824 12.2666C9.25055 12.2947 9.06504 12.3008 7.50053 12.3008C5.93596 12.3008 5.75051 12.2947 5.13287 12.2666C4.56158 12.2402 4.2515 12.1448 4.04479 12.0646C3.77139 11.9583 3.57604 11.8314 3.37096 11.6263C3.16588 11.4213 3.03896 11.2264 2.9325 10.9529C2.85229 10.7466 2.75672 10.4365 2.7307 9.8652C2.70264 9.2475 2.69701 9.06199 2.69701 7.49725C2.69701 5.93256 2.70264 5.74799 2.7307 5.13029C2.75684 4.559 2.85229 4.24893 2.9325 4.04238C3.03873 3.76893 3.16588 3.57363 3.37102 3.36855C3.57609 3.16348 3.77139 3.0365 4.04484 2.9301C4.25139 2.84953 4.56158 2.75432 5.13287 2.72807C5.6734 2.70363 5.88287 2.69631 6.97488 2.69508V2.69654ZM10.6283 3.66943C10.2401 3.66943 9.92514 3.98408 9.92514 4.37232C9.92514 4.76051 10.2401 5.07545 10.6283 5.07545C11.0164 5.07545 11.3314 4.76051 11.3314 4.37232C11.3314 3.98414 11.0164 3.6692 10.6283 3.6692V3.66943ZM7.50053 4.49098C5.83881 4.49098 4.4915 5.83828 4.4915 7.50006C4.4915 9.16184 5.83881 10.5085 7.50053 10.5085C9.1623 10.5085 10.5091 9.16184 10.5091 7.50006C10.5091 5.83834 9.16219 4.49098 7.50041 4.49098H7.50053ZM7.50053 5.54689C8.57918 5.54689 9.45369 6.42129 9.45369 7.50006C9.45369 8.57871 8.57918 9.45322 7.50053 9.45322C6.42182 9.45322 5.54742 8.57871 5.54742 7.50006C5.54742 6.42129 6.42182 5.54689 7.50053 5.54689Z" fill="white" />
                            <defs>
                                <radialGradient id="paint0_radial_4064_628" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(3.98437 16.1553) rotate(-90) scale(14.8661 13.8267)">
                                    <stop stop-color="#FFDD55" />
                                    <stop offset="0.1" stop-color="#FFDD55" />
                                    <stop offset="0.5" stop-color="#FF543E" />
                                    <stop offset="1" stop-color="#C837AB" />
                                </radialGradient>
                            </defs>
                        </svg>


                        <span class="ml-2">Instagram | </span>
                    </div>
                    <input type="url" class="form-control" id="instagram" name="instagram" aria-describedby="emailHelp" placeholder="Instagram" value="{{ $instagram }}">

                </div>
            </div>


            <div class="col-md-6 my-1 redSocial">
                <div class="form-group d-flex align-items-center">
                    <div class=" d-flex align-items-center">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.84052 5.41428C10.8091 6.10627 11.9956 6.51343 13.2771 6.51343V4.0488C13.0345 4.04885 12.7926 4.02355 12.5553 3.9733V5.91332C11.274 5.91332 10.0876 5.50621 9.11886 4.81427V9.84392C9.11886 12.36 7.07808 14.3996 4.56087 14.3996C3.62164 14.3996 2.74862 14.1158 2.02344 13.629C2.85113 14.4749 4.00541 14.9996 5.28237 14.9996C7.79979 14.9996 9.84062 12.9601 9.84062 10.4439V5.41428H9.84052ZM10.7309 2.92776C10.2359 2.38728 9.91083 1.68881 9.84052 0.916594V0.599609H9.15661C9.32877 1.58105 9.91602 2.41954 10.7309 2.92776ZM3.61557 11.6983C3.33903 11.3359 3.18954 10.8925 3.19021 10.4367C3.19021 9.28592 4.12364 8.35286 5.27527 8.35286C5.48985 8.35275 5.70317 8.38568 5.90773 8.4505V5.93074C5.66869 5.89802 5.42746 5.88408 5.18634 5.88921V7.85044C4.98168 7.78557 4.76825 7.75266 4.55356 7.75285C3.40198 7.75285 2.46861 8.68581 2.46861 9.83672C2.46861 10.6505 2.93514 11.3551 3.61557 11.6983Z" fill="#AAB4C3" fill-opacity="0.5" />
                            <path d="M9.11713 4.81422C10.0859 5.50616 11.2722 5.91327 12.5536 5.91327V3.97325C11.8383 3.82095 11.2051 3.44739 10.7291 2.92776C9.91418 2.41949 9.32704 1.581 9.15488 0.599609H7.3585V10.4438C7.3544 11.5914 6.42258 12.5206 5.27344 12.5206C4.59632 12.5206 3.99471 12.1981 3.61374 11.6982C2.93341 11.3551 2.46683 10.6505 2.46683 9.83677C2.46683 8.68597 3.4002 7.7529 4.55178 7.7529C4.77242 7.7529 4.98507 7.78723 5.18456 7.85049V5.88926C2.71153 5.94034 0.722656 7.95996 0.722656 10.4438C0.722656 11.6838 1.21792 12.8078 2.02176 13.6291C2.74694 14.1158 3.61991 14.3997 4.5592 14.3997C7.07646 14.3997 9.11718 12.36 9.11718 9.84392L9.11713 4.81422Z" fill="#AAB4C3" />
                            <path d="M12.5526 3.97359V3.44914C11.9076 3.45011 11.2753 3.26957 10.7281 2.92816C11.2125 3.45822 11.8504 3.82375 12.5526 3.97369M9.15383 0.599954C9.13742 0.506181 9.12483 0.41178 9.11608 0.316985V0H6.63568V9.84427C6.63174 10.9918 5.69992 11.921 4.55073 11.921C4.22494 11.9215 3.90361 11.8454 3.61268 11.6987C3.99366 12.1985 4.59527 12.521 5.27238 12.521C6.42147 12.521 7.3534 11.5919 7.35744 10.4442V0.600005L9.15383 0.599954ZM5.18366 5.88961V5.33119C4.9764 5.30286 4.76744 5.28868 4.55825 5.28877C2.04072 5.28877 0 7.3284 0 9.84427C0 11.4216 0.80203 12.8117 2.02081 13.6294C1.21697 12.8082 0.721707 11.6841 0.721707 10.4442C0.721707 7.96035 2.71053 5.94068 5.18366 5.88961Z" fill="#AAB4C3" fill-opacity="0.5" />
                        </svg>

                        <span class="ml-2">TikTok | </span>
                    </div>
                    <input type="url" class="form-control" id="tiktok" name="tiktok" placeholder="Tiktok" value="{{ $tiktok }}">

                </div>
            </div>

            <div class="col-md-12 my-1 text-right">
                <button type="submit" class="mt-3 btn btn-primary enviarForm">Guardar cambios</button>
            </div>

        </div>



    </form>
</div>

<style>
    .infoContenido h1 {
        color: #464B50;
        font-weight: bold;
        font-size: 25px;
    }

    .infoContenido .form-control {
        background: #fff;
        border-radius: 10px;
        border: none;
        padding-top: 7px !important;
        height: 50px !important;
        padding-bottom: 7px !important;
        color: #464B50;
        font-size: 12px;
        box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
    }

    .redSocial .form-control {
        box-shadow: unset;
        -webkit-box-shadow: unset;
        -moz-box-shadow: unset;
    }


    ::placeholder {
        /* Edge 12-18 */
        color: #AAB4C3 !important;
        font-size: 14px;
    }

    .redSocial .form-group {
        background: #fff;
        border-radius: 10px;
        padding-left: .75rem;
        padding-right: .75rem;
        box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
    }

    *:focus {
        outline: none;
        border-color: inherit;
        -webkit-box-shadow: none;
        box-shadow: none !important;
    }

    input:focus {
        outline: none !important;
    }


    .redSocial .form-group span {
        font-size: 13px !important;
        color: #AAB4C3;
        width: 80px;
    }

    label.error,
    span#telfono-error {
        color: red;
        font-weight: bold;
        margin-top: 10px;
        font-size: 12px;
    }

    .enviarForm {
        background: #1F71F0;
        border: none;
        font-size: 16px;
        font-weight: 500;
        border-radius: 10px;
        padding-top: 13px;
        padding-bottom: 13px;
        width: 200px;
    }
</style>

<script>
    $(document).ready(function() {

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.validator.addMethod("telefonoPeru", function(value, element) {
            const telefonoPeruRegex = /^(?:9\d{8}|\d{7})$/; // 9 para móviles, 7 para fijos
            return this.optional(element) || telefonoPeruRegex.test(value); // Si es opcional, es válido
        }, "Número de teléfono no válido");


        // Configuración del validador
        $("#editarPerfil").validate({
            rules: {
                codigo: {
                    required: true,
                    number: true,
                    minlength: 8,
                    maxlength: 11 // Dependiendo de la longitud esperada para el código (DNI, CE, RUC)
                },
                nombres: {
                    required: true,
                    minlength: 2
                },
                apPaterno: {
                    required: true,
                    minlength: 2
                },
                apMaterno: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                telfono: {
                    required: false, // Opcional, pero si está presente, se debe validar
                    telefonoPeru: true // Usa el método personalizado
                },
                // Reglas para campos opcionales que deben ser URLs si se completan
                facebook: {
                    url: true // Debe ser una URL válida si está presente
                },
                linkedIn: {
                    url: true // Debe ser una URL válida si está presente
                },
                instagram: {
                    url: true // Debe ser una URL válida si está presente
                },
                tiktok: {
                    url: true // Debe ser una URL válida si está presente
                }
            },
            messages: {
                codigo: {
                    required: "Por favor, ingrese el código",
                    number: "Debe ser un número",
                    minlength: "El código debe tener al menos 8 dígitos",
                    maxlength: "El código no debe exceder 11 dígitos"
                },
                nombres: {
                    required: "Por favor, ingrese sus nombres",
                    minlength: "Debe tener al menos 2 caracteres"
                },
                apPaterno: {
                    required: "Por favor, ingrese el apellido paterno",
                    minlength: "Debe tener al menos 2 caracteres"
                },
                apMaterno: {
                    required: "Por favor, ingrese el apellido materno",
                    minlength: "Debe tener al menos 2 caracteres"
                },
                email: {
                    required: "Por favor, ingrese el correo electrónico",
                    email: "Ingrese un correo electrónico válido"
                },
                password: {
                    required: "Por favor, ingrese la contraseña",
                    minlength: "La contraseña debe tener al menos 6 caracteres"
                },
                telfono: {
                    telefonoPeru: "Número de teléfono no válido"
                },
                facebook: {
                    url: "Ingrese una URL válida para Facebook"
                },
                linkedIn: {
                    url: "Ingrese una URL válida para LinkedIn"
                },
                instagram: {
                    url: "Ingrese una URL válida para Instagram"
                },
                tiktok: {
                    url: "Ingrese una URL válida para TikTok"
                }
            },
            submitHandler: function(form) {

                $("button.enviarForm").attr("disabled", true);
                const codigo = $("#codigo").val();
                const nombres = $("#nombres").val();
                const apellido_paterno = $("#apPaterno").val();
                const apellido_materno = $("#apMaterno").val();
                const email = $("#email").val();
                const password = $("#password").val();
                const telfono = $("#telfono").val();
                const razon_social = $("#razon_social").val();
                const cargo = $("#cargo").val();
                const facebook = $("#facebook").val();
                const linkedIn = $("#linkedIn").val();
                const instagram = $("#instagram").val();
                const tiktok = $("#tiktok").val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });


                $.ajax({

                    url: '/actualizarPerfil',
                    data: {
                        codigo: codigo,
                        nombres: nombres,
                        razon_social: razon_social,
                        apellido_paterno: apellido_paterno,
                        apellido_materno: apellido_materno,
                        numero_movil: telfono,
                        password: password,
                        cargo: cargo,
                        email: email,
                        facebook: facebook,
                        linkedIn: linkedIn,
                        instagram: instagram,
                        tiktok: tiktok
                    },
                    method: "post",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "¡Éxito!",
                                text: "Datos guardados correctamente.",
                                icon: "success",
                            }).then(() => {
                                window.location.reload();

                            });
                        } else {
                            Swal.fire({
                                title: "¡Error!",
                                text: response.message || "Ocurrió un error.",
                                icon: "error",
                            });
                        }

                        $("button.enviarForm").attr("disabled", false);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "¡Error!",
                            text: response.message || "Ocurrió un error.",
                            icon: "error",
                        });

                        $("button.enviarForm").attr("disabled", false);
                    }
                });


            }
        });
    });
</script>
@include('layouts.influencers.footer')