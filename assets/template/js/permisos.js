$(document).ready(function(){
    //Declaración de variables
    var heading="";
    var icon="";
    var text="";
    var estado = "";
    var data = {};

    $(".permisos-menu").addClass("menu-item-active");
    $(".configuracion-menu").addClass("menu-item-open");

    $(document).on('click', '#add_button', function(e){
        e.preventDefault();
        $('#permisosModal').modal('show');
    });

    //Creacion de la tabla usuario activos,
    var dataTable = $('#permisos_table').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: base_url + "permisos/listar_permisos",
            type:"POST"
        },
        "columnDefs":[
            {
                "orderable":false,
            },
        ],    
        "rowCallback": function( row, data ) {
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title","Ruta");
            $('td:nth-child(2)', row).attr("data-title","Alias");
            $('td:nth-child(3)', row).attr("data-title","Estado");
            $('td:nth-child(4)', row).attr("data-title","Padre");
            $('td:nth-child(5)', row).attr("data-title","Acción");            
        }
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form){
            var data = {};

            data.ruta = document.getElementById("ruta").value.trim();
            data.alias = document.getElementById("alias").value.trim();
            data.estado = document.getElementById("estado").value.trim();
            data.padre = document.getElementById("padre").value.trim();

            ajax('permisos/crear',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if(response.res != $EXIT_ERROR){
                    dataTable.ajax.reload();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                          
                    $('#padre').val("0").trigger('change.select2');
                    $('#permisosModal').modal('hide');

                    var data_permiso = {};
                    ajax('permisos/listar_permisos',data_permiso,function(response){
                        $("#padre, #padre_update").empty();
                        $("#padre, #padre_update").append('<option value="0">Seleccione el padre</option>');                        
                        for (var i = 0; i < response.data.length; i++) {
                            $("#padre, #padre_update").append('<option value="'+response.data[i][5]+'">'+response.data[i][0]+'</option>');
                        }                        
                    });
                }
                
                return mensaje_toast(heading,text,icon);
            });
        }
    });

     //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e){
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('permisos/listar_id',data,function(response){
            $('#permisosModalupdate').modal('show');

            $('#estado_update').val(response.estado).trigger('change.select2');
            $('#padre_update').val(response.padre).trigger('change.select2');
            $("#ruta_update").val(response.ruta);
            $("#alias_update").val(response.alias);
            
            $("#permisos_id_update").val(response.id);
        });
    });

    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({        
        submitHandler: function(form){
            var data = {};

            data.id = document.getElementById("permisos_id_update").value.trim();
            data.ruta = document.getElementById("ruta_update").value.trim();
            data.alias = document.getElementById("alias_update").value.trim();
            data.estado = document.getElementById("estado_update").value.trim();
            data.padre = document.getElementById("padre_update").value.trim();

            ajax('permisos/actualizar',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
       
                if(response.res == $EXIT_SUCCESS){
                  heading = "Modificado!";
                  icon = "success";
                  dataTable.ajax.reload();
                  $('#permisosModalupdate').modal('hide');
                  
                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });

});