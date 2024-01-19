AOS.init();
var url_actual = $(location).attr('origin');
var descripcion = $('#descripcion').val();
var total = $('#total_compra').val();
total = total.replace('.', '')
var clave_publica = $('#public_key').val();
Culqi.publicKey = clave_publica;
Culqi.init();
Culqi.settings({
    title: 'RH NUBE CORP',
    currency: 'USD',// Este parámetro es requerido para realizar pagos yape
    //currency: 'PEN', //Este parámetro es requerido para realizar pagos yape
    amount: total, // Este parámetro es requerido para realizar pagos yape
    //amount: "100", // Este parámetro es requerido para realizar pagos yape
    order: 'ord_live_0CjjdWhFpEAZlxlz' // Este parámetro es requerido para realizar pagos con pagoEfectivo, billeteras y Cuotéalo
});
var img = url_actual + "/automatizacionv1/img/logoPago.jpg"
Culqi.options({
    lang: "auto",
    installments: false, // Habilitar o deshabilitar el campo de cuotas
    paymentMethods: {
        tarjeta: true,
        yape: false,
        bancaMovil: false,
        agente: false,
        billetera: false,
        cuotealo: false,
    },
    style: {
        logo: img,
        bannerColor: '#3B89FF', // hexadecimal
        buttonBackground: '#33CC99', // hexadecimal
        menuColor: '#3B89FF', // hexadecimal
        linksColor: '', // hexadecimal
        buttonText: '', // texto que tomará el botón
        buttonTextColor: '#fff', // hexadecimal
        priceColor: '#33CC99' // hexadecimal
    }
});

$("#btn_pagar").click(function (e) {
    e.preventDefault();
    Culqi.open();
})

function culqi() {
    console.clear();
    Culqi.close();
    if (Culqi.token) { // ¡Objeto Token creado exitosamente!
        $('#ModalGif').modal({ backdrop: 'static', keyboard: false });
        //En esta linea de codigo debemos enviar el "Culqi.token.id"
        //hacia tu servidor con Ajax
        $.ajax({
            url: '/culqiStore',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'post',
            data: {
                token: Culqi.token.id,
                precio: total,
                email: Culqi.token.email,
                descripcion: descripcion
            },
            statusCode: {
                419: function () {
                    location.reload();
                },
            },
            success: function (data) {
                console.clear();
                Culqi.close();
                if (data.status) {
                    location.href = url_actual + '/CompraExitosa';
                } else {
                    setTimeout(function () { $('#ModalGif').modal('hide'); }, 100);
                    $('#TextoAlerta').text(data.error);
                    $('#ModalNotificaciones').modal();
                }
            },
            error: function (data) {
            }
        })
    } else if (Culqi.order) { // ¡Objeto Order creado exitosamente!
        const order = Culqi.order;
        console.log('Se ha creado el objeto Order: ', order);

    } else {
        // Mostramos JSON de objeto error en consola
        $('#TextoAlerta').text(Culqi.error.user_message);
        $('#ModalNotificaciones').modal();
        $.ajax({
            url: '/GuardarLogs',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'post',
            data: {
                error: Culqi.error.merchant_message,
            },
            statusCode: {
                419: function () {
                    location.reload();
                },
            },
            success: function (data) {


            },
            error: function (data) {
            }
        })
        /* console.log('Error : ', Culqi.error.user_message); */
    }
};
/*   $("#pagar").click(function() {
location.href = "pantalla6.html";
}); */
console.clear();
