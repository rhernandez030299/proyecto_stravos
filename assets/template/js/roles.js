$(document).ready(function(){
    //Declaración de variables
    var heading="";
    var icon="";
    var text="";
    var estado = "";
    var data = {};

    $('#jstree, #jstree_menu').jstree({
  
        'plugins': ["wholerow", "checkbox"]
    });
    
    $(".roles-menu").addClass("menu-item-active");
    $(".configuracion-menu").addClass("menu-item-open");

    $(document).on('click', '#add_button', function(e){
        e.preventDefault();
        $('#rolModal').modal('show');
    });

    //Creacion de la tabla roles activos,
    var dataTable = $('#roles').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: base_url + "roles/listar_roles",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [2],
                "orderable":false,
            },
        ],    
        "rowCallback": function( row, data ) {            
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title","Id");
            $('td:nth-child(2)', row).attr("data-title","Rol");
            $('td:nth-child(3)', row).attr("data-title","Acción");            

            /*if ( data.estado == ROL_ACTIVO ) {
                $(row).addClass('bg-success');
            }else{
                $(row).addClass('bg-danger');
            }*/
        }
    });
    
    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form){
            var data = {};
            
            data.rol = document.getElementById("rol").value.trim(); 
            data.ruta = document.getElementById("ruta").value.trim();            

            ajax('roles/crear',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";                
                if(response.res != $EXIT_ERROR){
                    dataTable.ajax.reload();
                    heading = "Ingresado!";
                    text = response.msg;
                    icon = "info";
                    $("input[type=text]").val("");
                    $('#rolModal').modal('hide');
                }
                
                return mensaje_toast(heading,text,icon);
            });
        }
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e){
        e.preventDefault();
        data.id = $(this).attr("id");        
        ajax('roles/listar_id',data,function(response){
            $('#rolModalupdate').modal('show');
            $("#rol_update").val(response.nombre);
            $("#ruta_update").val(response.ruta);
            $("#rol_id_update").val(response.id);            
        });
    });

    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({        
        submitHandler: function(form){
            var data = {};

            data.rol = document.getElementById("rol_update").value.trim();  
            data.id = document.getElementById("rol_id_update").value.trim();
            data.ruta = document.getElementById("ruta_update").value.trim();            
           
            ajax('roles/actualizar',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if(response.res == $EXIT_SUCCESS){
                  heading = "Modificado!";
                  icon = "success";
                  dataTable.ajax.reload();
                  $('#rolModalupdate').modal('hide');                  
                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });
    
    //Modal de permisos
    $(document).on('click', '.asignar-permisos', function(e){
        e.preventDefault();
        var data = {};
        data.id = $(this).data("id");
        $('#permisosModal').modal('show');
        ajax('roles/listar_id',data,function(response){

            $('#jstree').jstree(true).deselect_all(true); 

            if(response.consultar_permiso_rol.length > 0){
                
                for (var i = 0; i < response.consultar_permiso_rol.length; i++) {
                    $('#jstree').jstree(true).select_node("APR_"+ response.consultar_permiso_rol[i]["idpermiso_arbol"] + ""); 
                }
            }
            
            $("#rol_id_permiso").val(response.id);            
        });
    });

    $("#form_permisos").validate({        
        submitHandler: function(form){
            var data = {};

            data.id = document.getElementById("rol_id_permiso").value.trim();
            data.permisos = $('#jstree').jstree(true).get_selected(); 
            
            ajax('roles/actualizar_permisos',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if(response.res == $EXIT_SUCCESS){
                  heading = "Modificado!";
                  icon = "success";
                  dataTable.ajax.reload();
                  $('#permisosModal').modal('hide');                  
                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });


    //Modal de permisos
    $(document).on('click', '.asignar-permisos-menu', function(e){
        e.preventDefault();
        var data = {};
        data.id = $(this).data("id");
        $('#permisosMenuModal').modal('show');
        ajax('roles/listar_id',data,function(response){

            $('#jstree_menu').jstree(true).deselect_all(true); 

            if(response.consultar_permiso_menu.length > 0){
                
                for (var i = 0; i < response.consultar_permiso_menu.length; i++) {
                    $('#jstree_menu').jstree(true).select_node("APRM_"+ response.consultar_permiso_menu[i]["idmenu"] + ""); 
                }
            }
            
            $("#rol_id_permiso_menu").val(response.id);            
        });
    });

    $("#form_permisos_menu").validate({        
        submitHandler: function(form){
            var data = {};

            data.id = document.getElementById("rol_id_permiso_menu").value.trim();
            data.permisos = $('#jstree_menu').jstree(true).get_selected(); 
            
            ajax('roles/actualizar_permisos_menu',data,function(response){
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if(response.res == $EXIT_SUCCESS){
                  heading = "Modificado!";
                  icon = "success";
                  dataTable.ajax.reload();
                  $('#permisosMenuModal').modal('hide');                  
                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });
    

    //Declaración de la funcion para mostrar la ventana modal de estado y cargarla con sus datos
    /*$(document).on('click', '.activo, .inactivo', function(e){
        e.preventDefault();
        data.id = $(this).attr("id");
        
        ajax('Usuarios/listar_id',data,function(response){
            $('#userModalestado').modal('show');
            var estado_nombre = "activar";
            if(response.estado ==$USUARIO_ACTIVO ){
                estado_nombre = "inactivar";
            }
            estado = response.estado;
            $("#userModalestado > div > div > div > h4").html("Esta seguro de "+estado_nombre+" el usuario '"+response.user+"'");
            $(".estado").val(estado_nombre.charAt(0).toUpperCase() + estado_nombre.slice(1).toLowerCase());   
            $(".estado").attr("id",response.id);
        });
    });*/

    //Envio de datos para modificar el estado del usuario
    /*$(document).on("click", ".estado",function(e){
        e.preventDefault();
        data.estado = estado;
        data.id = $(this).attr("id");

        ajax('Usuarios/cambiar_estado',data,function(response){
            $('#userModalestado').modal('hide');
            
            dataTable.ajax.reload();

            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if(response.res== $EXIT_SUCCESS){
                heading = "Modificado!";
                icon = "success";
            }
            return mensaje_toast(heading,text,icon);
        });
    });*/

    /*//Enviar los datos al controlador para eliminarlo
    $(document).on('click', '.eliminar', function(e){
        data.id = $(this).attr("id");

        ajax('Usuarios/eliminar',data,function(response){
            heading = "Alerta!";
            text = response.msg;
            icon = "error";    

            if(response.estado!=$EXIT_ERROR){
                dataTable.ajax.reload();
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";
            }
            
            $('#userModaleliminar').modal('hide');
            return mensaje_toast(heading,text,icon);
        });
    });*/
});