$(document).ready(function() {
    //Declaración de variables
    var heading = "";
    var icon = "";
    var text = "";
    var estado = "";
    var data = {};

    var UserEdit = { init: function() { new KTImageInput("user_edit") } };
    var UserEditUpdate = { init: function() { new KTImageInput("user_edit_update") } };
    jQuery(document).ready(function() {
        UserEdit.init();
        UserEditUpdate.init();
    });

    $(document).on('click', '#add_button', function(e) {
        e.preventDefault();
        $('#userModal').modal('show');
    });

    $(".usuarios-menu").addClass("menu-item-active");
    $(".configuracion-menu").addClass("menu-item-open");

    //Creacion de la tabla usuario activos,
    var dataTable = $('#usuarios_activos').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: base_url + "usuarios/listar_usuarios",
            type: "POST"
        },
        "columnDefs": [{
            "targets": [6],
            "orderable": false,
        }, ],
        "rowCallback": function(row, data) {
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title", "Rol");
            $('td:nth-child(2)', row).attr("data-title", "Usuario");
            $('td:nth-child(3)', row).attr("data-title", "Nombre");
            $('td:nth-child(4)', row).attr("data-title", "Correo");
            $('td:nth-child(5)', row).attr("data-title", "Creado en");
            $('td:nth-child(6)', row).attr("data-title", "Estado");
            $('td:nth-child(7)', row).attr("data-title", "Acción");
        }
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form) {
            var data = {};

            var formData = new FormData(document.getElementById("form_ingresar"));

            $.ajax({
                url: base_url + 'usuarios/crear',
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if (response.res != $EXIT_ERROR) {
                    dataTable.ajax.reload();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                    $("input[type=text], input[type=password], input[type=file]").val("");
                    $('#rol_id').val("").trigger('change.select2');
                    $('#userModal').modal('hide');
                }

                $("#user").focus();
                return mensaje_toast(heading, text, icon);
            });
        }
    });

    //Validate para cambiar la contraseña
    $("#form_change_password").validate({
        submitHandler: function(form) {
            data.password = document.getElementById("change_password").value.trim();
            data.id = document.getElementById("id_usuario_change").value.trim();

            ajax('Usuarios/cambiar_contrasena', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                $("input[type=text]").val("");
                $("input[type=password]").val("");
                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificada!";
                    icon = "success";
                    $('#userModalChangePassword').modal('hide');
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });
    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('Usuarios/listar_id', data, function(response) {
            $(".container-imagen").addClass("hide");
            $('#userModalupdate').modal('show');

            $('#rol_id_update').val(response.rol_id).trigger('change.select2');
            $("#user_update").val(response.user);
            $("#usuario_id_update").val(response.id);
            $("#nombre_update").val(response.nombre);
            $("#correo_update").val(response.correo);
            $("#apellido_update").val(response.apellido);
            $("input[type=file]").val("");

            if (response.ruta_imagen == '' || response.ruta_imagen == null) {
                $("#user_edit_update").css("background-image", "url(" + base_url + "assets/public/img/imagen3.jpg" + ")");
            } else {
                $("#user_edit_update").css("background-image", "url(" + base_url + "uploads/" + response.ruta_imagen + ")");
            }
        });
    });

    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({
        submitHandler: function(form) {
            var data = {};

            var formData = new FormData(document.getElementById("form_update"));

            $.ajax({
                url: base_url + 'usuarios/actualizar',
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                $("#nombre_acceso_update").focus();

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    dataTable.ajax.reload();
                    $('#userModalupdate').modal('hide');

                }

                return mensaje_toast(heading, text, icon);
            });

        }
    });

    //Mostrar la ventana modal y cargar renombrar el texto
    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        data.id = $(this).attr("id");

        ajax('Usuarios/listar_id', data, function(response) {
            $('#userModaleliminar').modal('show');
            $("#userModaleliminar > div > div > div > h4").html("Esta seguro de eliminar el usuario '" + response.user + "'");
            $(".eliminar").val("Eliminar");
            $(".eliminar").attr("id", response.id);
        });
    });

    //Enviar los datos al controlador para eliminarlo
    $(document).on('click', '.eliminar', function(e) {
        data.id = $(this).attr("id");

        ajax('Usuarios/eliminar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.estado != $EXIT_ERROR) {
                dataTable.ajax.reload();
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";
            }

            $('#userModaleliminar').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    //Declaración de la funcion para mostrar la ventana modal de estado y cargarla con sus datos
    $(document).on('click', '.activo, .inactivo', function(e) {
        e.preventDefault();
        data.id = $(this).attr("id");

        ajax('Usuarios/listar_id', data, function(response) {
            $('#userModalestado').modal('show');
            var estado_nombre = "activar";
            if (response.estado == $USUARIO_ACTIVO) {
                estado_nombre = "inactivar";
            }
            estado = response.estado;
            $("#userModalestado > div > div > div > h4").html("Esta seguro de " + estado_nombre + " el usuario '" + response.user + "'");
            $(".estado").val(estado_nombre.charAt(0).toUpperCase() + estado_nombre.slice(1).toLowerCase());
            $(".estado").attr("id", response.id);
        });
    });

    //Envio de datos para modificar el estado del usuario
    $(document).on("click", ".estado", function(e) {
        e.preventDefault();
        data.estado = estado;
        data.id = $(this).attr("id");

        ajax('Usuarios/cambiar_estado', data, function(response) {
            $('#userModalestado').modal('hide');

            dataTable.ajax.reload();

            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.res == $EXIT_SUCCESS) {
                heading = "Modificado!";
                icon = "success";
            }
            return mensaje_toast(heading, text, icon);
        });
    });

    //Activar la ventana modal y sobre escribir el nombre del título de la ventana
    $(document).on('click', '.change_password', function(e) {
        e.preventDefault();
        data.id = $(this).attr("id");

        ajax('Usuarios/listar_id', data, function(response) {
            $('#userModalChangePassword').modal('show');
            $("#form_change_password > div > div > h4").html("Cambiar la contraseña al usuario '" + response.user + "'");
            $(".id_usuario_change").val(response.id);
        });
    });

    //Validate para cambiar la contraseña
    $("#form_change_password").validate({
        submitHandler: function(form) {
            data.password = document.getElementById("change_password").value.trim();
            data.id = document.getElementById("id_usuario_change").value.trim();

            ajax('Usuarios/cambiar_contrasena', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                $("input[type=text]").val("");
                $("input[type=password]").val("");
                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificada!";
                    icon = "success";
                    $('#userModalChangePassword').modal('hide');
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });


});