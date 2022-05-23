$(document).ready(function(){


  //Validar crearestudiante
  $("#form_ingresar").validate({

      submitHandler: function(form){

          var data = {};

          data.nombre = document.getElementById("nombre").value.trim();
          data.apellido = document.getElementById("apellido").value.trim();
          data.user = document.getElementById("user").value.trim();
          data.email = document.getElementById("email").value.trim();
          data.password = document.getElementById("password").value.trim();



          ajax('Usuarios/crearestudiante',data,function(response){
              heading = "Alerta!";
              text = response.msg;
              icon = "error";
              if(response.res != $EXIT_ERROR){
                  heading = "Usuario creado exitosamente.";
                  text = response.msg;
                  icon = "info";
                  $("input[type=text]").val("");
                  $("input[type=password]").val("");
              }
              $("#email").focus();

              return mensaje_toast(heading,text,icon);
          });
      }
  });


});
