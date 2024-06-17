<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f6f6f6;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #00aafa;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content p {
            font-size: 18px;
            color: #333333;
        }

        .coupon {
            margin: 20px 0;
            border: 2px dashed #00aafa;
            padding: 20px;
            background-color: #fafafa;
            text-align: center;
        }

        .coupon h2 {
            margin: 0;
            font-size: 22px;
            color: #00aafa;
        }

        .coupon-code {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
            color: #333333;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #00aafa;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
        }

        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777777;
        }

        .footer a {
            color: #00aafa;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>¡Gracias por registrarse!</h1>
        </div>
        <div class="content">
            <img style="margin-bottom: 0px;margin-top: 0px;height: 80px;"
                src="https://payment.rhnube.com.pe/images/iconoLogoRhNubeActual.png" alt="Logo de la Empresa"
                class="company-logo">
            <p>Hola <b>{{ $nombres }}</b>, nos encantaría que compartas esta oferta especial con tus amigos:</p>
            <div class="coupon">
                <h2>Cupon de Descuento para tus Referidos</h2>
                <div class="coupon-code">{{ $cupon }}</div>
                <p>Comparte este código con tus amigos para que obtengan un {{ $porcentaje }}% de descuento en sus
                    próximas compras.</p>
                <a href="{{ $link }}" class="button" style="color: #fff!important">Compartir Cupón</a>
            </div>
            <p>¡Gracias por recomendarnos!</p>
        </div>
        <div class="footer">
            <p>© 2020 RH nube Corp - USA | Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>
