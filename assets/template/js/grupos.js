$(document).ready(function(){
    //Declaración de variables
    var heading="";
    var icon="";
    var text="";
    var estado = "";
    var data = {};

    var pathname = window.location.pathname;
    $(".grupos-menu").addClass("menu-item-active");

    $(document).on('click', '#add_button', function(e){
        e.preventDefault();
        $('#gruposModal').modal('show');
    });

    //Creacion de la tabla usuario activos,
    var dataTable = $('#grupos_table').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: base_url + "grupos/listar_grupos",
            type:"POST"
        },
        "columnDefs":[
            {
                "orderable":false,
            },
        ],    
        "rowCallback": function( row, data ) {
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title","ID");
            $('td:nth-child(2)', row).attr("data-title","Nombre");
            $('td:nth-child(3)', row).attr("data-title","Cantidad Personas");
            $('td:nth-child(4)', row).attr("data-title","Acción");
        }
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form){
            var data = {};

            data.nombre = document.getElementById("nombre").value.trim();

            var usuarios = [];
            var contador = 0;
            $('#usuarios option:checked').each(function(){
                usuarios[contador] = {"idusuario":$(this).val()}; 
                contador++;
            });
            data.usuarios = usuarios;

            ajax('grupos/crear',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if(response.res != $EXIT_ERROR){
                    dataTable.ajax.reload();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                    
                    $('#gruposModal').modal('hide');
                    $("textarea, input[type=text]").val("");   
                    $('#usuarios').val("").trigger('change.select2');
                    
                }
                
                return mensaje_toast(heading,text,icon);
            });
        }
    });

     //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e){
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('grupos/listar_id',data,function(response){
            $('#gruposModalupdate').modal('show');
            
            var array_estudiantes = [];
            var contador = 0;
            for (var i = 0; i < response.usuarios.length; i++) {
                array_estudiantes[contador] = response.usuarios[i]["idusuario"];
                contador++;
            }

            $('#usuarios_update').val(array_estudiantes).trigger('change');
            $('#nombre_update').val(response.nombre);
            $("#grupos_id_update").val(response.idgrupo);
        });
    });

    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({        
        submitHandler: function(form){
            var data = {};

            data.id = document.getElementById("grupos_id_update").value.trim();
            data.nombre = document.getElementById("nombre_update").value.trim();

            var usuarios = [];
            var contador = 0;
            $('#usuarios_update option:checked').each(function(){
                usuarios[contador] = {"idusuario":$(this).val()}; 
                contador++;
            });
            data.usuarios = usuarios;

            ajax('grupos/actualizar',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
       
                if(response.res == $EXIT_SUCCESS){
                  heading = "Modificado!";
                  icon = "success";
                  dataTable.ajax.reload();
                  $('#gruposModalupdate').modal('hide');
                  
                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });

     //Mostrar la ventana modal y cargar renombrar el texto
    $(document).on('click', '.delete', function(e){
        e.preventDefault();
        data.id = $(this).attr("id");        
        ajax('grupos/listar_id',data,function(response){
            $('#grupoModaleliminar').modal('show');
            $("#grupoModaleliminar > div > div > div > h4").html("Esta seguro de eliminar el usuario '"+response.nombre+"'");
            $(".eliminar").val("Eliminar");
            $(".eliminar").attr("id", response.idgrupo);        
        });
    });

    //Enviar los datos al controlador para eliminarlo
    $(document).on('click', '.eliminar', function(e){
        data.id = $(this).attr("id");

        ajax('grupos/eliminar',data,function(response){
            heading = "Alerta!";
            text = response.msg;
            icon = "error";    

            if(response.estado!=$EXIT_ERROR){
                dataTable.ajax.reload();
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";
            }
            
            $('#grupoModaleliminar').modal('hide');
            return mensaje_toast(heading,text,icon);
        });
    });

    

});