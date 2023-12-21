@include('layouts.header')
{{ $paymentIntent }}

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="success-message">Pago exitoso</h2>
                    <i class="fas fa-check-circle icon-check mb-4"></i>
                    <p>Su pago ha sido procesado exitosamente. Gracias por su compra!</p>

                    <!-- Transaction Summary -->
                    <div class="transaction-summary">
                        <h4>Resumen de Transacciones</h4>
                        <p><strong>Número de pedido:</strong> # {{ $paymentIntent->id }} </p>
                        <p>
                            <strong>Fecha:</strong>
                            {{ \Carbon\Carbon::parse($paymentIntent->created)->format('Y-m-d H:i:s') }}
                        </p>

                        <p><strong>Monto Total:</strong> {{ $paymentIntent->amount_total / 100 }}
                            {{ strtoupper($paymentIntent->currency) }}
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
                    <a href="/" class="btn btn-primary">Continuar comprando</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-message {
        text-align: center;
        margin-bottom: 20px;
        color: #28a745;
    }

    .icon-check {
        color: #28a745;
        font-size: 5rem;
    }

    .transaction-summary {
        margin-top: 30px;
        text-align: center;
    }
</style>

@include('layouts.footer')
