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
                <table id="porRetirar" class="table table-striped table-bordered">
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


                        @foreach ($compras as $index => $comprasFiltrada)
                            @if ($comprasFiltrada['estado_transacion'] == 1)
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
                                    $status_trans = '<span class="badge badge-primary py-1 mr-2">Confirmado</span>';
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
                            @endif
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h1 class="mt-5">Retirados</h1>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="misRetiros" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nro de operacion</th>
                            <th>Monto</th>
                            <th>Destino</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($operaciones as $operacion)
                            <tr>
                                <td>
                                    @if (empty($operacion->numero_operacion))
                                        <span class="badge badge-warning py-1 mr-2">
                                            <i class="fas fa-clock" aria-hidden="true"></i>
                                            Procesando
                                        </span>
                                    @else
                                        {{ $operacion->numero_operacion }}
                                    @endif
                                </td>
                                <td>$ {{ $operacion->totalPagar }}</td>
                                <!-- Asegúrate de que 'totalPagar' es un campo existente -->
                                <td>
                                    @if (empty($operacion->cuentasBancarias))
                                        <span class="badge badge-warning py-1 mr-2">
                                            <i class="fas fa-clock" aria-hidden="true"></i>
                                            Procesando
                                        </span>
                                    @else
                                        Procesando
                                    @endif
                                </td> <!-- Asegúrate de que 'cuentasBancarias' es un campo existente -->
                                <td>{{ $operacion->fecha }}</td>
                                <td>{{ $operacion->hora }}</td>
                                <td>
                                    @if (empty($operacion->numero_operacion))
                                        <span id="cancelar-operacion" data-id="{{ $operacion->id_operacion }}"
                                            class="badge badge-danger py-1 mr-2">
                                            <i class="fas fa-trash mr-1" aria-hidden="true"></i>
                                            Cancelar
                                        </span>
                                    @else
                                        <span class="badge badge-secondary py-1 mr-2">
                                            Completado
                                        </span>
                                    @endif
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

    span#cancelar-operacion {
    cursor: pointer;
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

    div#misRetiros_paginate,
    div#misRetiros_info,
    div#misRetiros_length,
    div#porRetirar_paginate,
    div#porRetirar_info,
    div#porRetirar_length,
    div.dataTables_wrapper div.dataTables_length select,
    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_filter input {
        font-size: 12px;
    }
</style>
<script src="{{ asset('influencers/js/retiros.js') }}"></script>
@include('layouts.influencers.footer')
