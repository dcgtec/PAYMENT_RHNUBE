$("select#idCountry,select#idPaquete").select2();

$(document).ready(function () {
    var $valor = $("#valor");
    var $idPla = $("select#idPla");
    var $cupon = $("input#cupon");
    var $idBotonPeriodo = $(".periodo button");
    var $idCountry = $("select#idCountry");
    var $idBotonPeriodoSelec = $(".periodo button.seleccionado");
    var $idPaquete = $("select#idPaquete");
    var $cupon = $("#cupon");

    var descuento = $("input#cupon").attr("descuento");

    changePer(descuento);

    $(".dismin, .addmin").on("click", function () {
        var increment = $(this).hasClass("addmin") ? 1 : -1;
        $valor.val(Math.max(1, parseInt($valor.val()) + increment));
        changePer(descuento);
    });

    $idPaquete.change(function () {
        changePer(descuento);
    });

    $idPla.change(function () {
        changePer(descuento);
    });

    $idCountry.change(function () {
        changePer(descuento);
    });

    $(document).on("click", ".periodo button", function () {
        $(".periodo button").removeClass("seleccionado");
        $(this).addClass("seleccionado");
        $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
        $valorSeleccion = $(this).val();
        changePer(descuento);
    });

    var cuponAnti = $cupon.val();
    $cupon.blur(function () {
        var cuponVal = $cupon.val();
        var obPlan = obtenerPlan();
        if (cuponVal && cuponAnti != cuponVal) {
            cuponAnti = cuponVal;
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                url: "/obtenerCupon",
                type: "get",
                dataType: "json",
                data: {
                    codigo: cuponVal,
                    paquete: "0",
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
                            var periodos = response.message["0"].periodos;
                            var paquetes = response.message["0"].paquetes;
                            console.log(response.message["0"]);
                            descuento = detallesCupon[0]["descuento"];
                            codCupon = detallesCupon[0]["cupon"];
                            $("input#cupon").attr("descuento", descuento);
                            $("input#cupon").attr("codCupon", codCupon);

                            $(".lisPer").children().remove();
                            $("#idPaquete").children().remove();

                            $(periodos).each(function (index, periodo) {
                                var contenido =
                                    '<div class="col-md-6 my-2">' +
                                    '<button type="button" value="' +
                                    periodo.id_tipo_periodo +
                                    '" name="idPla" class="w-100 btn btn-primary">' +
                                    periodo.periodo +
                                    "</button>" +
                                    "</div>";
                                $(".lisPer").append(contenido);
                            });

                            $(paquetes).each(function (index, paquete) {
                                console.log(paquete.id_paquete);
                                console.log(paquete.paquete);
                                var contenido =
                                    '<option value="' +
                                    paquete.id_paquete +
                                    '">' +
                                    paquete.paquete +
                                    "</option>";
                                $("#idPaquete").append(contenido);
                            });

                            var primerBoton = $(".lisPer button:first");
                            primerBoton.addClass("seleccionado");

                            changePer(descuento);
                        } else {
                            limpiarError(response.message);
                            descuento = 0;
                            seleccionFunction(descuento);
                        }
                    } else {
                        limpiarError(response.message);
                        descuento = 0;
                        seleccionFunction(descuento);
                    }
                },

                error: function (xhr, status, error) {
                    limpiarError(error);
                    descuento = 0;
                    seleccionFunction(descuento);
                },
            });
        }
    });
});

function seleccionFunction(descuento) {
    $(".periodo button").click(function () {
        console.log("hola");
        $(".periodo button").removeClass("seleccionado");
        $(this).addClass("seleccionado");
        $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
        $valorSeleccion = $(this).val();
        changePer(descuento);
    });
}

function limpiarError(message) {
    $(".lisPer").children().remove();
    $("#idPaquete").children().remove();

    var contPer =
        '<div class="col-md-6 my-2">' +
        '<button type="button" value="1" name="idPla" class="w-100 btn btn-primary">Mensual</button>' +
        "</div>" +
        '<div class="col-md-6 my-2">' +
        '<button type="button" value="2" name="idPla" class="w-100 btn btn-primary">Trimestral</button>' +
        "</div>" +
        '<div class="col-md-6 my-2">' +
        '<button type="button" value="3" name="idPla" class="w-100 btn btn-primary">Semestral</button>' +
        "</div>" +
        '<div class="col-md-6 my-2">' +
        '<button type="button" value="4" name="idPla" class="w-100 btn btn-primary">Anual</button>' +
        "</div>";

    var contPaq =
        '<option value="1">Plus</option>' +
        '<option value="2">Remote</option>' +
        '<option value="3">Route</option>';

    $(".lisPer").append(contPer);
    $("#idPaquete").append(contPaq);

    var primerBoton = $(".lisPer button:first");
    primerBoton.addClass("seleccionado");

    descuento = 0;
    changePer(descuento);

    Swal.fire({
        title: "¡Cupón no válido!",
        text: message,
        icon: "error",
    });
}

function obtenerPlan() {
    var $idBotonPlaneleccionado = $(".paquete select");
    var valorPlan = $idBotonPlaneleccionado.val();

    return valorPlan;
}

function obtenerPlanId($idPaquete, $idBotonPeriodoSelec, successCallback) {
    var plan = $idPaquete.val();
    var periodo = $idBotonPeriodoSelec.val();

    $.ajax({
        type: "GET",
        url: "/obtenerPlan",
        data: {
            plan: plan,
            periodo: periodo,
        },
        success: function (response) {
            $("input#idPla").val(response);
            if (successCallback) {
                successCallback();
            }
        },
        error: function (xhr, status, error) {
            // Manejar errores si es necesario
            console.error(xhr.responseText);
        },
    });
}

function changePer(descuento) {
    var $valor = $("#valor");
    var quantity = $valor.val();
    var $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
    var valorPer = $("input#idPla").val();

    var $idCountry = $("select#idCountry");
    var country = $idCountry.val();

    var $idPaquete = $("select#idPaquete");

    obtenerPlanId($idPaquete, $idBotonPeriodoSeleccionado, function () {
        var valorPer = $("input#idPla").val();
        var $idBotonPlaneleccionado = $(".plan button.seleccionado");
        var valorPlan = $idBotonPlaneleccionado.val();
        var textPlan = $idPaquete.find(":selected").text().trim().toUpperCase();
        $("b.plan").text("RHNUBE " + textPlan);

        if (typeof descuento === "undefined") {
            descuento = 0;
        }
        $.ajax({
            type: "get",
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
