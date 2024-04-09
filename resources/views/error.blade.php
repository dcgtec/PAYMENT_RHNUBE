@include('layouts.header')

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="success-message">Error</h2>
                    <i class="fas fa-times-circle icon-check mb-4"></i>
                    <p class="p-0 m-0">Hola, se produjo un error en su proceso de compra.</p>

                    <!-- Transaction Summary -->
                    <div class="transaction-summary">
                        <p><strong>¡Por favor, vuelva a intentarlo!</strong>
                    </div>
                    <a href="/" id="enviarRe" class="btn btn-primary m-2">Continuar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .success-message {
        text-align: center;
        margin-bottom: 20px;
        color: red;
    }

    .icon-check {
        color: red;
        font-size: 5rem;
    }

    .transaction-summary {
        margin-top: 10px;
        text-align: center;
    }
</style>

@include('layouts.footer')
