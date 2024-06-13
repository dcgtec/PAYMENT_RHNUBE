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
            color: #333333;
            margin: 10px 0;
        }

        .content a {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }

        .coupon {
            border: 2px dashed #00aafa;
            padding: 20px;
            background-color: #fafafa;
            text-align: center;
            margin-bottom: 20px;
        }

        .coupon h2 {
            margin: 0;
            font-size: 22px;
            color: #00aafa;
            margin-bottom: 10px;
        }

        .coupon-code {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            color: #333333;
            letter-spacing: 2px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00aafa;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0077cc;
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
            <h1>¡Solicitud de cambio de {{ $title }}!</h1>
        </div>
        <div class="content">
            <img style="margin-bottom: 0px;margin-top: 0px;height: 80px;"
                src="https://payment.rhnube.com.pe/images/iconoLogoRhNubeActual.png"
                alt="Logo de la Empresa" class="company-logo">
            <p>Hola, has solicitado cambiar tu {{ $title }}. <br>Para completar el proceso, haz clic en el
                siguiente enlace:</p>
            <p style="margin-bottom: 30px;margin-top: 30px;">
                <a href="{{ url('https://payment.rhnube.com.pe/' . $status . '?ce=' . $token) }}"
                    target="_blank">Cambiar
                    {{ $title }}</a>
            </p>
            <p>Este enlace expirará en una hora.</p>
            <p>Tu código de validación es: <strong>{{ $codigoValidacion }}</strong></p>

        </div>
        <div class="footer">
            <p>Si no solicitaste este cambio, por favor ignora este correo.</p>
            <p>© 2020 RH nube Corp - USA | Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>
