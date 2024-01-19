AOS.init();

if (!$('select.selectpicker').val()) {
    $(".confirPai").attr("disabled", true);
} else {
    $(".confirPai").attr("disabled", false);
}
/**
 * Iniciar video
 */
const glightbox = GLightbox({
    selector: ".glightbox",
});

/*Retroceder*/
var url_actual = $(location).attr('origin');
$("button.previous").click(function () {
    location.href = url_actual + '/start';
});

/*Avanzar*/

$("button.next").click(function () {
    /* $('#elegPais').modal({ backdrop: 'static', keyboard: false }); */
    location.href = url_actual + '/tarifario-remoto';

});
$('select.selectpicker').on('change', function () {
    if (!this.value) {
        $(".confirPai").attr("disabled", true);
    } else {
        $(".confirPai").attr("disabled", false);
    }
});
$(".confirPai").click(function () {
    var pais = $('select.selectpicker').val();
    $.ajax({
        type: "POST",
        url: "/GuardarPais",
        data: { pais: pais },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        statusCode: {
            /*401: function () {
                location.reload();
            },*/
            419: function () {
                location.reload();
            },
        },
        success: function (data) {
            $('#elegPais').modal('toggle');
            location.href = url_actual + '/tarifario-remoto';
        },
        error: function (jqXHR, data, errorThrown) { },
    });
});