@include('layouts.header')

{{--
@if ($message) --}}
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="success-message">Hubo un error</h2>
                    <i class="fas fa-window-close icon-check mb-4"></i>

                    <!-- Transaction Summary -->
                    <div class="transaction-summary">
                        <h4>Hubo un problema con su pedido</h4>
                        <p><strong>Por favor, vuelva a intentarlo!!!</strong></p>

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
                    <a href="https://www.rhnube.com/precios" class="btn btn-primary previus">Volver a suscribirse</a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @else
    <script>
        window.location = "https://www.rhnube.com/precios";
    </script> --}}
{{-- @endif --}}



<style>
    .success-message {
        text-align: center;
        margin-bottom: 20px;
        color: #fb4d4d;
    }

    .icon-check {
        color: #fb4d4d;
        font-size: 5rem;
    }

    .transaction-summary {
        text-align: center;
    }

    .previus {
        background-color: #08d7d4;
        border-radius: 28px;
        border: none;
        padding: 18px 28px;
        font-family: Poppins, sans-serif;
        font-weight: 600;
    }
</style>

@include('layouts.footer')
