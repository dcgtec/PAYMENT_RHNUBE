AOS.init();

var table_colaboradores;
var paginaGlobal = 10;
var usuarios_registrados = [];
var posicion_usuario = null;
var n_empleados = $('#n_empleados').val();
$(".previous").click(function () {
    location.href = "pantalla7.html";
});
$("#formUusers").submit(function (evt) {
    evt.preventDefault();
    $("#error_nombre_e").hide();
    $("#error_correo_e").hide();
    $('#error_dni_e').hide();

    //agregar usuarios independientes
    var dni = $('#dni').val();
    var nombre = $('#nombre').val();
    var correo = $('#correo').val();
    var movil = $('#movil').val();
    if (dni.length < 8) {
        $("#error_ndocu_e").text("Su identificador debe tener al menos 8 dígitos").show("200");
        return false;
    }
    $("#error_ndocu_e").hide();
    /*  const pattern = /^[a-zA-Z ]+$/;
     var resultado = pattern.test(nombre);
     if (!resultado) {
         $("#error_nombre_e").show();
         return false;
     } */
    if (movil) {

        if (movil.length > 9) {
            $("#error_movil").text("Número móvil no debe superar los 9 caracteres").show("200");
            return false;
        }
        if (movil.length < 9) {
            $("#error_movil").text("Número móvil tiene como mínimo 9 caracteres").show("200");
            return false;
        }
    }
    $("#error_movil").hide();
    $("#error_nombre_e").hide();
    $dividir_nombres = nombre.split(" ");
    if ($dividir_nombres.length >= 3) {
        var aMaterno = $dividir_nombres.pop();
        var aPaterno = $dividir_nombres.pop();
        nombre = $dividir_nombres.join(' ');
    } else {
        var aMaterno = "";
        var aPaterno = "";
    }

    if (posicion_usuario == null) {
        //validamos numero de dni
        if (usuarios_registrados.length < n_empleados) {
            buscardni_usuario = usuarios_registrados.filter((item) => {
                return item.numero_documento == dni;
            });
            if (buscardni_usuario.length > 0) {
                $('#error_dni_e').show();
                return false;
            }
            $('#error_dni_e').hide();

            buscaremail_usuario = usuarios_registrados.filter((item) => {
                return item.correo == correo;
            });
            if (buscaremail_usuario.length > 0) {
                $("#error_correo_e").show();
                return false;
            }
            $("#error_correo_e").hide();
            if (movil) {
                movil = "+51" + movil;
            }
            $new_array = {
                numero_documento: dni,
                nombres: nombre,
                aPaterno: aPaterno,
                aMaterno: aMaterno,
                correo: correo,
                numero_celular: movil
            };
            usuarios_registrados.push($new_array);
            document.getElementById('formUusers').reset();
            crearTabla();
        } else {
            var mensaje = "Excedió el límite de colaboradores que puede registrar en la suscripción actual. Hasta " + n_empleados + " colaboradores contratados.";
            $.notifyClose();
            $.notify({
                message: mensaje,
                icon: "admin/images/warning.svg",
            }, {
                position: "fixed",
                placement: {
                    from: "top",
                    align: "center",
                },
                icon_type: "image",
                allow_dismiss: true,
                newest_on_top: true,
                delay: 10000,
                template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                    "</div>",
                spacing: 35,
            });
        }
    } else {
        usuario = usuarios_registrados[posicion_usuario];
        //comporbar si no se esta repitiendo un dni
        editar_usuarios = usuarios_registrados.filter((item, index) => {
            return posicion_usuario != index;
        });
        buscardni_usuario = editar_usuarios.filter((item) => {
            return item.numero_documento == dni;
        });
        if (buscardni_usuario.length > 0) {
            $.notifyClose();
            $.notify({
                message: "\nNúmero de documento duplicado.",
                icon: "admin/images/warning.svg",
            }, {
                position: "fixed",
                placement: {
                    from: "top",
                    align: "center",
                },
                icon_type: "image",
                allow_dismiss: true,
                newest_on_top: true,
                delay: 10000,
                template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                    "</div>",
                spacing: 35,
            });

            return false;
        }
        buscaremail_usuario = editar_usuarios.filter((item) => {
            return item.correo == correo;
        });


        if (buscaremail_usuario.length > 0) {
            $.notifyClose();
            $.notify({
                message: "\nCorreo electrónico  duplicado.",
                icon: "admin/images/warning.svg",
            }, {
                position: "fixed",
                placement: {
                    from: "top",
                    align: "center",
                },
                icon_type: "image",
                allow_dismiss: true,
                newest_on_top: true,
                delay: 10000,
                template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                    "</div>",
                spacing: 35,
            });
            return false;
        }
        if (movil) {
            movil = "+51" + movil;
        }
        usuario.numero_documento = dni;
        usuario.nombres = nombre;
        usuario.correo = correo;
        usuario.numero_celular = movil;
        posicion_usuario = null;
        $('.btn_crear').text('Agregar');
        $.notifyClose();
        $.notify({
            message: '\nColaborador editado',
            icon: "admin/images/checked.svg",
        }, {
            position: "fixed",
            icon_type: 'image',
            placement: {
                from: "top",
                align: "center",
            },
            allow_dismiss: true,
            newest_on_top: true,
            delay: 6000,
            template:
                '<div data-notify="container" class="col-xs-8 col-sm-2 text-center alert" style="background-color: #dff0d8;" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                '<span data-notify="title">{1}</span> ' +
                '<span style="color:#3c763d;" data-notify="message">{2}</span>' +
                "</div>",
            spacing: 35
        });
        document.getElementById('formUusers').reset();
        crearTabla();
    }

    /*  LimpiarEscritorio(); */
});
function btn_responsive() {
    $('#error_dni').hide();
    $('#error_correo').hide();
    $("#error_nombre").hide();
    $("#error_nombre_m").hide();

    //agregar usuarios independientes
    var dni = $('#dni_m').val();
    var nombre = $('#nombre_m').val();
    var correo = $('#correo_m').val();
    var movil = $('#movil_m').val();
    if (dni.length < 8) {
        $("#error_dni_m").text("Su identificador debe tener al menos 8 dígitos").show("200");
        return false;
    }
    $("#error_dni_m").hide();
    /*    const pattern = /^[a-zA-Z ]+$/;
       var resultado = pattern.test(nombre);
       if (!resultado) {
           $("#error_nombre").show();
           return false;
       } */
    $("#error_nombre").hide();
    if (movil) {
        if (movil.length > 9) {
            $("span#error_movil_m").text("Número móvil no debe superar los 9 caracteres").show("200");
            return false;
        }
        if (movil.length < 9) {
            $("span#error_movil_m").text("Número móvil tiene como mínimo 9 caracteres").show("200");
            return false;
        }
    }

    $("#error_movil_m").hide();
    $dividir_nombres = nombre.split(" ");
    if ($dividir_nombres.length >= 3) {
        var aMaterno = $dividir_nombres.pop();
        var aPaterno = $dividir_nombres.pop();
        nombre = $dividir_nombres.join(' ');
    } else {
        var aMaterno = "";
        var aPaterno = "";
    }
    if (posicion_usuario == null) {
        if (usuarios_registrados.length < n_empleados) {
            //validamos numero de dni
            buscardni_usuario = usuarios_registrados.filter((item) => {
                return item.numero_documento == dni;
            });
            if (buscardni_usuario.length > 0) {
                $('#error_dni').show();
                return false;
            }
            $('#error_dni').hide();

            buscaremail_usuario = usuarios_registrados.filter((item) => {
                return item.correo == correo;
            });
            if (buscaremail_usuario.length > 0) {
                $("#error_correo").show();
                return false;
            }
            $("#error_correo").hide();
            if (movil) {
                movil = "+51" + movil;
            }
            $new_array = {
                numero_documento: dni,
                nombres: nombre,
                aPaterno: aPaterno,
                aMaterno: aMaterno,
                correo: correo,
                numero_celular: movil
            };
            usuarios_registrados.push($new_array);
            /*  LimpiarMovil(); */
            document.getElementById('formMobil').reset();
            $("#formNewEmp").modal("hide");
            crearTabla();
        } else {
            $("#formNewEmp").modal("hide");
            var mensaje = "Excedió el límite de colaboradores que puede registrar en la suscripción actual. Hasta " + n_empleados + " colaboradores contratados.";
            $.notifyClose();
            $.notify({
                message: mensaje,
                icon: "admin/images/warning.svg",
            }, {
                position: "fixed",
                placement: {
                    from: "top",
                    align: "center",
                },
                icon_type: "image",
                allow_dismiss: true,
                newest_on_top: true,
                delay: 10000,
                template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                    "</div>",
                spacing: 35,
            });
        }
    } else {

        usuario = usuarios_registrados[posicion_usuario];
        //comporbar si no se esta repitiendo un dni
        editar_usuarios = usuarios_registrados.filter((item, index) => {
            return posicion_usuario != index;
        });
        buscardni_usuario = editar_usuarios.filter((item) => {
            return item.numero_documento == dni;
        });
        if (buscardni_usuario.length > 0) {
            $.notifyClose();
            $.notify({
                message: "\nNúmero de documento duplicado.",
                icon: "admin/images/warning.svg",
            }, {
                position: "fixed",
                placement: {
                    from: "top",
                    align: "center",
                },
                icon_type: "image",
                allow_dismiss: true,
                newest_on_top: true,
                delay: 10000,
                template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                    "</div>",
                spacing: 35,
            });

            return false;
        }
        buscaremail_usuario = editar_usuarios.filter((item) => {
            return item.correo == correo;
        });
        if (buscaremail_usuario.length > 0) {
            $.notifyClose();
            $.notify({
                message: "\nCorreo electrónico  duplicado.",
                icon: "admin/images/warning.svg",
            }, {
                position: "fixed",
                placement: {
                    from: "top",
                    align: "center",
                },
                icon_type: "image",
                allow_dismiss: true,
                newest_on_top: true,
                delay: 10000,
                template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                    "</div>",
                spacing: 35,
            });
            return false;
        }
        if (movil) {
            movil = "+51" + movil;
        }
        usuario.numero_documento = dni;
        usuario.nombres = nombre;
        usuario.correo = correo;
        usuario.numero_celular = movil;
        $("#formNewEmp").modal("hide");
        posicion_usuario = null;
        $('.btn_crear').text('Agregar');
        $.notifyClose();
        $.notify({
            message: '\nColaborador editado',
            icon: "admin/images/checked.svg",
        }, {
            position: "fixed",
            icon_type: 'image',
            placement: {
                from: "top",
                align: "center",
            },
            allow_dismiss: true,
            newest_on_top: true,
            delay: 6000,
            template:
                '<div data-notify="container" class="col-xs-8 col-sm-2 text-center alert" style="background-color: #dff0d8;" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                '<span data-notify="title">{1}</span> ' +
                '<span style="color:#3c763d;" data-notify="message">{2}</span>' +
                "</div>",
            spacing: 35
        });
        document.getElementById('formMobil').reset();
        crearTabla();
        return false;
    }

}

