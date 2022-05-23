<div class="subheader py-2 py-lg-4 subheader-solid ">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Modificar Formulario</h5>
			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
		</div>
		<div class="d-flex align-items-center">
			<a href="<?php echo base_url('formularios'); ?>" name="regresar" id="regresar" class="btn btn-light-primary btn-flat btn btn-shadow font-weight-bold mr-2" title="Regresar" data-tooltip="tooltip"><i class=""></i>Regresar</a>

            <button name="update_formulario" id="update_formulario" class="btn btn-success btn-flat btn btn-shadow font-weight-bold mr-2" title="Crear formulario" data-tooltip="tooltip"><i class=""></i>Modificar formulario</button>   
        </div>
	</div>
</div>


<form id="actualizar-formulario">
    <div class="row justify-content-center mt-10">

    <div class="col-9 col-xs-10 col-sm-10  col-md-8 col-lg-6">

            <div class="card gutter-b">
                <div class="card-header">
                    <h4>Participantes</h4>  
                </div>
                <div class="card-body">
                    <select name="usuarios" id="usuarios" multiple="multiple" class="form-control select2" style="width: 100%;">
                        <?php foreach ($consultar_estudiante as $estudiante) { ?>
                            <option <?php echo ( ! empty($estudiante->formulario) ) ? 'selected' : '' ?> value="<?= $estudiante->idusuario ?>"><?= $estudiante->correo ?> (<?= $estudiante->nombre ?>)</option>
                        <?php } ?>  
                    </select>
                </div>
            </div>


            <div class="card gutter-b card-container-formulario card-active">
                
                <button type="button" class="btn btn-primary boton-agregar-pregunta <?php echo (count($preguntas) == 0) ? 'd-none' : ''; ?>" title="Agregar pregunta">
                    <i class="fa fa-plus"></i>
                </button>
                <div class="card-body">

                    <input type="hidden" name="idformulario" id="idformulario" value="<?php echo $consultar_formulario->idformulario; ?>">   

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Ingrese el nombre" value="<?php echo $consultar_formulario->nombre; ?>">                    
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese la descripción" value="<?php echo $consultar_formulario->descripcion; ?>">                    
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select class="form-control required" id="estado" name="estado">
                            <option value="">Seleccione el estado</option>
                            <option value="1" <?php echo ($consultar_formulario->estado == 1) ? 'selected' : ''; ?>>Públicado</option>
                            <option value="2" <?php echo ($consultar_formulario->estado == 2) ? 'selected' : ''; ?>>Programado</option>
                            <option value="3" <?php echo ($consultar_formulario->estado == 3) ? 'selected' : ''; ?>>No públicado</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 <?php echo ($consultar_formulario->estado == 2) ? '' : 'd-none'; ?>">
                            <div class="form-group ">
                                <label>Fecha inicio</label>
                                <input type="text" autocomplete="off" name="fecha_inicio" id="fecha_inicio" class="form-control"  placeholder="Ingrese la fecha inicio"/ value="<?php echo $consultar_formulario->fecha_inicio; ?>">
                            </div>
                        </div>
                        <div class="col-6 <?php echo ($consultar_formulario->estado == 2) ? '' : 'd-none'; ?>">
                            <div class="form-group">
                                <label>Fecha fin</label>
                                <input type="text" autocomplete="off" name="fecha_final" id="fecha_final" class="form-control"  placeholder="Ingrese la fecha final" value="<?php echo $consultar_formulario->fecha_final; ?>"/>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            
            <div class="container-card-pregunta">
                <div class="card-pregunta"></div>

                <?php $contador_opciones = 1; $key = 0 ?> 

                <?php foreach( $preguntas as $key => $pregunta ){ ?>

                    <div class="card gutter-b container-pregunta-<?php echo $key ?>" id="container-pregunta-<?php echo $key ?>" data-id-pregunta="<?php echo $key ?>">
                        <button type="button" class="btn btn-primary boton-agregar-pregunta d-none" title="Agregar pregunta">
                            <i class="fa fa-plus"></i>
                        </button>
                    
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pregunta</label>
                                        <input type="text" id="nombre-pregunta-<?php echo $key ?>" name="nombre-pregunta-<?php echo $key ?>" class="form-control required" value="<?php echo $pregunta["nombre"]; ?>" placeholder="Ingrese el nombre">                    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo-pregunta">Tipo de pregunta</label>
                                        <select name="tipo-pregunta-<?php echo $key ?>" id="tipo-pregunta-<?php echo $key ?>" class="form-control tipo-pregunta required" style="width: 100%;">
                                            <option value="" >Seleccione el tipo de pregunta</option>
                                            <option value="1"  <?php echo ($pregunta["tipo_pregunta"] == 1) ? 'selected' : ''; ?>>Varias opciones</option>
                                            <option value="2" <?php echo ($pregunta["tipo_pregunta"] == "2") ? 'selected' : ''; ?>>Parrafo</option>
                                        </select>
                                    </div>  
                                </div>
                            </div>

                            <div class="container-opciones"> 

                                <?php if($pregunta["tipo_pregunta"] == 1){  ?>
                                    <?php foreach( $pregunta["opciones"] as $key2 => $opciones ){ ?>
                                        <div class="row d-none">
                                            <div class="col-md-6 col-11">
                                                <div class="form-group">
                                                    <label>Opción</label>
                                                    <input type="text" name="input-opcion-<?php echo $contador_opciones ?>" class="form-control required" placeholder="Ingrese el valor de la opción" value="<?php echo $opciones->nombre; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-1">
                                                <label for="" class="col-form-label"></label>
                                                <button class="btn btn-default boton-eliminar-opcion">
                                                    <i class="flaticon2-cross "></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php $contador_opciones++; ?>
                                    <?php } ?> 
                                <?php }else{ ?> 
                                    <div class="form-group d-none">
                                        <label>Opciones</label><br>
                                        <h5>Campo de texto</h5>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="container-button-agregar-opcion">
                                
                                <?php if($pregunta["tipo_pregunta"] == 1){  ?>
                                    <button type="button" class="btn btn-primary boton-agregar-opcion d-none">
                                        <i class="fa fa-plus"></i> Agregar opción
                                    </button>
                                <?php } ?> 
                            </div>
                        </div>
                        <div class="card-footer d-none">
                            
                            <div class="radio-inline justify-content-end">
                                <button type="button" class="btn btn-danger flaticon-delete boton-eliminar-pregunta"></button>
                                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 ml-5 bg-gray-200 border-gray-200"></div>
                                <label for="" class="col-form-label mr-5">Obligatorio</label>
                                <label class="radio">
                                <input type="radio" name="obligatorio-<?php echo $key ?>" value="1" <?php echo ($pregunta["requerido"] == 1) ? 'checked' : ''; ?>>
                                <span></span>Si</label>
                                <label class="radio">
                                <input type="radio" name="obligatorio-<?php echo $key ?>" value="0" <?php echo ($pregunta["requerido"] == 0) ? 'checked' : ''; ?>>
                                <span></span>No</label>
                            </div>                
                            
                        </div>
                    </div>

                <?php } ?>

                <?php if (count($preguntas) == 0) { ?>
                    <div class="" style="background-color: #fff">
                        <div class="card-body">
                            <h4>No es posible modificar las preguntas, porque ya tiene respuestas registradas</h4>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</form>

<script>
  var contador_pregunta = <?php echo $key ?>;
  var contador_opciones = <?php echo $contador_opciones ?>;

</script>