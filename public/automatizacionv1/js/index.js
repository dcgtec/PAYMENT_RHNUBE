AOS.init();
$(".priSlide").addClass("active");
$("input#presencial").change(function () {
    if (this.checked) {
        $("input#remoto")[0].checked = false;
        $("input#marcadores")[0].checked = false;
        $("input#distribuidores")[0].checked = false;

        $(".btn-primary.disabled").removeClass("disabled");

        $("label[for='remoto']").removeClass("checkBor");
        $("label[for='presencial']").addClass("checkBor");

        $("label[for='marcadores']").removeClass("checkBor");
        $("label[for='distribuidores']").removeClass("checkBor");

        /*  $("input#checkbox3")[0].checked = false; */
        $(".generaPres").fadeIn("200");

        $(".generaCont").hide();
        $(".generaRemo").hide();

        $(".generaMarca").hide();
        $(".generDistr").hide();

        $(".priSlide").removeClass("active");
        $(".secSlide").addClass("active");
        $(".terSlide").removeClass("active");
        $(".cuarSlide").removeClass("active");

        $(".carousel-item.priSlide").hide();

        $("html, body").animate(
            {
                scrollTop: $("#header").offset().top,
            },
            1000
        );

        stopSlide();
    } else {
        $("label[for='presencial']").removeClass("checkBor");
        $(".btn-primary").addClass("disabled");
        $(".generaCont").fadeIn("200");
        $(".generaPres").hide();
        $(".generaRemo").hide();

        $(".generaMarca").hide();
        $(".generDistr").hide();
    }
});

/*=============================================
FLOATING RANDOM
=============================================*/
$(document).ready(function () {
    var classes = ["float-animation-1", "float-animation-2", "float-animation-3"];

    $("img#float-animation").each(function () {
        $(this).addClass(classes.splice(~~(Math.random() * classes.length), 1)[0]);
    });
});

$("input#remoto").change(function () {
    if (this.checked) {
        $("input#presencial")[0].checked = false;
        $("input#marcadores")[0].checked = false;
        $("input#distribuidores")[0].checked = false;

        $(".btn-primary.disabled").removeClass("disabled");
        $("label[for='remoto']").addClass("checkBor");
        $("label[for='presencial']").removeClass("checkBor");

        $("label[for='marcadores']").removeClass("checkBor");
        $("label[for='distribuidores']").removeClass("checkBor");

        $(".generaRemo").fadeIn("200");
        $(".generaCont").hide();
        $(".generaPres").hide();

        $(".generaMarca").hide();
        $(".generDistr").hide();

        $(".priSlide").addClass("active");
        $(".secSlide").removeClass("active");
        $(".terSlide").removeClass("active");
        $(".cuarSlide").removeClass("active");

        $(".carousel-item.priSlide").show();

        $("html, body").animate(
            {
                scrollTop: $("#header").offset().top,
            },
            1000
        );

        stopSlide();
    } else {
        $("label[for='remoto']").removeClass("checkBor");
        $(".btn-primary").addClass("disabled");
        $(".generaCont").fadeIn("200");
        $(".generaRemo").hide();
        $(".generaPres").hide();

        $(".generaMarca").hide();
        $(".generDistr").hide();
    }
});

$("input#marcadores").change(function () {
    if (this.checked) {
        $("input#presencial")[0].checked = false;
        $("input#remoto")[0].checked = false;
        $("input#distribuidores")[0].checked = false;

        $(".btn-primary.disabled").removeClass("disabled");
        $("label[for='marcadores']").addClass("checkBor");
        $("label[for='presencial']").removeClass("checkBor");

        $("label[for='remoto']").removeClass("checkBor");
        $("label[for='distribuidores']").removeClass("checkBor");

        $(".generaMarca").fadeIn("200");
        $(".generaCont").hide();
        $(".generaPres").hide();

        $(".generDistr").hide();
        $(".generaRemo").hide();

        $(".priSlide").removeClass("active");
        $(".secSlide").removeClass("active");
        $(".terSlide").addClass("active");
        $(".cuarSlide").removeClass("active");

        $(".carousel-item.priSlide").hide();

        $("html, body").animate(
            {
                scrollTop: $("#header").offset().top,
            },
            1000
        );

        stopSlide();
    } else {
        $("label[for='marcadores']").removeClass("checkBor");

        $(".btn-primary").addClass("disabled");
        $(".generaCont").fadeIn("200");
        $(".generaRemo").hide();
        $(".generaPres").hide();

        $(".generaMarca").hide();
        $(".generDistr").hide();
    }
});