/*Abril modal*/
$(".showModalMesaa").click(function () {
    $(".divSuccess").hide("");
    $(".divFrame").hide();
    $(".questionCar").show();
    $(".progress-bar").attr("style", "width: 0%");
    $(".progress-bar").attr("aria-valuenow", "0");
    $(".progress-bar").text("0%");
    $("div#forumuRegistro").modal({ backdrop: "static", keyboard: false }, "show");
});




$(".selectYes").click(function () {
    $(".divSuccess").hide("");
    $(".divFrame").fadeIn();
    $(".questionCar").hide();

    $(".progress-bar").attr("style", "width: 0%");
    $(".progress-bar").attr("aria-valuenow", "0");
    $(".progress-bar").text("0%");
    $("div#forumuRegistro").modal({ backdrop: "static", keyboard: false }, "show");
});

function showFormAddModal(items) {
    $("#error_dni_m").hide();
    $('#error_nombre').hide();
    $("#error_nombre_m").hide();
    $("#error_correo").hide();
    $('#error_email_m').hide();
    if (items != null) {
        posicion_usuario = items;
        usuarios_editar = usuarios_registrados.filter((item, index) => {
            return items == index;
        });
        $('.btn_crear').text('Actualizar');
        $('#dni_m').val(usuarios_editar[0].numero_documento);
        $('#nombre_m').val(usuarios_editar[0].nombres + " " + usuarios_editar[0].aPaterno + " " + usuarios_editar[0].aMaterno);
        $('#correo_m').val(usuarios_editar[0].correo);
        var movil = usuarios_editar[0].numero_celular;
        var movil_codigo = movil.includes('+51')
        if (movil_codigo) {
            movil = movil.substring(3);
        }
        $('#movil_m').val(movil);
    } else {
        posicion_usuario = null;
        LimpiarMovil();
        $('.btn_crear').text('Agregar');
    }
    $("div#formNewEmp").modal({ backdrop: "static", keyboard: false }, "show");
}

