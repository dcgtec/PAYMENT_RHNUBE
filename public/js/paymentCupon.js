$("select#idCountry").select2();

$(document).ready(function () {
    changePer(descuento);
});

var $valor = $("#valor");
var $idPla = $("select#idPla");
var $cupon = $("input#cupon");
var $idBotonPeriodo = $(".periodo button");
var $idCountry = $("select#idCountry");

var $cupon = $("#cupon");

function obtenerPlan() {
    var $idBotonPlaneleccionado = $(".plan button.seleccionado");
    var valorPlan = $idBotonPlaneleccionado.val();

    return valorPlan;
}

$cupon.blur(function () {
    var cuponVal = $cupon.val();
    console.log(cuponVal);
    var obPlan = obtenerPlan();
    console.log(obPlan);
    if (cuponVal) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/obtenerCupon",
            type: "get",
            dataType: "json",
            data: {
                codigo: cuponVal,
                paquete: obPlan,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                if (response.message.length) {
                    if (response.success) {
                        // Procesar los detalles del cupón recibidos en la respuesta
                        var detallesCupon = response.message;
                        var botonSeleccionado = false;
                        descuento = detallesCupon[0]["descuento"];
                        codCupon = detallesCupon[0]["cupon"];
                        $("input#cupon").attr("descuento", descuento);
                        $("input#cupon").attr("codCupon", codCupon);

                        $('button[name="idPla"]').each(function () {
                            var valorBoton = $(this).text().trim();

                            // Ocultar los botones que no corresponden al periodo del cupón
                            if (
                                detallesCupon.some(function (detalle) {
                                    return detalle.periodo === valorBoton;
                                })
                            ) {
                                $(this).show();
                                if (!botonSeleccionado) {
                                    $(this).click(); // Simula un clic en el botón para seleccionarlo
                                    botonSeleccionado = true; // Marca que se ha seleccionado un botón
                                }
                            } else {
                                $(this).hide();
                            }
                        });

                        changePer(descuento);
                    } else {
                        Swal.fire({
                            title: "¡Cupón no válido!",
                            text: response.message,
                            icon: "error",
                        });
                        $('button[name="idPla"]').each(function () {
                            $(this).show();
                        });
                        descuento = 0;
                        changePer(descuento);
                    }
                } else {
                    $('button[name="idPla"]').each(function () {
                        $(this).show();
                        if (!botonSeleccionado) {
                            $(this).click(); // Simula un clic en el botón para seleccionarlo
                            botonSeleccionado = true; // Marca que se ha seleccionado un botón
                        }
                    });
                    descuento = 0;
                    changePer(descuento);
                    Swal.fire({
                        title: "¡Cupón no válido!",
                        text: response.message,
                        icon: "error",
                    });
                }
            },

            error: function (xhr, status, error) {
                $('button[name="idPla"]').each(function () {
                    $(this).show();
                });
                descuento = 0;
                changePer(descuento);

                Swal.fire({
                    title: "¡Cupón no válido!",
                    text: error,
                    icon: "error",
                });
            },
        });
    } else {
        $('button[name="idPla"]').each(function () {
            $(this).show();
        });
        descuento = 0;
        changePer(descuento);
    }
});

function changePer(descuento) {
    var $valor = $("#valor");
    var quantity = $valor.val();
    var $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
    var valorPer = $idBotonPeriodoSeleccionado.val();

    var $idBotonPlaneleccionado = $(".plan button.seleccionado");
    var valorPlan = $idBotonPlaneleccionado.val();
    var textPlan = $idBotonPlaneleccionado.text().trim().toUpperCase();

    var $idCountry = $("select#idCountry");
    var country = $idCountry.val();

    $("b.plan").text("RHNUBE " + textPlan);

    if (typeof descuento === "undefined") {
        descuento = 0;
    }
    $.ajax({
        type: "POST",
        url: "/update_prices",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            quantity: quantity,
            plan_id: valorPer,
            country: country,
        },
        success: function (response) {
            updatePrices(response, descuento);
        },
        error: function (xhr, status, error) {
            // Manejar errores si es necesario
            console.error(xhr.responseText);
        },
    });
}

function updatePrices(response, descuento) {
    var calcudes = (descuento * parseFloat(response.subtotal)) / 100;
    var totaldes = parseFloat(response.total) - calcudes;
    // Redondear los valores a dos decimales
    calcudes = calcudes.toFixed(2);
    totaldes = totaldes.toFixed(2);
    $("td.tax").text("$ " + parseFloat(response.tax).toFixed(2));
    $("td.subtotal").text("$ " + parseFloat(response.subtotal).toFixed(2));
    $("td.priceUn").text("$ " + parseFloat(response.price).toFixed(2));
    $("td.quanty").text($("#valor").val());
    $("td.desc").text("$ " + calcudes);
    $("td.total b").text("$ " + totaldes);
}
