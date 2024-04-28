@include('layouts.influencers.header')

<div class="container infoContenido pt-5 pl-md-4 pr-md-4 pb-5">
    <h1>Mis retiros</h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="tiltCard">Mi cartera</h4>
                </div>
            </div>

            <div class="row "> <!-- Espaciado adicional para claridad -->
                <div class="col-md-6 mt-3">
                    <form id="formRetiro">
                        <div class="input-group"> <!-- Grupo de entrada para alinear elementos -->
                            <input type="text" id="cantReti" class="form-control text-center"
                                placeholder="Cant. a retirar">
                            <!-- Campo de entrada -->
                            <button type="submit" class="ml-3 btn btn-primary">Programar a retirar</button>
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




    <h1 class="mt-5">Mis movimientos</h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nro. de operación</th>
                            <th>Monto</th>
                            <th>Destino</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>15229841</td>
                            <td>$ 15.00</td>
                            <td>BCP</td>
                            <td>20/03/24</td>
                            <td>06:24</td>
                            <td>Pendiente</td>
                        </tr>
                        <tr>
                            <td>15229841</td>
                            <td>$ 15.00</td>
                            <td>BCP</td>
                            <td>20/03/24</td>
                            <td>06:24</td>
                            <td>Pendiente</td>
                        </tr>
                        <tr>
                            <td>15229841</td>
                            <td>$ 15.00</td>
                            <td>BCP</td>
                            <td>20/03/24</td>
                            <td>06:24</td>
                            <td>Pendiente</td>
                        </tr>
                        <tr>
                            <td>15229841</td>
                            <td>$ 15.00</td>
                            <td>BCP</td>
                            <td>20/03/24</td>
                            <td>06:24</td>
                            <td>Pendiente</td>
                        </tr>
                        <!-- Agrega más filas si es necesario -->
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

    });
</script>


@include('layouts.influencers.footer')