/*Show Formulario*/
function showFormAdd(items) {
    $("#error_ndocu_e").hide();
    $("#error_nombreC_e").hide();
    $('#error_correoC_e').hide();
    $("#error_dni_e").hide();
    $("#error_correo_e").hide();
    $('#error_nombre_e').hide();
    if (items != null) {
        posicion_usuario = items;
        usuarios_editar = usuarios_registrados.filter((item, index) => {
            return items == index;
        });
        $('.btn_crear').text('Actualizar');

        $('#dni').val(usuarios_editar[0].numero_documento);
        $('#nombre').val(usuarios_editar[0].nombres + " " + usuarios_editar[0].aPaterno + " " + usuarios_editar[0].aMaterno);
        $('#correo').val(usuarios_editar[0].correo);
        var movil = usuarios_editar[0].numero_celular;
        var movil_codigo = movil.includes('+51')
        if (movil_codigo) {
            movil = movil.substring(3);
        }
        $('#movil').val(movil);
    } else {
        posicion_usuario = null;
        LimpiarEscritorio();
        $('.btn_crear').text('Agregar');
    }
    $(".formAddUserMa").fadeIn("200");
}

$("#tabla_colaboradores").dataTable({
    searching: false,
    ordering: false,
    responsive: true,
    language: {
        decimal: "",
        emptyTable: "No hay información",
        info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
        infoFiltered: "(Filtrado de _MAX_ total entradas)",
        infoPostFix: "",
        thousands: ",",
        lengthMenu: "Mostrar _MENU_ Entradas",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        search: "Buscar:",
        zeroRecords: "Sin resultados encontrados",
        paginate: {
            first: "Primero",
            last: "Ultimo",
            next: "Siguiente",
            previous: "Anterior",
        },
    },
});

