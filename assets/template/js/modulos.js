$(document).ready(function() {
    $(".proyectos-menu").addClass("menu-item-active");
    cargar_modulos();

    $(document).on('click', '#add_button', function(e) {
        e.preventDefault();
        $('#modulosModal').modal('show');
    });

    function cargar_modulos() {
        var data = {};
        data.url_proyecto = $url_proyecto;
        data.url_metodologia = $url_metodologia;
        data.url_fase = $url_fase;
        ajax('modulos/obtener_modulos', data, function(response) {

            if (response.res != $EXIT_SUCCESS) {
                heading = "Modificado!";
                icon = "success";
                text = response.msg;
                return mensaje_toast(heading, text, icon);
            }

            $("#contador-elementos div").remove();

            if (response.data.length == 0) {
                $("#contador-elementos").append(`<div class="contenedor-elementos">No hay modulos registradas</div>`);
            }

            $("#subheader_total").text(response.data.length + " Total");

            for (var i = 0; i < response.data.length; i++) {
                $("#contador-elementos").append(`

                    <div class="col-12 col-md-8" id="${response.data[i][2]}">
                        <div class="card card-custom mb-5">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                    <div class="d-flex flex-column mr-5">
                                        <a href="` + base_url + 'historia/listar/' + $url_proyecto + '/' + $url_metodologia + '/' + $url_fase + '/' + response.data[i][4] + `" class="h4 text-dark text-hover-primary ">` + response.data[i][0] + `</a>
                                        <span class="label label-` + response.data[i]['estadoColor'] + ` label-inline mr-2 mb-5" style="max-width: max-content;">` + response.data[i]['estado'] + `</span>
                                        <p class="text-dark-50 mb-0">Presupuesto: ` + response.data[i]['presupuesto'] + `</p>
                                        <p class="text-dark-50 mb-0">Descripción: ` + response.data[i][3] + `</p>
                                        <p class="text-info ">Publicado: ` + response.data[i][1] + `</p>
                                    </div>
                                    <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                        ` + response.data[i][5] + `
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    }

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form) {
            var data = {};
            data.nombre = document.getElementById("nombre").value.trim();
            data.descripcion = document.getElementById("descripcion").value.trim();
            data.url_proyecto = $url_proyecto;
            data.url_metodologia = $url_metodologia;
            data.url_fase = $url_fase;
            ajax('modulos/crear', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if (response.res != $EXIT_ERROR) {
                    cargar_modulos();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                    $("input[type=text], textarea").val("");
                    $('#modulosModal').modal('hide');
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('modulos/listar_id', data, function(response) {
            $('#modulosModalupdate').modal('show');
            $("#nombre_update").val(response.nombre);
            $("#descripcion_update").val(response.descripcion);
            $("#modulos_id_update").val(response.id);
        });
    });

    //Validate para modificar la metodologia con sus datos ya cargados.
    $("#form_update").validate({
        submitHandler: function(form) {
            var data = {};

            data.id = document.getElementById("modulos_id_update").value.trim();
            data.nombre = document.getElementById("nombre_update").value.trim();
            data.descripcion = document.getElementById("descripcion_update").value.trim();

            ajax('modulos/actualizar', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    $('#modulosModalupdate').modal('hide');
                    cargar_modulos();
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.delete', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('modulos/listar_id', data, function(response) {
            $('#moduloModaleliminar').modal('show');
            $("#modulos_id_eliminar").val(response.id);
        });
    });

    $(document).on('click', '.eliminar', function(e) {
        var data = {};
        data.id = document.getElementById("modulos_id_eliminar").value.trim();

        ajax('modulos/eliminar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.estado != $EXIT_ERROR) {
                cargar_modulos();
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";
            }

            $('#moduloModaleliminar').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    $("#contador-elementos").sortable({
        update: function(event, ui) {
            changeLoadPosition();
        }
    });
    $("#contador-elementos").disableSelection();

    function changeLoadPosition(){
        var data = {};
        var modulos = [];
        var count = 0;
        $(".ui-sortable > div").each(function() {
            let id = $(this).attr("id");
            if(id != undefined){
                modulos[count] = $(this).attr("id");
                count++;
            }
        });

        data.modulos = modulos;
        console.log(data);
        ajax('modulos/ordenar', data, function(response) {
            
        }, 'POST');
    }

});