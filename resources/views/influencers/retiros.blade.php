@include('layouts.influencers.header')

@php

    $usuario = session()->get('detalleUusario');
    $banco = '------';
    if ($usuario['banco']) {
        $banco = $usuario['banco'];
    }
@endphp
<div class="container infoContenido pt-5 pl-md-4 pr-md-4 pb-5">

    <div class="card mt-4">
        <div class="card-body">
            {{-- <div class="row">
                <div class="col-md-12">
                    <h4 class="tiltCard">Mi cartera</h4>
                </div>
            </div> --}}

            <div class="row "> <!-- Espaciado adicional para claridad -->
                <div class="col-md-6 mt-3">
                    <form id="formRetiro">
                        <div class="input-group"> <!-- Grupo de entrada para alinear elementos -->
                            <input type="text" id="cantReti" class="form-control text-center"
                                placeholder="Cant. a retirar" disabled value="$ 0.00">
                            <!-- Campo de entrada -->
                            <button type="submit" class="ml-3 btn btn-primary">Retirar</button>
                            <!-- Botón Submit -->
                        </div>
                    </form>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group"> <!-- Clase para agrupar inputs con sus etiquetas -->
                        <label for="input1" class="tiltCard">Medios de retiro:</label>
                        <!-- Etiqueta para el primer input -->
                        <img class="mt-sm-0 mt-2" src="{{ asset('influencers/images/metodoPago.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h1 class="mt-5">Por retirar</h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Ganancia</th>
                            <th>Estado</th>
                            <th>Retirar</th>
                        </tr>
                    </thead>
                    <tbody>


                        @foreach ($comprasFiltradas as $index => $comprasFiltrada)
                            @php
                                // Decodificar el JSON almacenado en 'dato_usuario'
                                $dato_usuario = json_decode($comprasFiltrada['dato_usuario'], true);
                                // Obtener la ganancia del JSON decodificado
                                $ganancia = $dato_usuario['ganancia'] ?? 'No disponible';
                                // Obtener la fecha y hora de compra
                                $fecha_compra = $comprasFiltrada['fecha_compra'];
                                // Separar la fecha y la hora
                                $fecha = date('Y-m-d', strtotime($fecha_compra)); // Formato YYYY-MM-DD
                                $hora = date('H:i:s', strtotime($fecha_compra)); // Formato HH:MM:SS
                                $stado = $comprasFiltrada['estado_transacion'];

                                if ($stado == 0) {
                                    $status_trans =
                                        '<span class="badge badge-warning py-1 mr-2"><i class="fa fa-clock-o" aria-hidden="true"></i> Por confirmar, dentro de ' .
                                        $dias_restantes .
                                        ' días</span>';
                                } elseif ($stado == 1) {
                                    $status_trans = '<span class="badge badge-primary py-1 mr-2">Confirmado</span>';
                                } elseif ($stado == 2) {
                                    $status_trans = '<span class="badge badge-info py-1 mr-2">Por cobrar</span>';
                                } elseif ($stado == 3) {
                                    $status_trans = '<span class="badge badge-danger py-1 mr-2">Rechazado</span>';
                                } elseif ($stado == 4) {
                                    $status_trans = '<span class="badge badge-success py-1 mr-2">Pagado</span>';
                                } else {
                                    $status_trans = '<span class="badge badge-secondary py-1 mr-2">Desconocido</span>';
                                }
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td> <!-- El índice comienza en 0, así que sumamos 1 -->
                                <td>{{ $fecha }}</td>
                                <td>{{ $hora }}</td>
                                <td>$ {{ $ganancia }}</td>
                                <td>{!! $status_trans !!}</td>
                                <td>
                                    <input type="checkbox" id="checkbox{{ $index }}" class="checkbox"
                                        name="seleccionar[]" value="{{ $comprasFiltrada['codigo_compra'] }}">
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
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

    .tiltCard {
        margin: 0;
        color: #AAB4C3;
        font-size: 14px;
    }

    input#cantReti::placeholder {
        font-size: 12px;
        color: #AAB4C3;
    }

    button.btn.btn-primary {
        background: #08D7D4;
        border: none;
        border-radius: 10px;
        padding-top: 0;
        padding-bottom: 0;
        height: 40px;
        color: #fff;
        font-size: 16px;
        width: 180px;
    }

    input#cantReti {
        height: 40px;
        border-radius: 10px;
        font-size: 20px;
        padding-top: 0;
        font-weight: bold;
        padding-bottom: 0;
        color: #464B50;
    }

    .card.mt-4 {
        border: none;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        -webkit-box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.1);
    }

    table.dataTable thead>tr>th.sorting,
    table.dataTable thead>tr>th.sorting_asc,
    table.dataTable thead>tr>th.sorting_desc,
    table.dataTable thead>tr>th.sorting_asc_disabled,
    table.dataTable thead>tr>th.sorting_desc_disabled,
    table.dataTable thead>tr>td.sorting,
    table.dataTable thead>tr>td.sorting_asc,
    table.dataTable thead>tr>td.sorting_desc,
    table.dataTable thead>tr>td.sorting_asc_disabled,
    table.dataTable thead>tr>td.sorting_desc_disabled {
        border: none;
        background: #fff;
    }

    table.dataTable {
        font-size: 12px;
        border: none;
        text-align: center;
    }

    .table td,
    .table th {
        border: none;
    }

    table.dataTable.table-striped>tbody>tr.odd>* {
        box-shadow: unset !important;
    }

    table.dataTable.table-striped>tbody>tr:nth-of-type(2n+1) {
        background-color: #F5F5F5;
    }

    table.dataTable thead th,
    table.dataTable thead td,
    table.dataTable tfoot th,
    table.dataTable tfoot td {
        border: none;
        text-align: center;
    }

    div#myTable_paginate,
    div#myTable_info,
    div#myTable_length,
    div.dataTables_wrapper div.dataTables_length select,
    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_filter input {
        font-size: 12px;
    }
