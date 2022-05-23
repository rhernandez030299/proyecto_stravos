<div class="subheader py-2 py-lg-4 subheader-solid ">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Crear Formulario</h5>
			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
		</div>
		<div class="d-flex align-items-center">
			<a href="<?php echo base_url('formularios'); ?>" name="regresar" id="regresar" class="btn btn-light-primary btn-flat btn btn-shadow font-weight-bold mr-2" title="Regresar" data-tooltip="tooltip"><i class=""></i>Regresar</a>

            <button name="add_formulario" id="add_formulario" class="btn btn-primary btn-flat btn btn-shadow font-weight-bold mr-2" title="Crear formulario" data-tooltip="tooltip"><i class=""></i>Crear formulario</button>   
        </div>
	</div>
</div>


<form id="crear-formulario">
    <div class="row justify-content-center mt-10">

        <div class="col-9 col-xs-10 col-sm-10  col-md-8 col-lg-6">

            <div class="card gutter-b">
                <div class="card-header">
                    <h4>Participantes</h4>  
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="checkbox-list">
                            <label class="checkbox">
                            <input type="checkbox" checked="checked" name="todos-usuarios-activos">
                            <span></span>Todos los estudiantes activos</label>
                        </div>
                    </div>
                    <div class="form-group d-none">
                        <label for="">Grupo de estudiantes</label>
                        <select name="grupos" id="grupos" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_grupo as $grupo) { ?>
                                <option value="<?= $grupo->idgrupo ?>"><?= $grupo->nombre ?></option>
                            <?php } ?>  
                        </select>
                    </div>
                                        
                    <div class="form-group d-none">
                        <label for="">Añadir otros estudiantes</label>
                        <select name="usuarios" id="usuarios" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_estudiante as $estudiante) { ?>
                                <option value="<?= $estudiante->idusuario ?>"><?= $estudiante->correo ?> (<?= $estudiante->nombre ?>)</option>
                            <?php } ?>  
                        </select>
                    </div>  

                    <?php if( ! empty($consultar_profesor)){ ?> 
                        <div class="form-group ">
                            <label for="">Añadir profesores</label>
                            <select name="profesores" id="profesores" multiple="multiple" class="form-control select2" style="width: 100%;">
                                <?php foreach ($consultar_profesor as $profesor) { ?>
                                    <option value="<?= $profesor->idusuario ?>"><?= $profesor->correo ?> (<?= $profesor->nombre ?>)</option>
                                <?php } ?>  
                            </select>
                        </div>  
                    <?php } ?> 
                     
                </div>
            </div>

            <div class="card gutter-b card-container-formulario">
                
                <button type="button" class="btn btn-primary boton-agregar-pregunta d-none" title="Agregar pregunta">
                    <i class="fa fa-plus"></i>
                </button>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Ingrese el nombre">                    
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese la descripción" >                    
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select class="form-control required" id="estado" name="estado">
                            <option value="">Seleccione el estado</option>
                            <option value="1">Públicado</option>
                            <option value="2">Programado</option>
                            <option value="3">No públicado</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 d-none">
                            <div class="form-group ">
                                <label>Fecha inicio</label>
                                <input type="text" autocomplete="off" name="fecha_inicio" id="fecha_inicio" class="form-control"  placeholder="Ingrese la fecha inicio"/>
                            </div>
                        </div>
                        <div class="col-6 d-none">
                            <div class="form-group">
                                <label>Fecha fin</label>
                                <input type="text" autocomplete="off" name="fecha_final" id="fecha_final" class="form-control"  placeholder="Ingrese la fecha final"/>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            
            <div class="container-card-pregunta">
                <div class="card-pregunta"></div>  
            </div>
        </div>
    </div>
</form>
