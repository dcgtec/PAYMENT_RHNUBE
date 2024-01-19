//1 : mensual 2 : trimestral 3: anual
/*Telefono con prefijo */
let sent = false;
let send = true;
var acronimo = $('#nombre_pais').val().toUpperCase();
switch (acronimo) {
    case "MEXICO":
        var cc3 = "MX";
        break;
    case "CHILE":
        var cc3 = "CL";
        break;
    case "GUATEMALA":
        var cc3 = "GT";
        break;
    case "URUGUAY":
        var cc3 = "UY";
        break;
    case "PARAGUAY":
        var cc3 = "PY";
        break;
    case "UNITED STATES":
        var cc3 = "US";
        break;
    default:
        var cc3 = acronimo.substr(0, 2);
        break;
}
$("[data-toggle='tooltip']").tooltip({
    animated: "fade",
    placement: "right",
    trigger: "hover",
});
const phoneInputField = document.querySelector("#telRegistro");
const phoneInput = window.intlTelInput(phoneInputField, {
    initialCountry: cc3,
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});

var handleChange = function () {
    var text = phoneInput.isValidNumber() ? phoneInput.getNumber() : "";
    console.log(text);
    $("#telGe").val(text);
};

phoneInputField.addEventListener("change", handleChange);
phoneInputField.addEventListener("keyup", handleChange);

var opcion = 1;
var compra = "";
//mensual
var mensual_pro = 3.00;
var mensual_enterprice = 5.00;
var servicios_mensual = 0.25;
var servicio_portal = 1.00;
var servicos_reconocimiento = 0.50;

//trimestrar
var trimestral_pro = 2.70;
var trimestral_enterprice = 4.50;
var servicios_trimestral = 0.23;
var servicio_portal_tri = 0.90;
var servicos_reconocimiento_tri = 0.45;

//anual
var anual_pro = 2.25;
var anual_enterprice = 3.75;
var servicios_anual = 0.19;
var servicio_portal_anu = 0.75;
var servicos_reconocimiento_anu = 0.38;
//para enviar correo
var soporte_pro = 0;
var soporte_enterprice = 0;
var erp_pro = 0;
var erp_enterprice = 0;

if (window.matchMedia("(min-width: 769px)").matches) {
    var url_actual = $(location).attr('origin');
    location.href = url_actual + '/tarifario-remoto';
}
$("#total_pro").text(mensual_pro.toFixed(2));
$("#total_interprise").text(mensual_enterprice.toFixed(2));
$(".total_pro").text(mensual_pro.toFixed(2));
$(".total_interprise").text(mensual_enterprice.toFixed(2));
$(".sercios_adicinal").text("$" + servicios_mensual.toFixed(2));
$(".sercios_portal").text("$" + servicio_portal.toFixed(2));
$(".servicios_reconocimiento").text("$" + servicos_reconocimiento.toFixed(2));


