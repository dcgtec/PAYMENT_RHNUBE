@include('layouts.influencers.header')


@php
$datosUsuario = json_decode($responseData, true);
$compras = $datosUsuario['compras']['compras'];
@endphp

<div class="container infoContenido pt-5 pl-md-4 pr-md-4 pb-5">
    <h1>Mis referidos</h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Email</th>
                            <th>Ganancia</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($compras as $compra)
                        @php
                        $datosUsuario = json_decode($compra['dato_usuario'], true);
                        $nombre = $datosUsuario['customerName'];
                        $email = $compra['correo'];
                        $ganancia = $datosUsuario['ganancia'];
                        $fecha = \Carbon\Carbon::parse($compra['fecha_compra']);
                        $stado = $compra['estado_transacion'];

                        $fecha_mas_30 = $fecha->copy()->addDays(30); // 30 días después de la fecha de compra
                        $today = \Carbon\Carbon::now(); // Fecha actual
                        $dias_restantes = $today->diffInDays($fecha_mas_30, false); // Diferencia de días
                        @endphp

                        @php
                        // Determinar el mensaje del estado basándose en el campo estado_transacion y días restantes
                        if ($stado == 0) {
                        $status_trans =
                        '<span class="badge badge-warning py-1 mr-2"><i class="fa fa-clock-o" aria-hidden="true"></i>
                            Por confirmar, dentro de ' .
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
                            <td>{{ $nombre }}</td>
                            <td>{{ $email }}</td>
                            <td>$ {{ $ganancia }}</td>
                            <td>{{ $fecha }}</td>
                            <td>{!! $status_trans !!}</td>
                        </tr>
                        @endforeach
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



div#misRetiros_paginate,
div#misRetiros_info,
div#misRetiros_length,
div#porRetirar_paginate,
div#porRetirar_info,
div#porRetirar_length,
div.dataTables_wrapper div.dataTables_length select,
div.dataTables_wrapper div.dataTables_filter label,
div.dataTables_wrapper div.dataTables_filter input {
    font-size: 12px !important;
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
});
</script>


@include('layouts.influencers.footer')