
$(document).ready(function() {

  $(".formularios-menu").addClass("menu-item-active");

  function remover_botones(){
    $(".container-opciones > div, .container-button-agregar-opcion > button").addClass("d-none");
    $(".container-card-pregunta > .card > .boton-agregar-pregunta, .container-card-pregunta > .card > .card-footer, .card-container-formulario > .boton-agregar-pregunta").addClass("d-none");

    $(".container-card-pregunta > .card, .card-container-formulario ").removeClass("card-active");
  }

  $('#fecha_inicio, #fecha_final').datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    formatDate: 'Y-m-d',
    minDate: true,
    scroll: false,
  });

  $(document).on('change', '#estado', function(e) {
    var value = $(this).val();
    $("#fecha_inicio, #fecha_final").val("");
    $("#fecha_inicio, #fecha_final").parent().parent().addClass("d-none");
    if (value == 2) {
        $("#fecha_inicio, #fecha_final").parent().parent().removeClass("d-none");
    }
  });

  function agregar_pregunta(contador_pregunta, card_principal = "card-pregunta") {

    var opciones_tipo_pregunta = '<option value="" selected>Seleccione el tipo de pregunta</option>';

    for (let i = 0; i < $consultar_tipo_pregunta.length; i++) {
      opciones_tipo_pregunta += '<option value="'+$consultar_tipo_pregunta[i].idtipo_pregunta+'" >'+$consultar_tipo_pregunta[i].nombre+'</option>';
    }

    $("."+card_principal).after(`
      <div class="card gutter-b example example-compact container-pregunta-`+contador_pregunta+`" id="container-pregunta-`+contador_pregunta+`" data-id-pregunta="`+contador_pregunta+`">
        <button type="button" class="btn btn-primary boton-agregar-pregunta d-none" title="Agregar pregunta">
            <i class="fa fa-plus"></i>
        </button>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pregunta</label>
                        <input type="text" id="nombre-pregunta-`+contador_pregunta+`" name="nombre-pregunta-`+contador_pregunta+`" class="form-control required" placeholder="Ingrese el nombre">                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo-pregunta">Tipo de pregunta</label>
                        <select name="tipo-pregunta-`+contador_pregunta+`" id="tipo-pregunta-`+contador_pregunta+`" class="form-control tipo-pregunta required" style="width: 100%;" >
                            `+opciones_tipo_pregunta+`
                        </select>
                    </div>  
                </div>
            </div>

            <div class="container-opciones"> 
              
            </div>

            <div class="container-button-agregar-opcion">
                
            </div>
        </div>
        <div class="card-footer d-none">
            
            <div class="radio-inline justify-content-end">
                <button type="button" class="btn btn-danger flaticon-delete boton-eliminar-pregunta"></button>
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 ml-5 bg-gray-200 border-gray-200"></div>
                <label for="" class="col-form-label mr-5">Obligatorio</label>
                <label class="radio">
                <input type="radio" name="obligatorio-`+contador_pregunta+`" value=1>
                <span></span>Si</label>
                <label class="radio">
                <input type="radio" name="obligatorio-`+contador_pregunta+`" value=0 checked>
                <span></span>No</label>
            </div>                
            
        </div>
      </div>
    `);

    $("input[name='nombre-pregunta-"+contador_pregunta+"']").focus();

    remover_botones();
    $(".container-pregunta-"+contador_pregunta).addClass("card-active");
    $(".container-pregunta-"+contador_pregunta + " > .boton-agregar-pregunta, .container-pregunta-"+contador_pregunta + " > .card-footer").removeClass("d-none");

    $(".container-pregunta-"+contador_pregunta + " .card-body > .container-opciones, .container-pregunta-"+contador_pregunta+" .container-button-agregar-opcion").removeClass("d-none");
  }

  
  $(document).on('click', '.boton-agregar-pregunta', function(e) {

    e.preventDefault();
    e.stopPropagation();
    contador_pregunta++;

    var idpregunta = $(this).parent().attr("data-id-pregunta");
    var card_principal = "";

    if( idpregunta == undefined) {
      card_principal = "card-pregunta";
    }else{
      card_principal = "container-pregunta-"+$(this).parent().attr("data-id-pregunta");
    }
    
    agregar_pregunta(contador_pregunta, card_principal);
  });

  $(document).on('click', '.boton-eliminar-pregunta', function(e) {
    $(this).parent().parent().parent().remove()
  });

  $(document).on('click', '.container-card-pregunta > .card, .card-container-formulario', function(e) {
    remover_botones();
    $(this).addClass("card-active");
    $( ".boton-agregar-pregunta, .card-footer", this).removeClass("d-none");
    $( ".card-body > .container-opciones > div, .container-button-agregar-opcion > button", this).removeClass("d-none");
  });

  function agregar_opcion(that){
    contador_opciones++;

    $(".container-opciones", that).append(`
        <div class="row">
            <div class="col-md-6 col-11">
                <div class="form-group">
                    <label>Opci??n</label>
                    <input type="text" name="input-opcion-`+contador_opciones+`" class="form-control required" placeholder="Ingrese el valor de la opci??n">
                </div>
            </div>
            <div class="col-md-1 col-1">
                <label for="" class="col-form-label"></label>
                <button class="btn btn-default boton-eliminar-opcion">
                    <i class="flaticon2-cross "></i>
                </button>
            </div>
        </div>
      `);

  }

  $(document).on('click', '.boton-agregar-opcion', function(e) {
    var that = $(this).parent().parent();
    agregar_opcion(that);
  });

  $(document).on('click', '.boton-eliminar-opcion', function(e) {
    $(this).parent().parent().remove();
  });

  $(document).on('change', '.tipo-pregunta', function(e) {

    var that = $(this).parent().parent().parent().parent();

    $(".container-opciones > div, .container-button-agregar-opcion > button", that).remove();
  
    if ($(this).val() == 1){

      agregar_opcion(that);

      $(".container-button-agregar-opcion", that).append(`
        <button type="button" class="btn btn-primary boton-agregar-opcion">
          <i class="fa fa-plus"></i> Agregar opci??n
        </button>
      `);

    } else if ($(this).val() == 2) {
      $(".container-opciones", that).append(`
        <div class="form-group">
          <label>Opciones</label><br>
          <h5>Campo de texto</h5>
        </div>
      `);
    }
  });

  $("#actualizar-formulario").validate({
    rules: {
      fecha_inicio: {
          required: function(element) {
              return $("#estado").val() == 2;
          }
      },
      fecha_final: {
          required: function(element) {
              return $("#estado").val() == 2;
          }
      },
    },

    invalidHandler: function (event, validator) {
      
      for (let i = 0; i < validator.errorList.length; i++) {
        var idElement = validator.errorList[i].element;
        $(idElement).parent().parent().parent().click();
        break;
      }
    },

  });

  $(document).on('click', '#update_formulario', function(e) {

    var data = $("#actualizar-formulario").valid();
    if( data ){

      var data = {};
      var preguntas = [];
      
      data.nombre = $("#nombre").val().trim();
      data.idformulario = $("#idformulario").val().trim();
      data.descripcion = $("#descripcion").val().trim();
      data.estado = $("#estado").val().trim();
      data.fecha_inicio = $("#fecha_inicio").val().trim();
      data.fecha_final = $("#fecha_final").val().trim();

      $(".container-card-pregunta > .card").each( function( index ){

        var idpregunta = $(this).attr("data-id-pregunta");

        var opciones = [];

        $(".container-opciones input[type=text]", this).each( function( index ){
          opciones[index] = $(this).val();
        });

        preguntas[index] = {
          "nombre": $("#nombre-pregunta-"+idpregunta).val().trim(),
          "tipo-pregunta": $("#tipo-pregunta-"+idpregunta).val().trim(),
          "obligatorio": $("input[name='obligatorio-"+idpregunta+"']:checked").val(),
          "opciones": opciones
        }

      });

      data.pregunta = preguntas;

      var usuarios = [];
      contadorInterno = 0;
      $('#usuarios option:checked').each(function() {
        usuarios[contadorInterno] =  $(this).val();
        contadorInterno++;
      });

      data.usuarios = usuarios;

      ajax('formularios/actualizar', data, function(response) {
      
        heading = "!Actualizado!";
        icon = "success";
        text = response.msg;

        if (response.res != $EXIT_SUCCESS) {
          heading = "Alerta!";
          icon = "error";
          return mensaje_toast(heading, text, icon);
        }

        return mensaje_toast(heading, text, icon);
      });
    }
  });
});