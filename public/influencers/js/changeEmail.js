$(document).ready(function () {
    $("#change-password-form").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },

            codigo_validacion: {
                required: true,
                minlength: 12,
                maxlength: 12,
                digits: true,
            },
        },
        messages: {
            email: {
                required: "Por favor ingrese su correo electrónico",
                email: "Por favor ingrese un correo electrónico válido",
            },

            codigo_validacion: {
                required: "Por favor ingrese el código de validación",
                minlength: "El código de validación debe tener 12 dígitos",
                maxlength: "El código de validación debe tener 12 dígitos",
                digits: "El código de validación debe ser numérico",
            },
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.siblings("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            $("button.enviarForm").attr("disabled", true);
            var token = $("input#token").val();
            var email = $("input#email").val();
            var codValidation = $("input#codigo-validacion").val();
            var _token = $("meta[name='csrf-token']").attr("content");

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": _token,
                },
            });

            $.ajax({
                url: "/changeEmail",
                data: {
                    token: token,
                    new_email: email,
                    codValidation: codValidation,
                },
                method: "post",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Éxito",
                            text: response.message,
                            icon: "success",
                        }).then(() => {
                            // Redirect to /iniciarSesion after success message is closed
                            window.location.href = "/iniciarSesion";
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.message,
                            icon: "error",
                        });
                    }

                    $("button.enviarForm").attr("disabled", false);
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "¡Error!",
                        text: response.message || "Ocurrió un error.",
                        icon: "error",
                    });

                    $("button.enviarForm").attr("disabled", false);
                },
            });

            return false; // Evita la acción predeterminada de enviar el formulario
        },
    });

    $.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
    );
});