function eliminarData(items) {
    usuarios_registrados = usuarios_registrados.filter((item, index) => {
        return items != index;
    });
    crearTabla();
}

/* var elemen1 = $(".row.buttonConf").detach();
$("div#example_wrapper").append(elemen1);

 */
// change inner text
$("#file").change(function () {
    var filesCount = $(this)[0].files.length;
    if (filesCount === 1) {
        // if single file is selected, show file name
        var formData = new FormData();
        formData.append('file', $('#file').prop("files")[0]);
        $.ajax({
            type: "POST",
            url: "/ImportarEmpleados",
            data: formData,
            contentType: false,
            processData: false,
            /* enctype: 'multipart/form-data', */
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            statusCode: {
                /*401: function () {
                    location.reload();
                },*/
                419: function () {
                    location.reload();
                },
            },
            success: function (data) {
                data.empleados.forEach(element => {
                    if (usuarios_registrados.length < n_empleados) {
                        buscardni_usuario = usuarios_registrados.filter((item) => {
                            return item.numero_documento == element.numero_documento;
                        });

                        buscaremail_usuario = usuarios_registrados.filter((item) => {
                            return item.correo == element.correo;
                        });

                        if (buscardni_usuario.length == 0 && buscaremail_usuario.length == 0) {
                            $new_array = {
                                numero_documento: element.numero_documento,
                                nombres: element.nombres,
                                aPaterno: element.aPaterno,
                                aMaterno: element.aMaterno,
                                correo: element.correo,
                                numero_celular: element.numero_celular
                            };
                            usuarios_registrados.push($new_array);
                        }
                    }
                });
                file = $('#file').val("");
                $('#MostrarFilas').text(data.filas)

                if (data.alert) {
                    $('#informacionErrorres').show();
                    $('#MostrarAlerta').text(data.alert)
                } else {
                    $('#informacionErrorres').hide();
                }
                if (data.filas > 0) {
                    crearTabla();
                }

            },
            error: function (jqXHR, data, errorThrown) { },
        });

        $(".divFrame").hide();
        $(".divSuccess").fadeIn("200");
        $(".progress-bar").attr("style", "width: 0%;background:red");
        $(".progress-bar").attr("aria-valuenow", "100");
        $(".progress-bar").text("0%");


    } else {
        // otherwise show number of files

        alert(filesCount + " files selected");
    }
});