//mensual
$("#switchMensual").click(function () {
    $(".sercios_adicinal").text("$" + servicios_mensual);
    $(".sercios_portal").text("$" + servicio_portal.toFixed(2));
    $(".servicios_reconocimiento").text("$" + servicos_reconocimiento.toFixed(2));

    var total_mensual_pro = mensual_pro;
    var total_mensual_interprise = mensual_enterprice;

    if ($('#switch1').is(":checked")) {
        total_mensual_pro = total_mensual_pro + servicos_reconocimiento;
    }
    /*  if ($('#switch2').is(":checked")) {
         total_mensual_pro = total_mensual_pro + servicios_mensual;
     } */
    if ($('#switch3').is(":checked")) {
        total_mensual_pro = total_mensual_pro + servicio_portal;
    }
    if ($('#switch4').is(":checked")) {
        total_mensual_pro = total_mensual_pro + servicios_mensual;
    }
    if ($('#switch5').is(":checked")) {
        total_mensual_pro = total_mensual_pro + servicios_mensual;
    }

    $("#total_pro").text(total_mensual_pro.toFixed(2));
    $("#total_interprise").text(total_mensual_interprise.toFixed(2));
    $(".total_pro").text(total_mensual_pro.toFixed(2));
    $(".total_interprise").text(total_mensual_interprise.toFixed(2));
    opcion = 1;
});
//trimestral
$("#switchTrimestral").click(function () {
    $(".sercios_adicinal").text("$" + servicios_trimestral);
    $(".sercios_portal").text("$" + servicio_portal_tri.toFixed(2));
    $(".servicios_reconocimiento").text("$" + servicos_reconocimiento_tri);

    var total_trimestar_pro = trimestral_pro;
    var total_trimestral_interprise = trimestral_enterprice;

    if ($('#switch1').is(":checked")) {
        total_trimestar_pro = total_trimestar_pro + servicos_reconocimiento_tri;
    }
    /*  if ($('#switch2').is(":checked")) {
         total_trimestar_pro = total_trimestar_pro + servicios_trimestral;
     } */
    if ($('#switch3').is(":checked")) {
        total_trimestar_pro = total_trimestar_pro + servicio_portal_tri;
    }
    if ($('#switch4').is(":checked")) {
        total_trimestar_pro = total_trimestar_pro + servicios_trimestral;
    }
    if ($('#switch5').is(":checked")) {
        total_trimestar_pro = total_trimestar_pro + servicios_trimestral;
    }

    $("#total_pro").text(total_trimestar_pro.toFixed(2));
    $("#total_interprise").text(total_trimestral_interprise.toFixed(2));
    $(".total_pro").text(total_trimestar_pro.toFixed(2));
    $(".total_interprise").text(total_trimestral_interprise.toFixed(2));
    opcion = 2;
});
//anual
$("#switchAnual").click(function () {
    $(".sercios_adicinal").text("$" + servicios_anual);
    $(".sercios_portal").text("$" + servicio_portal_anu.toFixed(2));
    $(".servicios_reconocimiento").text("$" + servicos_reconocimiento_anu);

    var total_anual_pro = anual_pro;
    var total_anual_interprise = anual_enterprice;

    if ($('#switch1').is(":checked")) {
        total_anual_pro = total_anual_pro + servicos_reconocimiento_anu;
    }
    /*  if ($('#switch2').is(":checked")) {
         total_anual_pro = total_anual_pro + servicios_anual;
     } */
    if ($('#switch3').is(":checked")) {
        total_anual_pro = total_anual_pro + servicio_portal_anu;
    }
    if ($('#switch4').is(":checked")) {
        total_anual_pro = total_anual_pro + servicios_anual;
    }
    if ($('#switch5').is(":checked")) {
        total_anual_pro = total_anual_pro + servicios_anual;
    }

    $("#total_pro").text(total_anual_pro.toFixed(2));
    $("#total_interprise").text(total_anual_interprise.toFixed(2));
    $(".total_pro").text(total_anual_pro.toFixed(2));
    $(".total_interprise").text(total_anual_interprise.toFixed(2));
    opcion = 3;
});
$("input#switch1").on("change", function () {
    var total_pro = document.getElementById('total_pro');
    switch (opcion) {
        case 1:
            var servicio = servicos_reconocimiento;
            break;
        case 2:
            var servicio = servicos_reconocimiento_tri;
            break;
        case 3:
            var servicio = servicos_reconocimiento_anu;
            break;
    }
    if ($(this).is(":checked")) {
        $("input#switch2").prop('checked', true);
        //inicio check dos
        $(".serv3 p.preSer").hide();
        $(".serv3 img.showImg").fadeIn("200");
        $(".serv3 .switchE").addClass("newColor");
        //fin
        $(".serv1 p.preSer").hide();
        $(".serv1 img.showImg").fadeIn("200");
        $(".serv1 .switchE").addClass("newColor");
        var contenido_pro = parseFloat(total_pro.innerHTML) + servicio;

        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    } else {
        $("input#switch2").prop('checked', false);
        //inicio check dos
        $(".serv3 .switchE").removeClass("newColor");
        $(".serv3 p.preSer").fadeIn("200");
        $(".serv3 img.showImg").hide();
        //fin
        $(".serv1 .switchE").removeClass("newColor");
        $(".serv1 p.preSer").fadeIn("200");
        $(".serv1 img.showImg").hide();
        var contenido_pro = parseFloat(total_pro.innerHTML) - servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    }
});

