$(document).ready(function(){
  
  $(".formularios-menu").addClass("menu-item-active");
  
  //Creacion de la tabla usuario activos,
  var dataTable = $('#formularios_table').DataTable({
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
          url: base_url + "formularios/listar_formularios",
          type:"POST"
      },
      "columnDefs":[
          {
              "orderable":false,
          },
      ],    
      "rowCallback": function( row ) {
          $(row).addClass('xs-block');
          $('td:nth-child(1)', row).attr("data-title","ID");
          $('td:nth-child(2)', row).attr("data-title","Nombre");
          $('td:nth-child(3)', row).attr("data-title","Estado");
          $('td:nth-child(4)', row).attr("data-title","Fecha publicación");
          $('td:nth-child(5)', row).attr("data-title","Acción");            
      }
  });

   //Mostrar la ventana modal y cargar renombrar el texto
   $(document).on('click', '.delete', function(e){
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");        
        ajax('formularios/listar_id',data,function(response){
            $('#formularioModaleliminar').modal('show');
            $("#formularioModaleliminar > div > div > div > h4").html("Esta seguro de eliminar el formulario '"+response.nombre+"'");
            $(".eliminar").val("Eliminar");
            $(".eliminar").attr("id", response.id);        
        });
    });

    //Enviar los datos al controlador para eliminarlo
    $(document).on('click', '.eliminar', function(e) {
        var data = {};
        data.id = $(this).attr("id");

        ajax('formularios/eliminar',data,function(response){
            heading = "¡Eliminado!";
            text = response.msg;
            icon = "info";    

            if(response.res==$EXIT_ERROR){
                heading = "¡Alerta!";
                text = response.msg;
                icon = "error";
            }

            dataTable.ajax.reload();
            $('#formularioModaleliminar').modal('hide');
            return mensaje_toast(heading,text,icon);
        });
    });
});