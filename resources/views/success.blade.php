@include('layouts.header')
<?php
$valorNumerico = abs(crc32($paymentIntent->id));
$codigoGenerado = str_pad($valorNumerico, 10, '0', STR_PAD_LEFT);

?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="success-message">Pago exitoso</h2>
                    <i class="fas fa-check-circle icon-check mb-4"></i>
                    <p class="p-0 m-0">Hola <?php echo explode(' ', $paymentIntent->customer_details->name)[0]; ?>, su compra se ha realizado con éxito.</p>

                    <!-- Transaction Summary -->
                    <div class="transaction-summary">
                        <p><strong>Fecha:</strong>
                            {{ \Carbon\Carbon::parse($paymentIntent->created)->format('Y-m-d H:i:s') }}
                        </p>
                        <p><strong>Monto Total:</strong> {{ $paymentIntent->amount_total / 100 }}
                            {{ strtoupper($paymentIntent->currency) }}
                        </p>

                        <p><strong>Tu código de compra es:</strong> {{ $codigoGenerado }} </p>
                        <p>Haz click en el botón para continuar con el registro <br>de tu empresa en RHNUBE
                        </p>



                        <!-- Add more details as needed -->
                    </div>

                    {{-- <!-- Order Details -->
                   <div class="order-details">
                       <h4>Detalles del pedido</h4>
                       <p><strong>Email:</strong> john.doe@example.com</p>
                       <p><strong>Producto:</strong> {{ $response->additional_info->items[0]->title }}</p>
                    <p><strong>Cantidad:</strong> {{ $response->additional_info->items[0]->quantity }}</p>
                    <p><strong>Método de pago:</strong> Credit Card</p>
                    <!-- Add more details as needed -->
                </div> --}}

                    <!-- Add a button or link to go back to the home page or any other relevant page -->
                    <a href="https://www.rhnube.com.pe/start" id="enviarRe" class="btn btn-primary m-2">Ir a RHNUBE</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-message {
        text-align: center;
        margin-bottom: 20px;
        color: #08d7d4;
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
</style>

@include('layouts.footer')
