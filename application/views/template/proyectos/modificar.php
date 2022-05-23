<div id="proyectosModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Proyecto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                
                    <div class="form-group">
                        <label for="nombre_update">Nombre (*)</label>
                        <input type="text" name="nombre_update" id="nombre_update" class="form-control required" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label for="subtitulo_update">Subtítulo</label>
                        <input type="text" name="subtitulo_update" id="subtitulo_update" class="form-control" placeholder="Subtítulo ">
                    </div>

                    <div class="form-group">
                        <label for="descripcion_update">Descripción</label>
                        <textarea name="descripcion_update" id="descripcion_update" class="form-control" placeholder="Descripción "></textarea>
                    </div>

                    <div class="form-group ">
                        <label class="col-form-label text-lg-right text-left">Foto</label>
                        <br>
                        <div class="image-input image-input-empty image-input-outline" id="foto_update" style="background-image: url('<?php echo base_url("assets/public/img/imagen3.jpg"); ?>')">
                            <div class="image-input-wrapper"></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar perfil">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="imagen_update" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="imagen_update_remove" />
                            </label>
                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancelar perfil">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove perfil">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="fecha_inicio_update">Fecha (*) </label>
                        <input type="text" name="fecha_inicio_update" id="fecha_inicio_update" class="form-control required" placeholder="Fecha Inicio">
                    </div>

                    <div class="form-group">
                        <label for="">Añadir otros profesores</label>
                        <select name="profesor_update" id="profesor_update" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_profesor as $profesor) { ?>
                                <option value="<?= $profesor->idusuario ?>"><?= $profesor->correo ?> (<?= $profesor->nombre ?>)</option>
                            <?php } ?>  
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Añadir estudiantes</label>
                        <select name="usuarios_update" id="usuarios_update" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_estudiante as $estudiante) { ?>
                                <option value="<?= $estudiante->idusuario ?>"><?= $estudiante->correo ?> (<?= $estudiante->nombre ?>)</option>
                            <?php } ?>  
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Adjuntar archivos</label>   
                        <div class="dropzone" id="archivos_update">
                            <div class="dz-message needsclick">
                                <i class="fas fa-cloud-download-alt fa-5x" aria-hidden="true"></i>
                                <h3 class="text-file text-center"> Haga clic aquí o suelte las imagenes</h3>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Proyecto base</label>
                        <div class="radio-inline">
                            <label class="radio">
                                <input type="radio" name="proyecto_base" value="0" checked="">
                                <span></span>
                                No
                            </label>
                            <label class="radio">
                                <input type="radio" name="proyecto_base" value="1">
                                <span></span>
                                Sí
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="proyectos_id_update" id="proyectos_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>