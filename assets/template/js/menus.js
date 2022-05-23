$(document).ready(function(){
    //Declaración de variables
    var heading="";
    var icon="";
    var text="";
    var estado = "";
    var data = {};

    var pathname = window.location.pathname;
    $(".menus-menu").addClass("menu-item-active");
    $(".configuracion-menu").addClass("menu-item-open");

    $(document).on('click', '#add_button', function(e){
        e.preventDefault();
        $('#menusModal').modal('show');
    });

    //Creacion de la tabla usuario activos,
    var dataTable = $('#menus_table').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: base_url + "menus/listar_menus",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [5],
                "orderable":false,
            },
        ],    
        "rowCallback": function( row, data ) {
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title","Ruta");
            $('td:nth-child(2)', row).attr("data-title","Nombre");
            $('td:nth-child(3)', row).attr("data-title","Clase");
            $('td:nth-child(4)', row).attr("data-title","Icono");
            $('td:nth-child(5)', row).attr("data-title","Padre");            
            $('td:nth-child(5)', row).attr("data-title","Acción");
        }
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form){
            var data = {};

            data.ruta = document.getElementById("ruta").value.trim();
            data.nombre = document.getElementById("nombre").value.trim();
            data.clase = document.getElementById("clase").value.trim();
            data.icono = document.getElementById("icono").value.trim();
            data.padre = document.getElementById("padre").value.trim();

            ajax('menus/crear',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if(response.res != $EXIT_ERROR){
                    dataTable.ajax.reload();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                          
                    $('#padre').val("0").trigger('change.select2');
                    $('#menusModal').modal('hide');

                    var data_permiso = {};
                    ajax('menus/listar_menus',data_permiso,function(response){
                        $("#padre, #padre_update").empty();
                        $("#padre, #padre_update").append('<option value="0">Seleccione el padre</option>');                        
                        for (var i = 0; i < response.data.length; i++) {
                            $("#padre, #padre_update").append('<option value="'+response.data[i][6]+'">' + response.data[i][0] + " - " + response.data[i][1] + '</option>');
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
        ajax('menus/listar_id',data,function(response){
            $('#menusModalupdate').modal('show');

            data.ruta = document.getElementById("ruta").value.trim();
            data.nombre = document.getElementById("nombre").value.trim();
            data.clase = document.getElementById("clase").value.trim();
            data.icono = document.getElementById("icono").value.trim();
            data.padre = document.getElementById("padre").value.trim();

            $("#ruta_update").val(response.ruta);
            $("#nombre_update").val(response.nombre);
            $("#clase_update").val(response.clase);
            $("#icono_update").val(response.icono);
            $('#padre_update').val(response.padre).trigger('change.select2');
            
            $("#menus_id_update").val(response.id);
        });
    });

    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({        
        submitHandler: function(form){
            var data = {};

            data.id = document.getElementById("menus_id_update").value.trim();
            data.ruta = document.getElementById("ruta_update").value.trim();
            data.nombre = document.getElementById("nombre_update").value.trim();
            data.clase = document.getElementById("clase_update").value.trim();
            data.icono = document.getElementById("icono_update").value.trim();
            data.padre = document.getElementById("padre_update").value.trim();

            ajax('menus/actualizar',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
       
                if(response.res == $EXIT_SUCCESS){
                  heading = "Modificado!";
                  icon = "success";
                  dataTable.ajax.reload();
                  $('#menusModalupdate').modal('hide');
                  
                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });

});