$(".closeModal").click(function () {
    $("div#forumuRegistro").modal("hide");
});
function crearTabla() {
    let thead = "";
    let tbody = "";
    let ttable = "";
    thead += `<thead style="background: #edf0f1;color: #6c757d;font-size: 12px"
    style="width:100%!important">
    <tr>
        <th>N° de documento</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>N. movil (opcional)</th>
        <th></th>
    </tr>
    </thead>`;
    var total = usuarios_registrados.length;
    var inicio = 0;
    usuarios_registrados.forEach(element => {
        inicio++;
        tbody += `<tr>`;
        tbody += `
        </td>
        <td scope="row" > <div class="dataComple">
                ${element.numero_documento}
            </div></td>
          <td scope="row" > <div class="dataComple">
          ${element.nombres}
            </div></td>
                <td scope="row" > <div class="dataComple">
                ${element.correo}
            </div></td>
                <td scope="row" > <div class="dataComple">
                ${element.numero_celular}
            </div></td>
                <td>
          <div class="dataComple eliBorder">
              <a class="delteDa" onclick="eliminarData(${usuarios_registrados.indexOf(element)});"
                  style="margin-right: 10px;">
                  <svg width="20" height="20"
                      viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <circle cx="10" cy="10"
                          r="10" fill="#F7596E" />
                      <rect x="5.38477" y="9.23071"
                          width="9.23077" height="1.53846"
                          rx="0.769231" fill="white" />
                  </svg>
              </a>

              <a class="editDa showDesk" onclick="showFormAdd(${usuarios_registrados.indexOf(element)})">
                  <svg width="20" height="20"
                      viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M0.0027861 11.4732C0.0027861 9.61446 -0.00348262 7.74946 0.0027861 5.89072C0.00905481 4.42 0.980705 3.26845 2.42251 3.01186C2.57296 2.98682 2.73595 2.98056 2.89266 2.98056C4.90492 2.98056 6.91718 2.97431 8.9357 2.98056C9.70049 2.98056 10.1894 3.65647 9.93243 4.33238C9.77571 4.73917 9.41839 4.97073 8.9357 4.97073C7.58166 4.97073 6.22762 4.97073 4.86731 4.97073C4.24044 4.97073 3.61356 4.97073 2.98669 4.97073C2.36609 4.97073 2.00251 5.32746 2.00251 5.94704C2.00251 9.6395 2.00251 13.332 2.00251 17.0244C2.00251 17.644 2.36609 17.9945 2.98669 18.0007C6.67897 18.0007 10.365 18.0007 14.0572 18.0007C14.6904 18.0007 15.0477 17.644 15.0477 17.0119C15.0477 15.1907 15.0477 13.3695 15.0477 11.5421C15.0477 10.9037 15.4865 10.4781 16.0883 10.4969C16.5961 10.5157 17.0161 10.91 17.0349 11.4169C17.0537 11.855 17.0411 12.2931 17.0411 12.7311C17.0411 14.1831 17.0474 15.6413 17.0411 17.0932C17.0349 18.5515 16.0632 19.6967 14.6277 19.9596C14.4522 19.9909 14.2704 19.9971 14.0886 19.9971C10.3775 19.9971 6.66016 20.0034 2.94908 19.9971C1.45713 19.9971 0.297416 19.0333 0.0341297 17.5814C0.00278609 17.3937 0.0027861 17.1934 0.0027861 16.9994C0.0027861 15.1531 0.0027861 13.3132 0.0027861 11.4732Z"
                          fill="#378CF4" />
                      <path
                          d="M7.07433 13.9766C6.3785 13.9766 5.91462 13.4258 6.03999 12.7687C6.26566 11.5859 6.50387 10.4093 6.75462 9.2327C6.79224 9.04495 6.91134 8.85094 7.04925 8.71325C9.83256 5.91575 12.6159 3.1245 15.4054 0.33326C15.8505 -0.111087 16.4398 -0.111087 16.8849 0.33326C17.8189 1.24699 18.7467 2.17323 19.6682 3.09947C20.107 3.53756 20.1133 4.12585 19.6744 4.56394C16.8911 7.36144 14.1016 10.1464 11.3182 12.9439C11.0424 13.2193 10.6851 13.2756 10.3403 13.3445C9.31853 13.551 8.29673 13.7513 7.2812 13.9515C7.19343 13.9703 7.10567 13.9703 7.07433 13.9766ZM8.303 11.7048C8.85464 11.5921 9.36241 11.4982 9.86391 11.3856C9.98928 11.3543 10.1272 11.2855 10.2212 11.1978C11.7821 9.64575 13.3305 8.08741 14.8851 6.52907C14.9353 6.479 14.9729 6.42894 14.998 6.39765C14.5216 5.92201 14.0577 5.46514 13.5938 4.99576C13.6001 4.98951 13.5875 4.99576 13.575 5.00828C11.9639 6.61669 10.3529 8.23136 8.74807 9.83976C8.70419 9.88357 8.66031 9.9399 8.64777 9.99622C8.52867 10.547 8.4221 11.0977 8.303 11.7048ZM15.0732 3.53756C15.5371 4.00068 16.001 4.4638 16.4398 4.90189C16.7908 4.55142 17.1607 4.18217 17.5117 3.82544C17.0604 3.37484 16.6028 2.91798 16.1514 2.46737C15.8066 2.81158 15.4431 3.17457 15.0732 3.53756Z"
                          fill="#378CF4" />
                  </svg>
              </a>

              <a class="editDa showMobil"
                  onclick="showFormAddModal(${usuarios_registrados.indexOf(element)})">
                  <svg width="20" height="20"
                      viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M0.0027861 11.4732C0.0027861 9.61446 -0.00348262 7.74946 0.0027861 5.89072C0.00905481 4.42 0.980705 3.26845 2.42251 3.01186C2.57296 2.98682 2.73595 2.98056 2.89266 2.98056C4.90492 2.98056 6.91718 2.97431 8.9357 2.98056C9.70049 2.98056 10.1894 3.65647 9.93243 4.33238C9.77571 4.73917 9.41839 4.97073 8.9357 4.97073C7.58166 4.97073 6.22762 4.97073 4.86731 4.97073C4.24044 4.97073 3.61356 4.97073 2.98669 4.97073C2.36609 4.97073 2.00251 5.32746 2.00251 5.94704C2.00251 9.6395 2.00251 13.332 2.00251 17.0244C2.00251 17.644 2.36609 17.9945 2.98669 18.0007C6.67897 18.0007 10.365 18.0007 14.0572 18.0007C14.6904 18.0007 15.0477 17.644 15.0477 17.0119C15.0477 15.1907 15.0477 13.3695 15.0477 11.5421C15.0477 10.9037 15.4865 10.4781 16.0883 10.4969C16.5961 10.5157 17.0161 10.91 17.0349 11.4169C17.0537 11.855 17.0411 12.2931 17.0411 12.7311C17.0411 14.1831 17.0474 15.6413 17.0411 17.0932C17.0349 18.5515 16.0632 19.6967 14.6277 19.9596C14.4522 19.9909 14.2704 19.9971 14.0886 19.9971C10.3775 19.9971 6.66016 20.0034 2.94908 19.9971C1.45713 19.9971 0.297416 19.0333 0.0341297 17.5814C0.00278609 17.3937 0.0027861 17.1934 0.0027861 16.9994C0.0027861 15.1531 0.0027861 13.3132 0.0027861 11.4732Z"
                          fill="#378CF4" />
                      <path
                          d="M7.07433 13.9766C6.3785 13.9766 5.91462 13.4258 6.03999 12.7687C6.26566 11.5859 6.50387 10.4093 6.75462 9.2327C6.79224 9.04495 6.91134 8.85094 7.04925 8.71325C9.83256 5.91575 12.6159 3.1245 15.4054 0.33326C15.8505 -0.111087 16.4398 -0.111087 16.8849 0.33326C17.8189 1.24699 18.7467 2.17323 19.6682 3.09947C20.107 3.53756 20.1133 4.12585 19.6744 4.56394C16.8911 7.36144 14.1016 10.1464 11.3182 12.9439C11.0424 13.2193 10.6851 13.2756 10.3403 13.3445C9.31853 13.551 8.29673 13.7513 7.2812 13.9515C7.19343 13.9703 7.10567 13.9703 7.07433 13.9766ZM8.303 11.7048C8.85464 11.5921 9.36241 11.4982 9.86391 11.3856C9.98928 11.3543 10.1272 11.2855 10.2212 11.1978C11.7821 9.64575 13.3305 8.08741 14.8851 6.52907C14.9353 6.479 14.9729 6.42894 14.998 6.39765C14.5216 5.92201 14.0577 5.46514 13.5938 4.99576C13.6001 4.98951 13.5875 4.99576 13.575 5.00828C11.9639 6.61669 10.3529 8.23136 8.74807 9.83976C8.70419 9.88357 8.66031 9.9399 8.64777 9.99622C8.52867 10.547 8.4221 11.0977 8.303 11.7048ZM15.0732 3.53756C15.5371 4.00068 16.001 4.4638 16.4398 4.90189C16.7908 4.55142 17.1607 4.18217 17.5117 3.82544C17.0604 3.37484 16.6028 2.91798 16.1514 2.46737C15.8066 2.81158 15.4431 3.17457 15.0732 3.53756Z"
                          fill="#378CF4" />
                  </svg>
              </a>
          </div>
      </td>
          `;
        tbody += `</tr>`;
        var porcentaje = (inicio / total) * 100;
        var contenido_pro = parseFloat(porcentaje);
        var promedio = contenido_pro.toFixed(2);
        if (promedio >= 50) nivel = "green";
        else if (promedio > 35) nivel = "#f3c623";
        else nivel = "red";
        $(".progress-bar").attr("style", "width: " + promedio + "%;background:" + nivel);
        $(".progress-bar").attr("aria-valuenow", "100");
        $(".progress-bar").text(promedio + "%");
    });
    tbody = `<tbody>` + tbody + `</tbody>`;
    ttable = thead + tbody;
    $('#tabla_colaboradores').DataTable().destroy();
    $('#tabla_colaboradores').html(ttable);
    $("#tabla_colaboradores").DataTable(({
        responsive: true,
        searching: false,
        ordering: false,
        language: {
            decimal: "",
            emptyTable: "No hay información",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
            infoFiltered: "(Filtrado de _MAX_ total entradas)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ Entradas",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "Sin resultados encontrados",
            paginate: {
                first: "Primero",
                last: "Ultimo",
                next: "Siguiente",
                previous: "Anterior",
            },
        },
    }));
}
function LimpiarEscritorio() {
    $('#dni').val("");
    $('#nombre').val("");
    $('#correo').val("");
    $('#movil').val("");
}
function LimpiarMovil() {
    $('#dni_m').val("");
    $('#nombre_m').val("");
    $('#correo_m').val("");
    $('#movil_m').val("");
}

