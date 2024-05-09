$(document).ready(function () {

    window.onpageshow = function(event) {
        if (event.persisted) { // Si la página fue almacenada en caché
            window.location.reload(); // Recargar la página
        }
    };


    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.validator.addMethod(
        "telefonoPeru",
        function (value, element) {
            const telefonoPeruRegex = /^(?:9\d{8}|\d{7})$/; // 9 para móviles, 7 para fijos
            return this.optional(element) || telefonoPeruRegex.test(value); // Si es opcional, es válido
        },
        "Número de teléfono no válido"
    );

    $("#nombres,input#apPaterno,input#apMaterno").on("keyup", function () {
        var input = $(this).val();
        var capitalized = input.replace(/\b\w/g, function (char) {
            return char.toUpperCase();
        });
        $(this).val(capitalized);
    });

    // Configuración del validador
    $("#editarPerfil").validate({
        rules: {
            codigo: {
                required: true,
                number: true,
                minlength: 8,
                maxlength: 11, // Dependiendo de la longitud esperada para el código (DNI, CE, RUC)
            },
            nombres: {
                required: true,
                minlength: 2,
            },
            apPaterno: {
                required: true,
                minlength: 2,
            },
            apMaterno: {
                required: true,
                minlength: 2,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6,
            },
            telfono: {
                required: true, // Opcional, pero si está presente, se debe validar
                telefonoPeru: true, // Usa el método personalizado
            },
            cargo: {
                required: true,
            },
            // Reglas para campos opcionales que deben ser URLs si se completan
            facebook: {
                required: true,
                url: true, // Debe ser una URL válida si está presente
            },
            linkedIn: {
                url: true, // Debe ser una URL válida si está presente
            },
            instagram: {
                url: true, // Debe ser una URL válida si está presente
            },
            tiktok: {
                url: true, // Debe ser una URL válida si está presente
            },
        },
        messages: {
            codigo: {
                required: "Ingrese el código",
                number: "Debe ser un número",
                minlength: "El código debe tener al menos 8 dígitos",
                maxlength: "El código no debe exceder 11 dígitos",
            },
            nombres: {
                required: "Ingrese sus nombres",
                minlength: "Debe tener al menos 2 caracteres",
            },
            apPaterno: {
                required: "Ingrese el apellido paterno",
                minlength: "Debe tener al menos 2 caracteres",
            },
            apMaterno: {
                required: "Ingrese el apellido materno",
                minlength: "Debe tener al menos 2 caracteres",
            },
            email: {
                required: "Ingrese el correo electrónico",
                email: "Ingrese un correo electrónico válido",
            },
            password: {
                required: "Ingrese la contraseña",
                minlength: "La contraseña debe tener al menos 6 caracteres",
            },
            telfono: {
                required: "Ingrese su número de telefono",
                telefonoPeru: "Número de teléfono no válido",
            },
            cargo: {
                required: "Ingrese su cargo",
            },
            facebook: {
                required: "Ingrese su cuenta de facebook",
                url: "Ingrese una URL válida para Facebook",
            },
            linkedIn: {
                url: "Ingrese una URL válida para LinkedIn",
            },
            instagram: {
                url: "Ingrese una URL válida para Instagram",
            },
            tiktok: {
                url: "Ingrese una URL válida para TikTok",
            },
        },
        errorPlacement: function (error, element) {
            if (element.is("input[name='facebook']")) {
                // Coloca el error después del div que contiene el ícono y el input
                element.closest(".redSocial").append(error);
            } else if (element.is("input[name='cargo']")) {
                // Para cargo, coloca el error en la posición deseada
                element.closest(".form-group").append(error);
            } else {
                // Para otros campos, usa el comportamiento predeterminado
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
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
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            $.ajax({
                url: "/registrarNuevo",
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
                    tiktok: tiktok,
                },
                method: "POST",
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            title: "¡Éxito!",
                            text: "Datos guardados correctamente.",
                            icon: "success",
                        }).then(() => {
                            window.location.href = "/perfil";
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
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Ocurrió un error.",
                        icon: "error",
                    });

                    $("button.enviarForm").attr("disabled", false);
                },
            });
        },
    });
});
