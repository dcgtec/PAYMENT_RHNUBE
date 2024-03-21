function obtenerValorCupon() {
    // Obtener la URL actual
    var url = window.location.href;

    // Dividir la URL en partes usando '?' como separador
    var partesURL = url.split("?");

    // Comprobar si hay parámetros después del '?' en la URL
    if (partesURL.length > 1) {
        // Obtener la parte de los parámetros
        var parametros = partesURL[1];

        // Dividir los parámetros usando '&' como separador para obtener los pares clave=valor
        var paresParametros = parametros.split("&");

        // Iterar sobre los pares para encontrar el valor del parámetro 'cupon'
        for (var i = 0; i < paresParametros.length; i++) {
            var par = paresParametros[i].split("=");
            if (par[0] === "cupon") {
                // Devolver el valor del parámetro 'cupon'
                return par[1];
            }
        }
    }

    // Si no se encuentra el parámetro 'cupon' en la URL, devolver null
    return null;
}

$(document).ready(function () {
    var valorCupon = obtenerValorCupon();
    var descuento = 0;
    if (valorCupon !== null) {
        $("div#miModal").modal({ backdrop: "static", keyboard: false });
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/obtenerDetalleCupon",
            type: "POST",
            dataType: "json",
            data: {
                codigo: valorCupon,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                console.log(response);
                if (response.message.length) {
                    if (response.success) {
                        // Procesar los detalles del cupón recibidos en la respuesta
                        var detallesCupon = response.message;
                        console.log(detallesCupon);
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
                            } else {
                                $(this).hide();
                            }
                        });

                        // Aquí puedes realizar cualquier acción con los detalles del cupón, como mostrarlos en la página
                    } else {
                        console.error(
                            "Error al obtener los detalles del cupón:",
                            response.message
                        );
                        // Manejar el caso en que no se encuentre el cupón
                    }
                }

                // Ocultar el modal después de procesar los detalles del cupón
                $("div#miModal").modal("hide");
            },

            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                // Manejar errores de la solicitud AJAX
            },
        });
    } else {
        console.log("No se encontró el valor del cupón en la URL.");
    }

    var $valor = $("#valor");
    var $idPla = $("select#idPla");
    var $cupon = $("input#cupon");
    var $idBotonPeriodo = $(".periodo button");
    var $idCountry = $("select#idCountry");
    var $editar = $("img#editarCupon");
    var $eliminar = $("img#eliminarCupon");
    var $guardar = $("img#guardarCupon");

    $editar.click(function () {
        $guardar.removeClass("d-none");
        $eliminar.removeClass("d-none");
        $editar.addClass("d-none");
    });

    $guardar.click(function () {
        $("#miModal").modal({ backdrop: "static", keyboard: false });
    });

    $cupon.on("input", function () {
        $(this).val($(this).val().toUpperCase());
    });

    // Aplicar Select2 a los elementos select
    $("select#idCountry, select#idPla").select2();
    // Manejar el evento select2:open para establecer el foco después de abrir el cuadro de opciones
    $(document).on("select2:open", () => {
        document
            .querySelector(".select2-container--open .select2-search__field")
            .focus();
    });

    $valor.on("change", function () {
        var valor = parseInt($valor.val()) || 1;
        $valor.val(valor);
        changePer();
    });

    $idPla.change(function () {
        changePer();
    });

    $idBotonPeriodo.click(function () {
        $(".periodo button").removeClass("seleccionado");
        $(this).addClass("seleccionado");
        $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
        changePer();
    });

    $idCountry.change(function () {
        changePer();
    });

    function changePer() {
        var $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
        var valorPer = $idBotonPeriodoSeleccionado.val();
        var quantity = $valor.val();
        var country = $idCountry.val();
        $("input#idPla").val(valorPer);

        $.ajax({
            url: "../update_prices",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                quantity: quantity,
                plan_id: valorPer,
                country: country,
            },
            success: function (response) {
                updatePrices(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            },
            complete: function () {
                console.log("Actualización de precios completada");
            },
        });
    }

    function updatePrices(response) {
        $("td.tax").text("$ " + response.tax);
        $("td.subtotal").text("$ " + response.subtotal);
        $("td.priceUn").text("$ " + response.price);
        $("td.quanty").text($("#valor").val());
        $("td.total b").text("$ " + response.total);
    }

    $(".dismin, .addmin").on("click", function () {
        var increment = $(this).hasClass("addmin") ? 1 : -1;
        $valor.val(Math.max(1, parseInt($valor.val()) + increment));
        changePer();
    });
});
