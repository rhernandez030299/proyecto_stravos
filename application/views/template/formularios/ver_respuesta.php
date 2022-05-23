<div class="row justify-content-center">

  <div class="col-9 col-xs-10 col-sm-10  col-md-8 col-lg-6">

        <div class="card gutter-b">
            <div class="card-header">
                <h4><?php echo $conteo_participantes ?> respuestas</h4>
                <select name="usuarios" id="usuarios" class="form-control select2" style="width: 100%;">
                  <option value="">Todos los usuarios</option>
                    <?php foreach ($consultar_participantes as $participantes) { ?>
                        <option value="<?= $participantes->idparticipante ?>"><?= $participantes->correo ?> (<?= $participantes->nombre ?>)</option>
                    <?php } ?>  
                </select>
            </div>
        </div>

        <div class="card gutter-b">
            <div class="card-header">                
                <h1><?php echo $consultar_formulario->nombre; ?></h1> 
                <p>
                  <?php echo $consultar_formulario->descripcion; ?>
                </p>
            </div>
        </div>

        <div class="container-card-pregunta">
            
        </div>
    </div>
</div>

<script>
  var $idformulario = <?php echo $consultar_formulario->idformulario ?> 
</script>
