<?php if( ! empty($estado) ) { ?>

  <div class="card gutter-b">
    <div class="card-body text-center">
        <h1><?php echo $estado ?></h1>
    </div>
  </div>

<?php } else { ?>

  <form id="ver-formulario">
    <div class="row justify-content-center">

    <div class="col-9 col-xs-10 col-sm-10  col-md-8 col-lg-6">

            <div class="card gutter-b">
                <div class="card-header">
                    <input type="hidden" name="idformulario" id="idformulario" value="<?php echo $consultar_formulario->idformulario; ?>">
                    <h1><?php echo $consultar_formulario->nombre; ?></h1> 
                    <p>
                      <?php echo $consultar_formulario->descripcion; ?>
                    </p>
                </div>
            </div>

            <div class="container-card-pregunta">
                <?php foreach( $preguntas as $key => $pregunta ){ ?>

                    <div class="card gutter-b card-preguntas" id="container-pregunta-<?php echo $pregunta["idpregunta"] ?>" data-id-pregunta="<?php echo $pregunta["idpregunta"] ?>">
          
                        <div class="card-body">
                            <h5 class="mb-3">
                              <?php echo $pregunta["nombre"]; ?>
                            </h5>
                            <div class="container-opciones">
                                <?php if($pregunta["tipo_pregunta"] == 1){  ?>
                                    <div class="radio-list mb-2">
                                      <?php foreach( $pregunta["opciones"] as $key2 => $opciones ){ ?>
                                          <label class="radio">
                                            <input type="radio" name="radios-<?php echo $key ?>" <?php echo ($pregunta["requerido"] == 1) ? 'required' : ''; ?> value="<?php echo $opciones->idopcion; ?>">
                                            <span></span><?php echo $opciones->nombre; ?>
                                          </label>
                                      <?php } ?> 
                                    </div>
                                <?php }else{ ?> 
                                  <div class="form-group">
                                    <input type="text" class="form-control <?php echo ($pregunta["requerido"] == 1) ? 'required' : ''; ?>" placeholder="Ingrese el texto">
                                  </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="card gutter-b">
              <div class="card-body text-right">
                  <button class="btn btn-primary" type="submit">Enviar formulario</button>
              </div>
            </div>

        </div>
    </div>
  </form>
<?php } ?>
