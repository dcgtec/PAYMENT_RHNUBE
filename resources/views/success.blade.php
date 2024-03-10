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

                    </div>
                    <a data-toggle="modal" data-target="#exampleModal" id="abrirModal"
                        class="btn btn-secondary m-2">Copiar al correo <i class="ml-2 fa fa-envelope"></i> </a>
                    <a href="https://www.rhnube.com.pe/start" id="enviarRe" class="btn btn-primary m-2">Continuar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enviar a otro correo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" required
                            placeholder="Escriba un correo electrónico válido">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </form>
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
    a#conEsp,
    a#abrirModal {
        box-shadow: 0 4px 10px rgba(20, 20, 43, .04);
        border-radius: 0.5rem;
        padding: 0.75rem 1.75rem;
        font-size: 18px;
        border: none;
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

<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: '/reenviar-correo',
                type: 'POST',
                data: {
                    correoElectronico: $("input#email").val().trim(),
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: "¡Mensaje enviado!",
                            text: response.message,
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "¡Mensaje no enviado!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "¡Vuelva a intentarlo!",
                        text: 'Hubo un problema con nuestra conexión',
                        icon: "error"
                    });
                },
                complete: function() {
                    // Habilitar el botón nuevamente después de que se completa la solicitud, ya sea éxito o error
                    $('button[type="submit"]').prop('disabled', false);
                }
            });

        });
    });
</script>
