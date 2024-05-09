<?php
// Definición del array de menús
$menus = [
    [
        'name' => 'Mi perfil',
        'link' => 'perfil', // El enlace a la página de inicio
        'icon' => 'fas fa-user', // Nombre del icono de Ionicons
        'estado' => 1,
    ],
    [
        'name' => 'Mis referidos',
        'link' => 'referidos', // El enlace a la página de "Acerca de"
        'icon' => 'fas fa-users', // Icono para la sección "Acerca de"
        'estado' => 1,
    ],
    [
        'name' => 'Mi cupón',
        'link' => 'micupon', // El enlace a la página de "Acerca de"
        'icon' => 'fas fa-gift', // Icono para la sección "Acerca de"
        'estado' => 1,
    ],
    [
        'name' => 'Mis retiros',
        'link' => 'retiros', // El enlace a la página de habilidades
        'icon' => 'fas fa-wallet', // Icono para la sección de habilidades
        'estado' => 1,
    ],
    [
        'name' => 'Cerrar sesión',
        'link' => '/logout', // El enlace a la página del portafolio
        'icon' => 'fas fa-sign-out-alt', // Icono para el portafolio
        'estado' => 0,
    ],
];

$propietarios = session()->get('detalleUusario');
$nombrePro = $propietarios['nombres'];
$nombre = explode(' ', $propietarios['nombres'])[0];

if ($propietarios['foto_perfil']) {
    $rutaImgPerfil = $propietarios['foto_perfil'];
} else {
    $rutaImgPerfil = '../../influencers/images/imgDefault.png';
}