</style>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "ordering": false,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        }); // Inicializa DataTables

        $('#cantReti').on('focus', function() {
            var currentValue = $(this).val(); // Obtén el valor actual

            // Si el valor está vacío, comienza con "$ "
            if (currentValue.trim() === '') {
                $(this).val('$ '); // Agrega el símbolo al comienzo
            } else if (!currentValue.startsWith('$')) {
                // Si el símbolo no está presente, agrégalo
                $(this).val('$ ' + currentValue); // Añade el símbolo al principio
            }

            // Elimina el placeholder para facilitar la entrada
            $(this).attr('placeholder', '');
        });

        $('#cantReti').on('blur', function() {
            var currentValue = $(this).val().replace('$', '').trim(); // Elimina el símbolo y espacios

            if (currentValue !== '') {
                // Convierte a número y formatea a dos decimales
                var numericValue = parseFloat(currentValue).toFixed(2); // Asegura dos decimales
                $(this).val('$ ' + numericValue); // Vuelve a añadir el símbolo
            } else {
                $(this).attr('placeholder',
                    'Cant. a retirar'); // Restablece el placeholder si está vacío
            }
        });

        let total = 0;

        // Agregar evento change a los checkboxes
        $('.checkbox').change(function() {
            // Obtener el valor de la ganancia asociada al checkbox seleccionado
            const ganancia = parseFloat($(this).closest('tr').find('td:eq(3)').text().replace('$', '')
                .trim());

            // Si el checkbox está marcado, sumar la ganancia al total; si no, restarla
            if ($(this).is(':checked')) {
                total += ganancia;
            } else {
                total -= ganancia;
            }

            $("input#cantReti").val("$ " + total.toFixed(2));
            // Mostrar el total en la consola
            console.log('Total ganancia:', total.toFixed(2));
        });

        $('#formRetiro').submit(function(event) {
            event.preventDefault(); // Evita el envío del formulario por defecto

            // Obtener el valor del campo de entrada
            const monto = parseFloat($('#cantReti').val().replace('$', '').trim());

            // Verificar si el monto es mayor a 1.00
            if (monto <= 1.00) {
                Swal.fire({
                    title: "¡Error!",
                    text: "El monto a retirar debe ser mayor a $1.00.",
                    icon: "error",
                });
            } else {
                let ids = [];
                $('.checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    $.ajax({
                        url: '/cambiarEstadoPorCobrar',
                        type: 'GET',
                        data: {
                            idCompra: ids,
                        },
                        success: function(response) {
                            var ganancia = response.totalGanancia;

                            Swal.fire({
                                title: "Confirmación",
                                text: "¿Estás seguro de retirar el monto de $" +
                                    ganancia + "?",
                                icon: "question",
                                showCancelButton: true,
                                confirmButtonText: "Sí",
                                cancelButtonText: "No",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '/retirarDinero',
                                        type: 'GET', // Cambiado a POST
                                        data: {
                                            idCompra: ids,
                                        },
                                        success: function(data) {
                                            console.log(data);
                                            Swal.fire({
                                                title: "Éxito",
                                                text: "El monto de $" +
                                                    data
                                                    .totalGanancia +
                                                    " ha sido retirado correctamente.",
                                                icon: "success",
                                            });
                                        },
                                        error: function(xhr) {
                                            console.log(xhr.responseJSON
                                                .error);
                                            Swal.fire({
                                                title: "¡Error!",
                                                text: xhr
                                                    .responseJSON
                                                    .error ||
                                                    'Ocurrió un error inesperado.',
                                                icon: "error",
                                            });
                                        }
                                    });

                                }
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "¡Error!",
                                text: xhr.error,
                                icon: "error",
                            });
                            console.error(xhr.responseText.error);
                            // Aquí puedes manejar el error
                        }
                    });
                } else {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Debes seleccionar al menos una compra.",
                        icon: "error",
                    });
                }
            }
        });
    });
</script>


@include('layouts.influencers.footer')
