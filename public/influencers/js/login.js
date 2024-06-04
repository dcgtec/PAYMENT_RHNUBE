// Obtener elementos
const passwordInput = document.getElementById("passwordInput");
const togglePassword = document.getElementById("togglePassword");

// Agregar evento click al botón
togglePassword.addEventListener("click", function () {
    // Cambiar el tipo de entrada de la contraseña
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePassword.innerHTML = '<span class="far fa-eye"></span>'; // Cambiar el icono a un ojo abierto
    } else {
        passwordInput.type = "password";
        togglePassword.innerHTML = '<span class="far fa-eye-slash"></span>'; // Cambiar el icono a un ojo tachado
    }
});

const state = {};
const carouselList = document.querySelector(".carousel__list");
const carouselItems = document.querySelectorAll(".carousel__item");
const elems = Array.from(carouselItems);

carouselList.addEventListener("click", function (event) {
    var newActive = event.target;
    var isItem = newActive.closest(".carousel__item");

    if (!isItem || newActive.classList.contains("carousel__item_active")) {
        return;
    }

    update(newActive);
});

const update = function (newActive) {
    const newActivePos = newActive.dataset.pos;

    const current = elems.find((elem) => elem.dataset.pos == 0);
    const prev = elems.find((elem) => elem.dataset.pos == -1);
    const next = elems.find((elem) => elem.dataset.pos == 1);
    const first = elems.find((elem) => elem.dataset.pos == -2);
    const last = elems.find((elem) => elem.dataset.pos == 2);

    current.classList.remove("carousel__item_active");

    [current, prev, next, first, last].forEach((item) => {
        var itemPos = item.dataset.pos;

        item.dataset.pos = getPos(itemPos, newActivePos);
    });
};

const getPos = function (current, active) {
    const diff = current - active;

    if (Math.abs(current - active) > 2) {
        return -current;
    }

    return diff;
};

//**VALIDAR FORM */

$("#loginForm").validate({
    rules: {
        user: {
            required: true,
            email: true,
        },
        passwordInput: {
            required: true,
        },
    },
    messages: {
        user: {
            required: "Por favor, ingrese su correo electrónico.",
            email: "Por favor, ingrese un correo electrónico válido.",
        },
        passwordInput: {
            required: "Por favor, ingrese su contraseña.",
        },
    },
    errorPlacement: function (error, element) {
        error.appendTo(element.closest(".form-group"));
    },
    highlight: function (element) {
        $(element).addClass("is-invalid"); // Clase para indicar error
    },
    unhighlight: function (element) {
        $(element).removeClass("is-invalid"); // Remueve clase cuando es válido
    },
    submitHandler: function (form) {
        var user = $("#user").val();
        var passowrd = $("#passwordInput").val();

        // Enviar datos por AJAX a tu controlador
        $.ajax({
            type: "post",
            url: "/logear", // Asegúrate de que este sea el endpoint correcto
            data: {
                user: user,
                password: passowrd,
                recaptcha: grecaptcha.getResponse(),
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Para evitar ataques CSRF
            },
            success: function (response) {
                console.log(response);
                // Si la respuesta es exitosa, comprobar si hubo errores de validación
                if (response.success) {
                    window.location.href = "/perfil"; // Redirigir si el login fue exitoso
                } else {
                    // Verificar si hay errores de validación
                    if (response.errors) {
                        var errorMessages = "";
                        for (var key in response.errors) {
                            errorMessages += response.errors[key].join("\n");
                        }

                        Swal.fire({
                            title: "¡Errores de validación!",
                            text: errorMessages,
                            icon: "error",
                        });
                    } else {
                        Swal.fire({
                            title: "¡Error!",
                            text: response.message || "Ocurrió un error.",
                            icon: "error",
                        });
                    }
                }
            },
            error: function (xhr, status, error) {
                // Acciones si ocurre un error
                console.error("Error: " + xhr.responseText);
            },
        });
    },
});

$("a#forgot").click(function (event) {
    event.preventDefault(); // Prevent the default anchor behavior
    $("#forgotPassword").modal("show"); // Show the modal
});

var $forgotPasswordForm = $("#forgotPasswordForm");

$forgotPasswordForm.validate({
    rules: {
        emailCorreo: {
            required: true,
            email: true,
        },
    },
    messages: {
        emailCorreo: {
            required: "Por favor, ingrese su correo electrónico.",
            email: "Por favor, ingrese un correo electrónico válido.",
        },
    },
    errorPlacement: function (error, element) {
        error.appendTo(element.closest(".form-group"));
    },
    highlight: function (element) {
        $(element).addClass("is-invalid"); // Clase para indicar error
    },
    unhighlight: function (element) {
        $(element).removeClass("is-invalid"); // Remueve clase cuando es válido
    },
    submitHandler: function (form) {
        var emailCorreo = $("#emailCorreo").val();
        var $submitButton = $(form).find("button[type='submit']");
        $submitButton.prop("disabled", true);
        $.ajax({
            url: "/changeEmail",
            method: "GET", // Adjust method if necessary, POST is usually preferred for such actions
            data: {
                user: emailCorreo,
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    Swal.fire({
                        title: "¡Correcto!",
                        text: response.message,
                        icon: "success",
                    });
                    $("#emailCorreo").val("");
                } else {
                    Swal.fire({
                        title: "¡Error!",
                        text: response.message,
                        icon: "error",
                    });
                }

                $submitButton.prop("disabled", false);
            },
            error: function (xhr) {
                var errorMessage = xhr.responseJSON
                    ? xhr.responseJSON.message
                    : "Hubo un problema al cambiar el correo electrónico.";
                Swal.fire({
                    title: "Error",
                    text: errorMessage,
                    icon: "error",
                });

                $submitButton.prop("disabled", false);
            },
        });
    },
});

// $("#loginForm").on("submit", function (e) {
//     e.preventDefault(); // Evitar el comportamiento normal del formulario

// });
