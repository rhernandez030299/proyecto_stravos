$("#ver-formulario").validate({

  submitHandler: function(form){
    var data = {};

    var idformulario = document.getElementById("idformulario").value.trim();
    
    var preguntas = [];

    $(".card-preguntas").each( function( index ){

      var idpregunta = $(this).attr("data-id-pregunta");
      var valor = $("input[type=radio]:checked", this).val();
      if(valor == undefined){
        valor = $("input[type=text]", this).val();
      }

      preguntas[index] = { idpregunta: idpregunta, valor: valor};
    });

    data.preguntas = preguntas;

    ajax('formularios/agregar_respuesta/'+idformulario,data,function(response) {

      if (response.res != $EXIT_SUCCESS) {
        text = response.msg;
        heading = "Alerta!";
        icon = "error";
        return mensaje_toast(heading, text, icon);
      }

      Swal.fire(response.msg)
      .then(() => {
        location.href=response.url
      });

    });
  }
});