$("input#switch2").on("change", function () {
    var total_pro = document.getElementById('total_pro');
    switch (opcion) {
        case 1:
            var servicio = servicos_reconocimiento;
            break;
        case 2:
            var servicio = servicos_reconocimiento_tri;
            break;
        case 3:
            var servicio = servicos_reconocimiento_anu;
            break;
    }
    if ($(this).is(":checked")) {
        $("input#switch1").prop('checked', true);
        //inicio de cambio sw1
        $(".serv1 p.preSer").hide();
        $(".serv1 img.showImg").fadeIn("200");
        $(".serv1 .switchE").addClass("newColor");
        //fin
        $(".serv3 p.preSer").hide();
        $(".serv3 img.showImg").fadeIn("200");
        $(".serv3 .switchE").addClass("newColor");
        var contenido_pro = parseFloat(total_pro.innerHTML) + servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    } else {
        $("input#switch1").prop('checked', false);
        //inico de cambio sw1
        $(".serv1 .switchE").removeClass("newColor");
        $(".serv1 p.preSer").fadeIn("200");
        $(".serv1 img.showImg").hide();
        //fin
        $(".serv3 .switchE").removeClass("newColor");
        $(".serv3 p.preSer").fadeIn("200");
        $(".serv3 img.showImg").hide();
        var contenido_pro = parseFloat(total_pro.innerHTML) - servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    }
});

$("input#switch3").on("change", function () {
    var total_pro = document.getElementById('total_pro');
    switch (opcion) {
        case 1:
            var servicio = servicio_portal;
            break;
        case 2:
            var servicio = servicio_portal_tri;
            break;
        case 3:
            var servicio = servicio_portal_anu;
            break;
    }
    if ($(this).is(":checked")) {
        $(".serv5 p.preSer").hide();
        $(".serv5 img.showImg").fadeIn("200");
        $(".serv5 .divGen").addClass("newColor");
        var contenido_pro = parseFloat(total_pro.innerHTML) + servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    } else {
        $(".serv5 .divGen").removeClass("newColor");
        $(".serv5 p.preSer").fadeIn("200");
        $(".serv5 img.showImg").hide();
        var contenido_pro = parseFloat(total_pro.innerHTML) - servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    }
});

$("input#switch4").on("change", function () {
    var total_pro = document.getElementById('total_pro');
    switch (opcion) {
        case 1:
            var servicio = servicios_mensual;
            break;
        case 2:
            var servicio = servicios_trimestral;
            break;
        case 3:
            var servicio = servicios_anual;
            break;
    }
    if ($(this).is(":checked")) {
        $(".serv7 p.preSer").hide();
        $(".serv7 img.showImg").fadeIn("200");
        $(".serv7 .switchE").addClass("newColor");
        var contenido_pro = parseFloat(total_pro.innerHTML) + servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    } else {
        $(".serv7 .switchE").removeClass("newColor");
        $(".serv7 p.preSer").fadeIn("200");
        $(".serv7 img.showImg").hide();
        var contenido_pro = parseFloat(total_pro.innerHTML) - servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    }
});

$("input#switch5").on("change", function () {
    var total_pro = document.getElementById('total_pro');
    switch (opcion) {
        case 1:
            var servicio = servicios_mensual;
            break;
        case 2:
            var servicio = servicios_trimestral;
            break;
        case 3:
            var servicio = servicios_anual;
            break;
    }
    if ($(this).is(":checked")) {
        $(".serv8 p.preSer").hide();
        $(".serv8 img.showImg").fadeIn("200");
        $(".serv8 .switchE").addClass("newColor");
        var contenido_pro = parseFloat(total_pro.innerHTML) + servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    } else {
        $(".serv8 .switchE").removeClass("newColor");
        $(".serv8 p.preSer").fadeIn("200");
        $(".serv8 img.showImg").hide();
        var contenido_pro = parseFloat(total_pro.innerHTML) - servicio;
        $("#total_pro").text(contenido_pro.toFixed(2));
        $(".total_pro").text(contenido_pro.toFixed(2));
    }
});

$("input#switch6").on("change", function () {
    if ($(this).is(":checked")) {
        $(".serv9 .primerMul p.preSer").hide();
        $(".serv9 .primerMul img.showImg").fadeIn("200");
        $(".serv9 .switchE.primerMul").addClass("newColor");
    } else {
        $(".serv9 .switchE.primerMul").removeClass("newColor");
        $(".serv9 .primerMul p.preSer").fadeIn("200");
        $(".serv9 .primerMul img.showImg").hide();
    }
});

$("input#switch7").on("change", function () {
    if ($(this).is(":checked")) {
        $(".serv9 .segrMul p.preSer").hide();
        $(".serv9 .segrMul img.showImg").fadeIn("200");
        $(".serv9 .switchE.segrMul").addClass("newColor");
    } else {
        $(".serv9 .switchE.segrMul").removeClass("newColor");
        $(".serv9 .segrMul p.preSer").fadeIn("200");
        $(".serv9 .segrMul img.showImg").hide();
    }
});

