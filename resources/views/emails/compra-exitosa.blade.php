<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Compra</title>
    <style>
        .success-message {
            text-align: center;
            margin-bottom: 20px;
            color: #08d7d4;
            font-size: 32px;
        }

        .icon-check {
            color: #08d7d4;
            font-size: 5rem;
        }

        .transaction-summary {
            margin-top: 10px;
            text-align: center;
        }

        a#enviarRe,
        a#conEsp {
            box-shadow: 0 4px 10px rgba(20, 20, 43, .04);
            border-radius: 12px;
            padding: 20px 40px;
            font-size: 18px;
        }

        a#enviarRe {
            background: #00aafa;
            border: none;
        }

        a#conEsp {
            background: #08d7d4;
            border: none;
        }

        p {
            text-align: center;
            font-size: 16px;
        }

        div {
            text-align: center;
        }

        img {
            text-align: center;
            width: 80px;
            height: 80px;
            margin: 0 auto !important;
        }

        a {
            background: #00aafa;
            border: none;
            box-shadow: 0 4px 10px rgba(20, 20, 43, .04);
            border-radius: 12px;
            padding: 20px 40px;
            font-size: 18px;
            color: #fff !important;
            text-decoration: auto;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="success-message">Pago exitoso</h2>
                        <img src="https://uxwing.com/wp-content/themes/uxwing/download/checkmark-cross/success-green-check-mark-icon.png"
                            alt="Paso 4" />
                        <i class="fas fa-check-circle icon-check mb-4"></i>
                        <p class="p-0 m-0 text-center">Hola {{ $customerName }}, su compra se ha realizado con éxito.
                        </p>

                        <!-- Transaction Summary -->
                        <div class="transaction-summary">
                            <p><strong>Fecha:</strong>
                                {{ $fecha }} <br>
                                <br>
                                <strong>Monto Total:</strong> {{ $monto }}
                                USD
                                <br>
                                <br>
                                <strong>Tu código de compra es:</strong> {{ $codigoGenerado }}
                                <br>
                                <br>
                                Haz click en el botón para continuar con el registro de tu empresa en RHNUBE
                                <br>
                                <br>
                                <br>
                                <a href="https://www.rhnube.com.pe/start" id="enviarRe" class="btn btn-primary m-2">Ir
                                    a
                                    RHNUBE</a>
                                <br>
                                <br>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
