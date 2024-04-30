@include('layouts.influencers.header')

@php
$jsonData = $cupon->getData();
$cuponCode = $jsonData->cupon; // Obtiene el valor de la propiedad "cupon"
@endphp

<div class="container infoContenido pt-5 pl-md-4 pr-md-4 pb-5">

    <div class="card mt-4">
        <div class="card-body">
            <div class="row mostrarQR" bis_skin_checked="1">
                <div class="col-md-12 text-center mb-3" bis_skin_checked="1">
                    <h1 class="mb-4">Mi cupón</h1>
                    <img data-cupon="{{$cuponCode}}" src="https://api.qrserver.com/v1/create-qr-code/?data=https://payment.rhnube.com.pe/payment?cupon={{$cuponCode}}&amp;size=150x150" alt="QR Code"><br><button class="mt-3 btn btn-primary  mx-1 btn-download" data-url="https://payment.rhnube.com.pe/payment?cupon=PRACTICAR"><i class="fa fa-download "></i></button><button class="mt-3  mx-1 btn btn-secondary btn-copy" data-url="https://payment.rhnube.com.pe/payment?cupon=PRACTICAR"><i class="fa fa-copy"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .infoContenido h1 {
        color: #464B50;
        font-weight: bold;
        font-size: 25px;
    }
</style>

<script>
    $(".btn-download").click(function() {
        var url = $(this).data("cupon");
        var img = new Image();
        img.crossOrigin = "Anonymous";
        img.onload = function() {
            var canvas = document.createElement("canvas");
            var ctx = canvas.getContext("2d");
            canvas.width = 300;
            canvas.height = 300;
            ctx.drawImage(img, 0, 0, 300, 300); // Escala la imagen al tamaño del lienzo (300x300)
            var dataURL = canvas.toDataURL("image/png");
            var link = document.createElement("a");
            link.href = dataURL;
            link.download = "qr_code.png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };
        img.src =
            "https://api.qrserver.com/v1/create-qr-code/?data=" +
            url +
            "&size=300x300"; // Ajusta el tamaño del código QR a 300x300
    });
</script>
@include('layouts.influencers.footer')