$("input#switch8").on("change", function () {
    if ($(this).is(":checked")) {
        $(".serv9 .cuartoMul p.preSer").hide();
        $(".serv9 .cuartoMul img.showImg").fadeIn("200");
        $(".serv9 .switchE.cuartoMul").addClass("newColor");
    } else {
        $(".serv9 .switchE.cuartoMul").removeClass("newColor");
        $(".serv9 .cuartoMul p.preSer").fadeIn("200");
        $(".serv9 .cuartoMul img.showImg").hide();
    }
});

$("input#switch9").on("change", function () {
    if ($(this).is(":checked")) {
        $(".serv9 .tercerMul p.preSer").hide();
        $(".serv9 .tercerMul img.showImg").fadeIn("200");
        $(".serv9 .switchE.tercerMul").addClass("newColor");
    } else {
        $(".serv9 .switchE.tercerMul").removeClass("newColor");
        $(".serv9 .tercerMul p.preSer").fadeIn("200");
        $(".serv9 .tercerMul img.showImg").hide();
    }
});

$("#header button.previous").click(function () {
    var url_actual = $(location).attr('origin');
    location.href = url_actual + '/modo-remoto';
});


$(".compra_pro").click(function () {
    compra = "PRO";
    if ($('#switch6').is(":checked")) soporte_pro = 1; else soporte_pro = 0;
    if ($('#switch7').is(":checked")) erp_pro = 1; else erp_pro = 0;
   /*  $("div#forumuRegistro").modal({ backdrop: "static", keyboard: false }, "show"); */
    var url_actual = $(location).attr('origin');
    location.href = url_actual + '/RegistroOrganizacion?modo=PRO';
});
$(".compra_enterprise").click(function () {
    compra = "ENTERPRISE";
    if ($('#switch8').is(":checked")) soporte_enterprice = 1; else soporte_enterprice = 0;
    if ($('#switch9').is(":checked")) erp_enterprice = 1; else erp_enterprice = 0;
    /*  $("div#forumuRegistro").modal({ backdrop: "static", keyboard: false }, "show"); */
    var url_actual = $(location).attr('origin');
    location.href = url_actual + '/RegistroOrganizacion?modo=ENTERPRISE';
});

$("div#forumuRegistro .previous ").click(function () {
    $("div#forumuRegistro").modal("hide");
});



/*Validar -1 o vacio*/

$("#cantCola").on("keyup change", function (e) {
    var cant = $(this).val();
    if (cant <= -1) {
        $(this).val("1");
    }
});

/*Validacion del formulario de cliente*/

$("input#nombreRegistro").on("blur", function () {
    $("div#nombres-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$')) {
        $("div#nombres-error").text("Escriba su nombre correctamente").show("200");
    }
});

$("input#ApellidoRegistro").on("blur", function () {
    $("div#apellidos-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s ]+$')) {
        $("div#apellidos-error").text("Escriba sus apellidos correctamente").show("200");
    }
});

