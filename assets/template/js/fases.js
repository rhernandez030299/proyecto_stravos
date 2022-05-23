$(document).ready(function() {
    $(".proyectos-menu").addClass("menu-item-active");
    cargar_fases();

    $(document).on('click', '#add_button', function(e) {
        e.preventDefault();
        $('#fasesModal').modal('show');
    });

    function cargar_fases() {
        var data = {};
        data.url_proyecto = $url_proyecto;
        data.url_metodologia = $url_metodologia;
        ajax('fases/obtener_fases', data, function(response) {

            if (response.res != $EXIT_SUCCESS) {
                heading = "Alerta!";
                icon = "danger";
                text = response.msg;
                return mensaje_toast(heading, text, icon);
            }

            $("#contador-elementos div").remove();

            if (response.data.length == 0) {
                $("#contador-elementos").append(`<div class="contenedor-elementos">No hay fases registradas</div>`);
            }

            $("#subheader_total").text(response.data.length + " Total");

            for (var i = 0; i < response.data.length; i++) {
                $("#contador-elementos").append(`
                    <div class="col-12 col-md-8 container-card" id="${response.data[i][2]}">
                        <div class="card card-custom mb-5">
                        
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                    <div class="d-flex flex-column mr-5">
                                        <a href="` + base_url + 'proyectos/modulos/' + $url_proyecto + '/' + $url_metodologia + '/' + response.data[i][3] + `" class="h4 text-dark text-hover-primary mb-5">` + response.data[i][0] + `</a>
                                        <p class="text-dark-50 mb-0">Presupuesto: ` + response.data[i]['presupuesto'] + `</p>
                                        <p class="text-dark-50">Publicado: ` + response.data[i][1] + `</p>
                                    </div>
                                    <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                        ` + response.data[i][4] + `
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
            data.url_proyecto = $url_proyecto;
            data.url_metodologia = $url_metodologia;
            ajax('fases/crear', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if (response.res != $EXIT_ERROR) {
                    cargar_fases();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                    $("input[type=text]").val("");
                    $('#fasesModal').modal('hide');
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
        ajax('fases/listar_id', data, function(response) {
            $('#fasesModalupdate').modal('show');
            $("#nombre_update").val(response.nombre);
            $("#fases_id_update").val(response.id);
        });
    });

    //Validate para modificar la metodologia con sus datos ya cargados.
    $("#form_update").validate({
        submitHandler: function(form) {
            var data = {};

            data.id = document.getElementById("fases_id_update").value.trim();
            data.nombre = document.getElementById("nombre_update").value.trim();

            ajax('fases/actualizar', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    $('#fasesModalupdate').modal('hide');
                    cargar_fases();
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
        ajax('fases/listar_id', data, function(response) {
            $('#faseModaleliminar').modal('show');
            $("#fases_id_eliminar").val(response.id);
        });
    });

    $(document).on('click', '.eliminar', function(e) {
        var data = {};
        data.id = document.getElementById("fases_id_eliminar").value.trim();

        ajax('fases/eliminar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.estado != $EXIT_ERROR) {
                cargar_fases();
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";
            }

            $('#faseModaleliminar').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });


    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.clonar', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('fases/listar_id', data, function(response) {
            $('#faseModalclonar').modal('show');
            $("#fases_id_clonar").val(response.id);
        });
    });

    $(document).on('click', '#btn-clonar', function(e) {
        var data = {};
        data.id = document.getElementById("fases_id_clonar").value.trim();

        ajax('fases/clonar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

        
            if (response.res != $EXIT_ERROR) {
                cargar_fases();
                heading = "Clonado!";
                text = response.msg;
                icon = "success";
            }

            $('#faseModalclonar').modal('hide');
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
        var fases = [];
        var count = 0;
        $(".ui-sortable > div").each(function() {
            let id = $(this).attr("id");
            if(id != undefined){
                fases[count] = $(this).attr("id");
                count++;
            }
        });

        data.fases = fases;
        console.log(data);
        ajax('fases/ordenar', data, function(response) {
            
        }, 'POST');
    }
});