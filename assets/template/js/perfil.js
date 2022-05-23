"use strict";
var UserEdit = { init: function() { new KTImageInput("user_edit") } };
jQuery(document).ready(function() {    
    UserEdit.init();
});

//Validate para cambiar la contraseña general por usuario
$("#form-change-password-general").validate({

   rules:{
       "change-password-repit-general":{
           equalTo:"#change-password-general",
       }
   },

   submitHandler: function(form){
       var data = {};
       data.password_actual = document.getElementById("change-password-general-actual").value.trim();
       data.password = document.getElementById("change-password-general").value.trim();

       ajax('Usuarios/cambiar_contrasena_general',data,function(response){
           var heading = "Alerta!";
           var text = response.msg;
           var icon = "error";
           
           if(response.res== $EXIT_SUCCESS)
           {
               $("#change-password-general-actual").val("");
               $("#change-password-general").val("");
               $("#change-password-repit-general").val("");
               heading = "Modificada!";
               icon = "success";
               $("#userModalChangePasswordGeneral").modal('hide');
           }          

           return mensaje_toast(heading,text,icon);
       });
   }
});

//Validate para cambiar la contraseña general por usuario
$("#form-change-datos").validate({

   submitHandler: function(form){

       var formData = new FormData(document.getElementById("form-change-datos"));

       $.ajax({
           url: base_url + 'usuarios/modificar_perfil',
           type: "post",
           dataType: "json",
           data: formData,
           cache: false,
           contentType: false,
           processData: false
       }).done(function(response) {
           var heading = "Alerta!";
           var text = response.msg;
           var icon = "error";
           
           if(response.res== $EXIT_SUCCESS)
           {
               heading = "Modificada!";
               icon = "success";
               $("#userModalChangeDatos").modal('hide');
               setInterval(function(){ location.reload(); }, 3000);
           }          

           return mensaje_toast(heading,text,icon);
       });
   }
});