$("input#correoRegistro").on("blur", function () {
    $("div#email-error").text("").hide("200");
    if (!$(this).val().match(/^([a-zA-Z0-9_\.\+\-])+\@(([a-zA-z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/)) {
        $("div#email-error").text("Escriba su email correctamente").show("200");
    }
});

$("input#telRegistro").on("blur", function () {
    $("div#movil-error").text("").hide("200");
    if ($("#telGe").val().length == 0) {
        send = true;
        $("div#movil-error").text("Debe ingresar un número móvil válido").show("200");
    } else send = false;
});

$("input#cantCola").on("blur", function () {
    $("div#colaboradores-error").text("").hide("200");
    if (!$(this).val().match("^[0-9]+$")) {
        $("div#colaboradores-error").text("Escriba su número de colaboradores correctamente").show("200");
    } else if ($(this).val() < 1) {
        $("div#colaboradores-error").text("El número de colaboradores tiene que ser mayor a 0").show("200");

    }
});

$("form#validarRegistro").submit(function (evt) {
    evt.preventDefault();
    if (!send) {
        send = true;
        $("#nombres-error").hide();
        $("#apellidos-error").hide();
        $("#email-error").hide();
        $("#movil-error").hide();
        $("#colaboradores-error").hide();
        $("#pais-error").hide();
        $("#pago-error").hide();
        $("div#forumuRegistro").modal("hide");

        $("#formCompro").modal({ backdrop: "static", keyboard: false });

    }
});


var apiURL = "https://restcountries.com/v3.1/all";

$.getJSON(apiURL, function (returnData) {
    var $select = $("select#paises");
    $.each(returnData, function (index, value) {
        if (value.name.common == $('#nombre_pais').val()) {
            $select.append("<option selected value=" + value.name.common + ">" + value.name.common + "</option>");
        } else {
            $select.append("<option value=" + value.name.common + ">" + value.name.common + "</option>");
        }
    });

    var my_options = $("#paises option");
    my_options.sort(function (a, b) {
        if (a.text > b.text) return 1;
        else if (a.text < b.text) return -1;
        else return 0
    })
    $("#paises").empty().append(my_options);

    // run chosen plugin
    $("#paises").chosen({ no_results_text: "No results matched" });
});

$("#paises").on("change", function () {
    $("#pais-error").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-Z ]+$')) {
        $("#pais-error").text("Seleccione un país").show("200");
    }
});

$("#formAsignarMedioPago").submit(function (e) {
    e.preventDefault();
    if (!sent) {
        this.submit();
        sent = true;
    }
});

function asignar_medio_pago() {
    var formData = new FormData($('#validarRegistro')[0]);
    if (compra == "PRO") {
        var soporte = soporte_pro;
        var erp = erp_pro;
        var total_pro = document.getElementById('total_pro');
        var pago_total = parseFloat(total_pro.innerHTML);
    } else {
        var soporte = soporte_enterprice;
        var erp = erp_enterprice;
        var total_interprise = document.getElementById('total_interprise');
        var pago_total = parseFloat(total_interprise.innerHTML);

    }
    formData.append('soporte', soporte);
    formData.append('erp', erp);
    switch (opcion) {
        case 1:
            var servicio = 1;
            break;
        case 2:
            var servicio = 3;
            break;
        case 3:
            var servicio = 12;
            break;
    }
    formData.append('periodo', servicio);
    formData.append('tipo', compra);
    formData.append('total', pago_total);
    formData.append('modalidad', "REMOTO");
    formData.append('MedioPago', $("#MedioPago").val());

    /* console.log(token); */
    $.ajax({
        url: '/DatosCompra',
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'post',
        datatype: 'json',
        //async: false,
        contentType: false,
        processData: false,
        data: formData,
        statusCode: {
            419: function () {
                location.reload();
            },
        },
        success: function (data) {
            var url_actual = $(location).attr('origin');
            location.href = url_actual + '/resumen-compra';

        },
        error: function (data) {
            $("#formCompro").modal("hide");
            $("#forumuRegistro").modal({ backdrop: "static", keyboard: false });
            if (data.responseJSON.errors.nombreRegistro) {
                $("#nombres-error").html(data.responseJSON.errors.nombreRegistro);
                $("#nombres-error").show();
            }
            if (data.responseJSON.errors.ApellidoRegistro) {
                $("#apellidos-error").html(data.responseJSON.errors.ApellidoRegistro);
                $("#apellidos-error").show();
            }
            if (data.responseJSON.errors.correoRegistro) {
                $("#email-error").html(data.responseJSON.errors.correoRegistro);
                $("#email-error").show();
            }
            if (data.responseJSON.errors.telRegistro) {
                $("#movil-error").html(data.responseJSON.errors.telRegistro);
                $("#movil-error").show();
            }
            if (data.responseJSON.errors.cantCola) {
                $("#colaboradores-error").html(data.responseJSON.errors.cantCola);
                $("#colaboradores-error").show();
            }
            if (data.responseJSON.errors.paises) {
                $("#pais-error").html(data.responseJSON.errors.paises);
                $("#pais-error").show();
            }
            if (data.responseJSON.errors.MedioPago) {
                $("#pago-error").html(data.responseJSON.errors.MedioPago);
                $("#pago-error").show();
            }
            if (data.status == 422) {
                console.clear();
            }
        }
    })
}


$("#MedioPago").change(function (e) {
    $(".errorSelCom").empty();
    if (!$.trim($(this).val())) {
        sent == true;
        $(".errorSelCom").text("Debe seleccionar al menos un método de facturación");
        $("button.confirCom").removeClass("enabButton");
    } else {
        $("button.confirCom").addClass("enabButton");
        sent = false;
    }
    var MedioPago = $(this).val();
    if (MedioPago == 2) {
        var pais = $("#paises").val();
        if (pais != "Peru") {
            $("#MedioPago").val("1");
        }
    }
});