/*Validacion del formulario registrar colaboradores*/

$("input#dni").on("blur", function () {
    $("li#error_ndocu_e").text("").hide("200");
    if (!$(this).val().match("^[0-9]+$")) {
        $("li#error_ndocu_e").text("Escriba su número documento correctamente").show("200");
    } else if ($("input#dni").val().length < 8) {
        $("li#error_ndocu_e").text("Su identificador debe tener al menos 8 dígitos").show("200");
    }
});


/* $("input#nombre").on("blur", function () {
    $("li#error_nombreC_e").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-Z ]+$')) {
        $("li#error_nombreC_e").text("Escriba sus nombres correctamente").show("200");
    }
}); */


$("input#correo").on("blur", function () {
    $("li#error_correoC_e").text("").hide("200");
    if (!$(this).val().match(/^([a-zA-Z0-9_\.\+\-])+\@(([a-zA-z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/)) {
        $("li#error_correoC_e").text("Escriba su email correctamente").show("200");
    }
});



$("input#dni_m").on("blur", function () {
    $("li#error_ndocu_e").text("").hide("200");
    if (!$(this).val().match("^[0-9]+$")) {
        $("span#error_dni_m").text("Escriba su número documento correctamente").show("200");
    } else if ($("input#dni_m").val().length < 8) {
        $("span#error_dni_m").text("Su identificador debe tener al menos 8 dígitos").show("200");
    }
});


/* $("input#nombre_m").on("blur", function () {
    $("span#error_nombre_m").text("").hide("200");
    var value_input = $(this).val().replace(/\s+/g, " ").trim();
    $(this).val(value_input);
    if (!$(this).val().match('^[a-zA-Z ]+$')) {
        $("span#error_nombre_m").text("Escriba sus nombres correctamente").show("200");
    }
}); */
$("input#correo_m").on("blur", function () {
    $("span#error_email_m").text("").hide("200");
    if (!$(this).val().match(/^([a-zA-Z0-9_\.\+\-])+\@(([a-zA-z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/)) {
        $("span#error_email_m").text("Escriba su email correctamente").show("200");
    }
});


$("#dni").keyup(function () {
    if (!$(this).val()) {
        $('#error_dni_e').hide();
    }
});

$("#correo").keyup(function () {
    if (!$(this).val()) {
        $('#error_correo_e').hide();
    }
});
$("#dni_m").keyup(function () {
    if (!$(this).val()) {
        $('#error_dni').hide();
    }
});
$("#correo_m").keyup(function () {
    if (!$(this).val()) {
        $('#error_correo').hide();
    }
});
$("#nombre_m").keyup(function () {
    if (!$(this).val()) {
        $('#error_nombre').hide();
    }
});
$("#nombre").keyup(function () {
    if (!$(this).val()) {
        $('#error_nombre_e').hide();
    }
});
/* window.addEventListener("beforeunload", (evento) => {
    if (true) {
        evento.preventDefault();
        evento.returnValue = "";
        return "";
    }
}); */
// change inner text
$("#guardarEmpleados").click(function () {
    if (usuarios_registrados.length > 0) {
        $('#ModalGif').modal({ backdrop: 'static', keyboard: false })
        $.ajax({
            type: "POST",
            url: "/GuardarEmpleados",
            data: { data: usuarios_registrados },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            statusCode: {
                /*401: function () {
                    location.reload();
                },*/
                419: function () {
                    location.reload();
                },
            },
            success: function (data) {
                if (data.success) {
                    setTimeout(function () { $('#ModalGif').modal('hide'); }, 100);
                    $('#ModalNotificaciones').modal();
                } else {
                    setTimeout(function () { $('#ModalGif').modal('hide'); }, 1000);
                    $.notifyClose();
                    $.notify({
                        message: data.respuesta,
                        icon: "admin/images/warning.svg",
                    }, {
                        position: "fixed",
                        placement: {
                            from: "top",
                            align: "center",
                        },
                        icon_type: "image",
                        allow_dismiss: true,
                        newest_on_top: true,
                        delay: 10000,
                        template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                            '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                            '<span data-notify="title">{1}</span> ' +
                            '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                            "</div>",
                        spacing: 35,
                    });
                }


            },
            error: function (jqXHR, data, errorThrown) { },
        });
    } else {
        $.notifyClose();
        $.notify({
            message: "\nDebe agrega colaboradores.",
            icon: "admin/images/warning.svg",
        }, {
            position: "fixed",
            placement: {
                from: "top",
                align: "center",
            },
            icon_type: "image",
            allow_dismiss: true,
            newest_on_top: true,
            delay: 10000,
            template: '<div data-notify="container" class="col-xs-12 col-sm-3 text-center alert" style="background-color: #fcf8e3;" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<img data-notify="icon" class="img-circle pull-left" height="20">' +
                '<span data-notify="title">{1}</span> ' +
                '<span style="color:#8a6d3b;" data-notify="message">{2}</span>' +
                "</div>",
            spacing: 35,
        });
    }

});
$("#modalExito").click(function () {
    var url_actual = $(location).attr("origin");
    location.href = url_actual + "/controlRemoto";
});