if ($propietarios['foto_portada']) {
    $rutaImgPortada = $propietarios['foto_portada'];
} else {
    $rutaImgPortada = '../../influencers/images/imgPortada.png';
}

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
                        <li class="nav__item"><a href="{{ url($menu['link']) }}" class="nav__link @if (request()->path() == $menu['link']) active @endif">{{ $menu['name'] }}</a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>

                <div class="nav__social">

                    <a href="#" class="nav__social-icon"><img src="{{ $rutaImgPerfil }}" alt=""> <label for="" class="nombrePer">{{ $nombre }}</label></a>
                </div>
            </div>
        </nav>
    </header>

    <main role="main" class="mb-5">


        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-5 perfilPort" style="background-image: url('<?php echo $rutaImgPortada; ?>')">
                    <img src="{{ asset('influencers/images/editPortada.png') }}" id="imgPort" alt="imgPort">
                    <input type="file" id="inputFilePortada" accept=".png, .jpg, .jpeg" style="display: none;" />
                    <img src="{{ asset('influencers/images/savePerfil.png') }}" id="savemgPerfilPortada" alt="savePhoto">
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 perfilInf text-center">
                            <div class="perfilPhoto" style="background-image: url('<?php echo $rutaImgPerfil; ?>')">
                                <input type="file" id="inputFile" accept=".png, .jpg, .jpeg" style="display: none;" />
                                <img src="{{ asset('influencers/images/editPerfil.png') }}" id="changeImgPerfil" alt="perfilPhoto">
                                <img src="{{ asset('influencers/images/savePerfil.png') }}" id="savemgPerfil" alt="savePhoto">
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
                                let selectedFile = null;
                                let isImageValid = false;


                                let selectedFilePor = null;
                                let isImageValidPor = false;
                                $("#changeImgPerfil").on("click", function() {
                                    // Activa el campo de entrada para archivos (abre el diálogo de selección de archivos)
                                    $("#inputFile").click();
                                });

                                $("img#imgPort").on("click", function() {
                                    // Activa el campo de entrada para archivos (abre el diálogo de selección de archivos)
                                    $("input#inputFilePortada").click();
                                });

                                $("input#inputFilePortada").on("change", function() {
                                    selectedFilePor = this.files[0];

                                    if (selectedFilePor) {
                                        const allowedExtensions = ["image/png", "image/jpeg"];
                                        if (!allowedExtensions.includes(selectedFilePor.type)) {
                                            alert("Por favor, selecciona solo archivos PNG, JPG, o JPEG.");
                                            this.value = ''; // Restablecer el campo de entrada
                                            isImageValidPor = false; // Estado de imagen no válida
                                            return;
                                        }

                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const img = new Image();
                                            img.src = e.target.result;

                                            img.onload = function() {
                                                // if (img.width !== img.height) {
                                                //     Swal.fire({
                                                //         title: "¡Errores de dimensiones!",
                                                //         text: "La imagen debe ser cuadrada (mismo ancho y alto).",
                                                //         icon: "error",
                                                //     });
                                                //     $("#inputFile").val(
                                                //         ''); // Restablecer el campo de entrada
                                                //     isImageValidPor = false; // Estado de imagen no válida
                                                //     return;
                                                // }

                                                // Si la imagen es válida, establecer la imagen de fondo
                                                $(".perfilPort").css("background-image",
                                                    `url(${e.target.result})`);
                                                $("img#imgPort").addClass("upImgPort");
                                                $("#savemgPerfilPortada").attr("style",
                                                    "display:block!important");

                                                isImageValidPor = true; // Imagen válida
                                            };
                                        };
                                        reader.readAsDataURL(selectedFilePor); // Leer el archivo como datos URL
                                    }
                                });

                                $("img#savemgPerfilPortada").on("click", function() {
                                    Swal.fire({
                                        title: "Confirmar cambio de imagen",
                                        text: "¿Estás seguro de que deseas cambiar la imagen de portada?",
                                        icon: "question",
                                        showCancelButton: true,
                                        confirmButtonText: "Sí, cambiar",
                                        cancelButtonText: "No",
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const formData = new FormData();
                                            formData.append("image", selectedFilePor);
                                            var csrfToken = $('meta[name="csrf-token"]').attr(
                                                "content");
                                            $.ajax({
                                                url: '/upload-imagePort', // Ruta al controlador en Laravel
                                                type: 'POST',
                                                data: formData,
                                                headers: {
                                                    "X-CSRF-TOKEN": csrfToken,
                                                },
                                                contentType: false, // Necesario para archivos
                                                processData: false, // No procesar datos como string
                                                success: function(response) {
                                                    Swal.fire({
                                                        title: "Éxito",
                                                        text: "La imagen se ha subido correctamente.",
                                                        icon: "success",
                                                        confirmButtonText: "OK"
                                                    }).then(() => {
                                                        // Recargar la página después de que el usuario presiona "OK"
                                                        location.reload();
                                                    });
                                                    $(".perfilPort").css(
                                                        "background-image",
                                                        `url(${response.image_url})`);

                                                    $("img#changeImgPerfil").removeClass(
                                                        "upImgPort");
                                                    $("img#savemgPerfilPortada").attr("style",
                                                        "display:none!important");
                                                },
                                                error: function() {
                                                    Swal.fire("Error",
                                                        "Hubo un problema al subir la imagen.",
                                                        "error");
                                                }
                                            });

                                            $("img#changeImgPerfil").removeClass("upImg");
                                            $("img#savemgPerfil").attr("style",
                                                "display:none!important");
                                        }
                                    });
                                });

                                $("#inputFile").on("change", function() {
                                    selectedFile = this.files[0];
                                    if (selectedFile) {
                                        const allowedExtensions = ["image/png", "image/jpeg"];
                                        if (!allowedExtensions.includes(selectedFile.type)) {
                                            alert("Por favor, selecciona solo archivos PNG, JPG, o JPEG.");
                                            this.value = ''; // Restablecer el campo de entrada
                                            isImageValid = false; // Estado de imagen no válida
                                            return;
                                        }

                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const img = new Image();
                                            img.src = e.target.result;

                                            img.onload = function() {
                                                if (img.width !== img.height) {
                                                    Swal.fire({
                                                        title: "¡Errores de dimensiones!",
                                                        text: "La imagen debe ser cuadrada (mismo ancho y alto).",
                                                        icon: "error",
                                                    });
                                                    $("#inputFile").val(
                                                        ''); // Restablecer el campo de entrada
                                                    isImageValid = false; // Estado de imagen no válida
                                                    return;
                                                }

                                                // Si la imagen es válida, establecer la imagen de fondo
                                                $(".perfilPhoto").css("background-image",
                                                    `url(${e.target.result})`);
                                                $("img#changeImgPerfil").addClass("upImg");
                                                $("img#savemgPerfil").attr("style",
                                                    "display:block!important");

                                                isImageValid = true; // Imagen válida
                                            };
                                        };
                                        reader.readAsDataURL(selectedFile); // Leer el archivo como datos URL
                                    }
                                });

                                $("img#savemgPerfil").on("click", function() {
                                    if (!isImageValid) {
                                        Swal.fire({
                                            title: "Imagen no válida",
                                            text: "Por favor, selecciona una imagen cuadrada y de tipo PNG o JPEG.",
                                            icon: "error",
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "Confirmar cambio de imagen",
                                            text: "¿Estás seguro de que deseas cambiar la imagen de perfil?",
                                            icon: "question",
                                            showCancelButton: true,
                                            confirmButtonText: "Sí, cambiar",
                                            cancelButtonText: "No",
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const formData = new FormData();
                                                formData.append("image", selectedFile);
                                                var csrfToken = $('meta[name="csrf-token"]').attr(
                                                    "content");
                                                $.ajax({
                                                    url: '/upload-image', // Ruta al controlador en Laravel
                                                    type: 'POST',
                                                    data: formData,
                                                    headers: {
                                                        "X-CSRF-TOKEN": csrfToken,
                                                    },
                                                    contentType: false, // Necesario para archivos
                                                    processData: false, // No procesar datos como string
                                                    success: function(response) {
                                                        Swal.fire({
                                                            title: "Éxito",
                                                            text: "La imagen se ha subido correctamente.",
                                                            icon: "success",
                                                            confirmButtonText: "OK"
                                                        }).then(() => {
                                                            // Recargar la página después de que el usuario presiona "OK"
                                                            location.reload();
                                                        });

                                                        $("img#changeImgPerfil").removeClass(
                                                            "upImg");
                                                        $("img#savemgPerfil").attr("style",
                                                            "display:none!important");
                                                    },
                                                    error: function() {
                                                        Swal.fire("Error",
                                                            "Hubo un problema al subir la imagen.",
                                                            "error");
                                                    }
                                                });

                                                $("img#changeImgPerfil").removeClass("upImg");
                                                $("img#savemgPerfil").attr("style",
                                                    "display:none!important");
                                            }
                                        });
                                    }
                                });
                            </script>