$("input#distribuidores").change(function () {
    if (this.checked) {
        $("input#presencial")[0].checked = false;
        $("input#remoto")[0].checked = false;
        $("input#marcadores")[0].checked = false;

        $(".btn-primary.disabled").removeClass("disabled");
        $("label[for='distribuidores']").addClass("checkBor");
        $("label[for='presencial']").removeClass("checkBor");

        $("label[for='remoto']").removeClass("checkBor");
        $("label[for='marcadores']").removeClass("checkBor");

        $(".generDistr").fadeIn("200");
        $(".generaCont").hide();
        $(".generaPres").hide();

        $(".generaMarca").hide();
        $(".generaRemo").hide();

        $(".priSlide").removeClass("active");
        $(".secSlide").removeClass("active");
        $(".terSlide").removeClass("active");
        $(".cuarSlide").addClass("active");

        $(".carousel-item.priSlide").hide();

        $("html, body").animate(
            {
                scrollTop: $("#header").offset().top,
            },
            1000
        );

        stopSlide();
    } else {
        $("label[for='distribuidores']").removeClass("checkBor");

        $(".btn-primary").addClass("disabled");
        $(".generaCont").fadeIn("200");
        $(".generaRemo").hide();
        $(".generaPres").hide();

        $(".generaMarca").hide();
        $(".generDistr").hide();
    }
});

$("form#validarModalidad").submit(function (evt) {
    evt.preventDefault();

    if ($("form#validarModalidad :checkbox:checked").length > 0) {
        if ($("input#presencial").is(":checked")) {
            window.open("https://api.whatsapp.com/send?phone=+51988681050&text=Un saludo, me interesa el MODO PRESENCIAL para administrar personal. Favor mayor información.");
        }

        if ($("input#marcadores").is(":checked")) {
            window.open("https://api.whatsapp.com/send?phone=+51988681050&text=Un saludo, me interesa relojes biométricos o checadores de control de asistencia de personal.");
        }

        if ($("input#distribuidores").is(":checked")) {
            window.open(
                "https://api.whatsapp.com/send?phone=+51988681050&text=Un saludo, soy un canal de distribución o reventa de tecnologías o sistemas de control de asistencia de personal y me interesa ser distribuidor o tener una alianza estratégica."
            );
        }

        if ($("input#remoto").is(":checked")) {
            var url_actual = $(location).attr("origin");
            location.href = url_actual + "/modo-remoto";
        }
    } else {
        $(".col-xs-11.col-sm-4.alert.alert-danger").remove();
        dangerClick();

        $("div#carouselExampleIndicators").carousel("play");
    }
});

var dangerClick = function () {
    $.notify(
        {
            // options
            title: "<strong>Seleccionar</strong>",
            message: "<br>Debe seleccionar una modalidad.",
            icon: "glyphicon glyphicon-remove-sign",
        },
        {
            // settings
            element: "body",
            position: null,
            type: "danger",
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "right",
            },
            offset: 20,
            spacing: 10,
            z_index: 1031,
            delay: 3300,
            timer: 1000,
            url_target: "_blank",
            mouse_over: null,
            animate: {
                enter: "animated flipInY",
                exit: "animated flipOutX",
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: "class",
        }
    );
};

if (window.matchMedia("(max-width:768px)").matches) {
    stopSlide();
    var el = $("div#carouselExampleIndicators").detach();
    $("div#inner-wrap div#content").append(el);
    $(".priSlide").removeClass("active");
}

function stopSlide() {
    $("div#carouselExampleIndicators").carousel("pause");
}
