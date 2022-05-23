
obtener_preguntas();
function obtener_preguntas(idparticipante) {

  var data = {};

  data.idparticipante = idparticipante;

  ajax('formularios/obtener_respuesta/'+$idformulario, data,function(response) {

    if (response.res != $EXIT_SUCCESS) {
      text = response.msg;
      heading = "Alerta!";
      icon = "error";
      return mensaje_toast(heading, text, icon);
    }

    var data_respuesta = "";
    var preguntas = response.data;
    var opciones_grafica = [];
    var contador_opciones_grafica = 0;

    $(".container-card-pregunta div").remove();

    for (let i = 0; i < preguntas.length; i++) {

      var opciones = "";
      

      if(preguntas[i]["tipo_pregunta"] == 1){

        opciones = `
            <figure class="highcharts-figure">
              <div id="container-figure-${contador_opciones_grafica}"></div>            
            </figure>
        `

        opciones_grafica[contador_opciones_grafica] = preguntas[i]["opciones"];
        contador_opciones_grafica++;

      }else{

        for (let j = 0; j < preguntas[i]["valor"].length; j++) {
        
          opciones += `
            <div class="form-group">
              <div class="alert alert-custom alert-default" role="alert">
                <div class="alert-text">${preguntas[i]["valor"][j]["valor"]}</div>
              </div>
            </div>
          `;
        }

      }

      data_respuesta += `
        <div class="card gutter-b card-preguntas" id="container-pregunta-${preguntas[i]["idpregunta"]}" data-id-pregunta="${preguntas[i]["idpregunta"]}">
          <div class="card-body">
            <h5 class="mb-3">
              ${preguntas[i]["nombre"]}
            </h5>
            <div class="container-opciones">
              ${opciones}
            </div>
          </div>
        </div>
      `;

    }


    $(".container-card-pregunta").append(data_respuesta);

    for (let i = 0; i < opciones_grafica.length; i++) {
    
      var data = opciones_grafica[i]

      Highcharts.chart('container-figure-'+i, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },

        exporting: {
          enabled: false
        },
        series: [{
            name: 'Etiqueta',
            colorByPoint: true,
            data: data
        }]
      });
      
    }
   
  });

}

$(document).on('change', '#usuarios', function(e) {

  obtener_preguntas($(this).val());
  
});