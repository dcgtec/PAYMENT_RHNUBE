AOS.init();
let sent = true;
var url_actual = $(location).attr('origin');
console.log(url_actual)
var ocultar = false;
$(".form-1 .previous").click(function () {
    location.href = url_actual + '/tarifario-remoto';
});

$(".regresar").click(function () {
    $("#contra-error").hide();
    $("#usuario_error").hide();
    $(".form-5").hide();
    $(".form-1").fadeIn(200);
});
$("#inner-wrap.form-1 form").submit(function (evt) {
    $("#contra-error").hide();
    evt.preventDefault();
    $(".form-1").hide();
    $(".form-5").fadeIn(200);

    /*    if (ocultar) {
           $(".form-1").hide();
           $(".form-3").fadeIn(200);
           $(".usuAdm").hide();
           $(".usuEmpresa").fadeIn(200);
       } else {
           $(".form-1").hide();
           $(".form-2").fadeIn(200);
       } */
});

console.log($('meta[name="csrf-token"]').attr("content"))

function validarUsuario() {
    $("#contra-error").hide();
    $("#usuario_error").hide();

    var email = $('#emailReg').val();
    var passw = $('#password').val();
    if (!sent) {

        $.ajax({
            type: "post",
            url: "/validarUusuario",
            data: {
                email: email,
                password: passw,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (data) {
                if (data.respuesta == 1) {
                    ocultar = true;
                    if (data.success) {
                        $("#usuario_error").hide();
                        $(".form-1").hide();
                        $(".form-5").hide();
                        $(".form-3").fadeIn(200);
                        $(".usuAdm").hide();
                        $(".usuEmpresa").fadeIn(200);
                    } else {
                        $("#usuario_error").show();
                    }

                } else {
                    ocultar = false;
                    $(".form-1").hide();
                    $(".form-5").hide();
                    $(".form-2").fadeIn(200);
                }
            },
        });
    }
}
$("#inner-wrap.form-2 form").submit(function (evt) {
    evt.preventDefault();

    $(".form-2").hide();
    $(".form-3").fadeIn(200);
    $(".usuAdm").hide();
    $(".usuEmpresa").fadeIn(200);



});

$("#inner-wrap.form-3 form").submit(function (evt) {
    evt.preventDefault();
    $("#n-error").hide();
    $("#ap-error").hide();
    $("#am-error").hide();
    $("#correo-error").hide();
    $("#ruc-error").hide();
    $("#razonSocial-error").hide();
    $("#pais-error").hide();
    $("#tipo-error").hide();
    $("#contra-error").hide();



    $('#cargaEmpresa').modal({ backdrop: 'static', keyboard: false });
    $('#cargaEmpresa').modal("show");
    setInterval(changeColor, 1000);
    //aca q se muestre la modal oka
    //optengo datos para registrar
    //datos de usuario
    var nombres = $("#nombres").val();
    var apPaterno = $("#apePa").val();
    var apMaterno = $("#apeMa").val();
    var email = $('#emailReg').val();
    var password = $("#password").val();
    //datos de organizacion
    var ruc = $('#identificador').val();
    var razonSocial = $('#nomRaz').val();
    var pais = $('#pais').val();
    var tipo = $('#organizacionS').val();

    $.ajax({
        type: "POST",
        url: "/registrarOrganizacion",
        data: {
            nombres,
            apPaterno,
            apMaterno,
            email,
            password,
            organi_ruc: ruc,
            razonSocial,
            pais,
            tipo
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            if (data.success) {
                location.href = url_actual + '/RegistroEmpleados';
            } else {
                setTimeout(function () { $('#cargaEmpresa').modal('hide'); }, 1000);
                if (data.respuesta == "error") {
                    $("#contra-error").show();
                    $("#test").prop('checked', false);
                    $(".form-2").hide();
                    $(".form-1").fadeIn(200);
                    $(".form-3").hide();
                } else {
                    $.notifyClose();
                    $.notify({
                        message: data.respuesta,
                        icon: "admin/images/warning.svg",
                    }, {
                        position: "fixed",
                        placement: {
                            from: "top",
                            align: "center",
                        },
                        icon_type: "image",
                        allow_dismiss: true,
                        newest_on_top: true,
                        delay: 10000,
                        template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                            '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                            '<span data-notify="title">{1}</span> ' +
                            '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                            "</div>",
                        spacing: 35,
                    });
                }
            }
            //aca se cierra la modal

        },
        error: function (data) {
            setTimeout(function () { $('#cargaEmpresa').modal('hide'); }, 1000);
            var error = true;
            if (data.responseJSON.errors.email) {
                error = false;
                $("#test").prop('checked', false);
                $(".form-2").hide();
                $(".form-3").hide();
                $(".form-1").fadeIn(200);

                $("#correo-error").html(data.responseJSON.errors.email);
                $("#correo-error").show();
            }
            if (data.responseJSON.errors.nombres) {
                if (error) {
                    $(".form-1").hide();
                    $(".form-3").hide();
                    $(".form-2").fadeIn(200);

                    $(".usuEmpresa").hide();
                    $(".usuAdm").fadeIn(200);
                }
                $("#n-error").html(data.responseJSON.errors.nombres);
                $("#n-error").show();
            }
            if (data.responseJSON.errors.apPaterno) {
                if (error) {
                    $(".form-1").hide();
                    $(".form-3").hide();
                    $(".form-2").fadeIn(200);

                    $(".usuEmpresa").hide();
                    $(".usuAdm").fadeIn(200);
                }
                $("#ap-error").html(data.responseJSON.errors.apPaterno);
                $("#ap-error").show();
            }
            if (data.responseJSON.errors.apMaterno) {
                if (error) {
                    $(".form-1").hide();
                    $(".form-3").hide();
                    $(".form-2").fadeIn(200);

                    $(".usuEmpresa").hide();
                    $(".usuAdm").fadeIn(200);
                }
                $("#am-error").html(data.responseJSON.errors.apMaterno);
                $("#am-error").show();
            }

            if (data.responseJSON.errors.organi_ruc) {
                $("#ruc-error").html(data.responseJSON.errors.organi_ruc);
                $("#ruc-error").show();
            }
            if (data.responseJSON.errors.razonSocial) {
                $("#razonSocial-error").html(data.responseJSON.errors.razonSocial);
                $("#razonSocial-error").show();
            }
            if (data.responseJSON.errors.pais) {
                $("#pais-error").html(data.responseJSON.errors.pais);
                $("#pais-error").show();
            }
            if (data.responseJSON.errors.tipo) {
                $("#tipo-error").html(data.responseJSON.errors.tipo);
                $("#tipo-error").show();
            }
            if (data.status == 422) {
                console.clear();
            }
        }
    });

});

$(".form-2 form#validarRegistroPer .previous").click(function () {
    $("#test").prop('checked', false);
    $(".form-2").hide();
    $(".form-1").fadeIn(200);
});

$(".form-3  .previous").click(function () {
    if (ocultar) {
        $("#test").prop('checked', false);
        $(".form-2").hide();
        $(".form-1").fadeIn(200);
    } else {
        $(".form-3").hide();
        $(".form-2").fadeIn(200);

        $(".usuEmpresa").hide();
        $(".usuAdm").fadeIn(200);
    }

});

const togglePassword = document.querySelector("#togglePassword");
const togglePassword2 = document.querySelector("#conf-togglePassword");

const password = document.querySelector("#password");
const password2 = document.querySelector("#passwordConfR");

togglePassword.addEventListener("click", () => {
    const type = password.getAttribute("type") === "password" ? "text" : "password";

    password.setAttribute("type", type);

    togglePassword.classList.toggle("bi-eye");
});

togglePassword2.addEventListener("click", () => {
    const type = password2.getAttribute("type") === "password" ? "text" : "password";

    password2.setAttribute("type", type);

    togglePassword2.classList.toggle("bi-eye");
});

/*Validacion del formulario de login*/

$("input#emailReg").on("blur", function () {
    $("div#correo-error").text("").hide("200");
    if (!$(this).val().match(/^([a-zA-Z0-9_\.\+\-])+\@(([a-zA-z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/)) {
        $("div#correo-error").text("Escriba su email correctamente").show("200");
    }
});

$("input#password").on("blur", function () {
    let password = $(this).val();
    $("span#contra-error").text("").hide("200");

    if (!/^(.{8,20}$)/.test(password)) {
        $("span#contra-error").text("La contraseña debe tener entre 8 y 20 caracteres.").show("200");
    } else if (!/^(?=.*[A-Z])/.test(password)) {
        $("span#contra-error").text("La contraseña debe contener al menos una mayscula.").show("200");
    } else if (!/^(?=.*[a-z])/.test(password)) {
        $("span#contra-error").text("La contraseña debe contener al menos una minúscula.").show("200");
    } else if (!/^(?=.*[0-9])/.test(password)) {
        $("span#contra-error").text("La contraseña debe contener al menos un dí­gito").show("200");
    } else if (!/^(?=.*[@#$%&])/.test(password)) {
        $("span#contra-error").text("La contraseña debe contener caracteres especiales de @#$%&.").show("200");
    }

});

$("input#passwordConfR").on("blur", function () {
    let confipassword = $(this).val();
    let password = $("input#password").val();
    $("span#confcontra-error").text("").hide("200");


    if (confipassword != password) {
        sent = true;
        $("span#confcontra-error").text("Las contraseñas deben coincidir").show("200");
    } else sent = false;

});

$('input#test').change(function () {
    $("span#checkTe-error").text("").hide("200");
    let isChecked = $(this)[0].checked;

    if (!isChecked) {
        $("span#checkTe-error").text("Debe aceptar los términso y condiciones").show("200");
    }
});



/*Validacion del formulario de perfil*/

$("input#nombres").on("blur", function () {
    $("div#n-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$')) {
        $("div#n-error").text("Escriba su nombre correctamente").show("200");
    }
});


$("input#apePa").on("blur", function () {
    $("div#ap-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$')) {
        $("div#ap-error").text("Escriba su apellido paterno correctamente").show("200");
    }
});

$("input#apeMa").on("blur", function () {
    $("div#am-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$')) {
        $("div#am-error").text("Escriba su apellido materno correctamente").show("200");
    }
});


/*Validacion del formulario registrar organizacion*/

$("input#identificador").on("blur", function () {
    $("div#ruc-error").text("").hide("200");
    if (!$(this).val().match("^[A-Za-z0-9]+$")) {
        $("div#ruc-error").text("Escriba su identificador correctamente").show("200");
    }
});

$("input#nomRaz").on("blur", function () {
    $("div#razonSocial-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val()) {
        $("div#razonSocial-error").text("Escriba su razón social correctamente").show("200");
    }
});


/*=============================================
FLOATING RANDOM
=============================================*/
$(document).ready(function () {
    var classes = ["float-animation-1", "float-animation-2"];
    var classes2 = ["float-animation-1", "float-animation-2", "float-animation-3"];
    var classes3 = ["float-animation-1", "float-animation-2", "float-animation-3"];
    $(".usuAdm img#float-animation").each(function () {
        $(this).addClass(classes.splice(~~(Math.random() * classes.length), 1)[0]);
    });

    $(".usuPers img#float-animation").each(function () {
        $(this).addClass(classes2.splice(~~(Math.random() * classes2.length), 1)[0]);
    });

    $(".usuEmpresa img#float-animation").each(function () {
        $(this).addClass(classes3.splice(~~(Math.random() * classes3.length), 1)[0]);
    });
});

/*Validar select vacios*/
// $.validator.addMethod(
//     "valueNotEquals",
//     function (value, element, arg) {
//         return arg !== value;
//     },
//     "¡Selecccione un item!"
// );
$("[data-toggle='tooltip']").tooltip({
    animated: "fade",
    placement: "right",
    trigger: "hover",
}); // Initialize Tooltip


var apiURL = "https://restcountries.com/v3.1/all";

$.getJSON(apiURL, function (returnData) {
    var $select = $("select#pais");
    $.each(returnData, function (index, value) {
        if (value.name.common == $('#nombre_pais').val()) {
            $select.append("<option selected value=" + value.name.common + ">" + value.name.common + "</option>");
        } else {
            $select.append("<option value=" + value.name.common + ">" + value.name.common + "</option>");
        }
    });

    var my_options = $("#pais option");
    my_options.sort(function (a, b) {
        if (a.text > b.text) return 1;
        else if (a.text < b.text) return -1;
        else return 0
    })
    $("#pais").empty().append(my_options);

    // run chosen plugin
    $("#pais").chosen({
        no_results_text: "No results matched"
    });
});

function comprobarEmail() {
    var email = $("#emailReg").val();
    $.ajax({
        type: "get",
        url: "/persona/comprobar",
        data: {
            email: email,
        },
        success: function (data) {
            if (data == 1) {
                $('.usuario_registrado').hide();
                $('.camposRequeridos').prop('required', false);
                $("#texto").text("Este usuario ya existe, confirma tu contraseña para continuar.");
                sent = false;
            } else {
                $('.usuario_registrado').show();
                $('.camposRequeridos').prop('required', true);
                $("#texto").text("Ingrese su nueva contraseña y la confirmación.");
            }
        },
    });
}

var x = 0;
/*Carga de empresa*/
function changeColor() {
    x++;

    if (x == 5) {
        $(".changImgLoad").attr("src", "automatizacionv1/img/gifCarga/carga-2.gif");
        $("label.chanTextLoa").text("Generando plantillas y documentos legales del país");
        $(".progress-bar.bg-success-bar").attr("style", "width: 5%");
        $(".progress-bar.bg-success-bar").attr("aria-valuenow", "5");
    }

    if (x == 35) {
        $(".progress-bar.bg-success-bar").attr("style", "width: 35%");
        $(".progress-bar.bg-success-bar").attr("aria-valuenow", "35");
    }

    if (x == 50) {
        $(".changImgLoad").attr("src", "automatizacionv1/img/gifCarga/carga-3.gif");
        $("label.chanTextLoa").text("Creando interfase de usuario");
        $(".progress-bar.bg-success-bar").attr("style", "width: 50%");
        $(".progress-bar.bg-success-bar").attr("aria-valuenow", "50");
    }

    if (x == 65) {
        $(".changImgLoad").attr("src", "automatizacionv1/img/gifCarga/carga-4.gif");
        $("label.chanTextLoa").text("Generando interfase de empleados");
        $(".progress-bar.bg-success-bar").attr("style", "width: 65%");
        $(".progress-bar.bg-success-bar").attr("aria-valuenow", "65");
    }

    if (x == 75) {
        $(".changImgLoad").attr("src", "automatizacionv1/img/gifCarga/carga-5.gif");
        $("label.chanTextLoa").text("Generando interfase de empleados");
        $(".progress-bar.bg-success-bar").attr("style", "width: 75%");
        $(".progress-bar.bg-success-bar").attr("aria-valuenow", "75");
    }

    if (x == 100) {
        $(".changImgLoad").attr("src", "automatizacionv1/img/gifCarga/carga-6.gif");
        $("label.chanTextLoa").text("Sincronizando calendarios con las fechas festivas y feriados");
        $(".progress-bar.bg-success-bar").attr("style", "width: 100%");
        $(".progress-bar.bg-success-bar").attr("aria-valuenow", "100");
    }
}


