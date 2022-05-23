
$(".graficas-menu").addClass("menu-item-active");

if($IDPROYECTO != ''){
  crear_fases($IDPROYECTO);
  crear_historia($IDPROYECTO);
  $('#proyecto').val($IDPROYECTO).trigger('change');
}

$("#proyecto").on('change',function(){
  crear_fases($(this).val());
  crear_historia($(this).val());
})

$("#fases").on('change',function(){
  crear_historia( $("#proyecto").val(), $(this).val());
})

function crear_fases($idproyecto){
  $("#fases").empty();
  $("#fases").append('<option value="0">Seleccione las fases</option>'); 
  var data = {};
  data.idproyecto = $idproyecto
  ajax('fases/obtener_fases_by_proyecto',data,function(response){  
    if(response.res == $EXIT_ERROR){
      text = response.msg;
      heading = "Alerta!";
      icon = "error";
      return mensaje_toast(heading,text,icon);
    }
    console.log(response);
    for (var i = 0; i < response.consultar_fase.length; i++) {
        $("#fases").append('<option value="'+response.consultar_fase[i].idfase+'">'+response.consultar_fase[i].nombre+'</option>');
    }
  });
}


function crear_historia($idproyecto, $idfase){

  var data = {};
  data.idproyecto = $idproyecto;
  data.idfase = $idfase;
  ajax('graficas/obtener_graficas',data,function(response){

    var chart = Highcharts.chart('container_estado_usuario', {
        chart: {
          type: 'bar',
        },
        title: {
          text: '<strong>ESTADO HISTORIAS DE USUARIO</strong>'
        },
        subtitle: {
          text: 'Project'
        },
        xAxis: {
          categories: response.nombres,
          crosshair: true,
        },
        yAxis: {
          allowDecimals: false,
          min: 0,
          title: {
            text: 'Cantidad'
          },
        },
        tooltip: {
          headerFormat: '<span style="font-size:18px"></span><table>',
            pointFormat: '<tr><td style="font-size:15px; color:{series.color};padding:0">{series.name}: </td>' +
              '<td style="padding:0"><b>&nbsp;{point.y} </b></td></tr>',
            footerFormat: '</table>',
          shared: true,
          useHTML: true
        },

        options: {
            scales: {
        yAxis: [{
                  categoryPercentage: 10,
                  barPercentage: 10
              }]
          }
        },
     
        series: []
    });

    chart.addSeries({
      name: "Finalizada",
      data: response.data[0],
      color: "#00a65a",
    });

    chart.addSeries({
      name: "Pendiente",
      data: response.data[1],
      color: "#FFEB3B",
    });

    chart.addSeries({
      name: "Incompleta",
      data: response.data[2],
      color: "#f39c12",
    });
  });
}

