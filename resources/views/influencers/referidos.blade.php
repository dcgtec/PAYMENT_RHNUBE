@include('layouts.influencers.header')

<div class="container infoContenido pt-5 pl-md-4 pr-md-4 pb-5">
    <h1>Mis referidos</h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Juan</td>
                            <td>Pérez</td>
                            <td>juan.perez@example.com</td>
                        </tr>
                        <tr>
                            <td>Maria</td>
                            <td>Gomez</td>
                            <td>maria.gomez@example.com</td>
                        </tr>
                        <tr>
                            <td>Maria</td>
                            <td>Gomez</td>
                            <td>maria.gomez@example.com</td>
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
});
</script>


@include('layouts.influencers.footer')