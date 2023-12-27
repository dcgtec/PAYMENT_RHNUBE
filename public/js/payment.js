$(document).ready(function () {
    $("select#idCountry,select#idPla").select2();
    var $valor = $("#valor");
    var $idPla = $("select#idPla");
    var $idCountry = $("select#idCountry");

    $valor.on("change", function () {
        var valor = parseInt($valor.val()) || 1;
        $valor.val(valor < 5 ? 1 : valor);
        changePer();
    });

    $idPla.change(function () {
        changePer();
    });

    $idCountry.change(function () {
        changePer();
    });

    function changePer() {
        var valorPer = $idPla.val();
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
