$(document).ready(function () {
    $("#misRetiros,#porRetirar").DataTable({
        ordering: false,
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior",
            },
        },
    }); // Inicializa DataTables

    $("#cantReti").on("focus", function () {
        var currentValue = $(this).val(); // Obtén el valor actual

        // Si el valor está vacío, comienza con "$ "
        if (currentValue.trim() === "") {
            $(this).val("$ "); // Agrega el símbolo al comienzo
        } else if (!currentValue.startsWith("$")) {
            // Si el símbolo no está presente, agrégalo
            $(this).val("$ " + currentValue); // Añade el símbolo al principio
        }

        // Elimina el placeholder para facilitar la entrada
        $(this).attr("placeholder", "");
    });

    $("#cantReti").on("blur", function () {
        var currentValue = $(this).val().replace("$", "").trim(); // Elimina el símbolo y espacios

        if (currentValue !== "") {
            // Convierte a número y formatea a dos decimales
            var numericValue = parseFloat(currentValue).toFixed(2); // Asegura dos decimales
            $(this).val("$ " + numericValue); // Vuelve a añadir el símbolo
        } else {
            $(this).attr("placeholder", "Cant. a retirar"); // Restablece el placeholder si está vacío
        }
    });

    let total = 0;

    // Agregar evento change a los checkboxes
    $(".checkbox").change(function () {
        // Obtener el valor de la ganancia asociada al checkbox seleccionado
        const ganancia = parseFloat(
            $(this)
                .closest("tr")
                .find("td:eq(3)")
                .text()
                .replace("$", "")
                .trim()
        );

        // Si el checkbox está marcado, sumar la ganancia al total; si no, restarla
        if ($(this).is(":checked")) {
            total += ganancia;
        } else {
            total -= ganancia;
        }

        $("input#cantReti").val("$ " + total.toFixed(2));
        // Mostrar el total en la consola
        console.log("Total ganancia:", total.toFixed(2));
    });

    $("#formRetiro").submit(function (event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        // Obtener el valor del campo de entrada
        const monto = parseFloat($("#cantReti").val().replace("$", "").trim());

        // Verificar si el monto es mayor a 1.00
        if (monto <= -1) {
            Swal.fire({
                title: "¡Error!",
                text: "El monto a retirar debe ser mayor a $1.00.",
                icon: "error",
            });
        } else {
            let ids = [];
            $(".checkbox:checked").each(function () {
                ids.push($(this).val());
            });

            if (ids.length > 0) {
                $.ajax({
                    url: "/cambiarEstadoPorCobrar",
                    type: "GET",
                    data: {
                        idCompra: ids,
                    },
                    success: function (response) {
                        if (response.success) {
                            var ganancia = response.totalGanancia;
                            Swal.fire({
                                title: "Confirmación",
                                text:
                                    "¿Estás seguro de retirar el monto de " +
                                    ganancia +
                                    "?",
                                icon: "question",
                                showCancelButton: true,
                                confirmButtonText: "Sí",
                                cancelButtonText: "No",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "/retirarDinero",
                                        type: "GET", // Cambiado a POST
                                        data: {
                                            idCompra: ids,
                                        },
                                        success: function (data) {
                                            Swal.fire({
                                                title: "Éxito",
                                                text:
                                                    "El monto de " +
                                                    ganancia +
                                                    " ha sido retirado correctamente.",
                                                icon: "success",
                                            }).then(() => {
                                                location.reload(); // Recarga la página después de cerrar el SweetAlert
                                            });
                                        },
                                        error: function (xhr) {
                                            console.log(xhr.responseJSON.error);
                                            Swal.fire({
                                                title: "¡Error!",
                                                text:
                                                    xhr.responseJSON.error ||
                                                    "Ocurrió un error inesperado.",
                                                icon: "error",
                                            });
                                        },
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "¡Error!",
                                text: response.message,
                                icon: "error",
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: "¡Error!",
                            text: xhr.error,
                            icon: "error",
                        });
                    },
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
