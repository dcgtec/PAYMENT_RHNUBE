$(document).ready(function () {
    // Aplicar Select2 a los elementos select
    $("select#idCountry, select#idPla").select2();
    // Manejar el evento select2:open para establecer el foco después de abrir el cuadro de opciones
    $(document).on("select2:open", () => {
        document
            .querySelector(".select2-container--open .select2-search__field")
            .focus();
    });

    var $valor = $("#valor");
    var $idPla = $("select#idPla");
    var $idBotonPeriodo = $(".periodo button");
    var $idBotonPeriodoSeleccionado = $(".periodo button.seleccionado");
    var $idCountry = $("select#idCountry");

    $valor.on("change", function () {
        var valor = parseInt($valor.val()) || 1;

        // if (valor > 100) {
        //     // Mostrar alerta cuando el valor es mayor a 100
        //     $("#modalDemo").modal('show');
        //     valor = 100; // Limitar el valor a 100
        // }

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
