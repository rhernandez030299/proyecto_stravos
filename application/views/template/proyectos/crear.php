<div id="proyectosModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Proyecto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="nombre">Nombre (*)</label>
                        <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label for="subtitulo">Subtítulo</label>
                        <input type="text" name="subtitulo" id="subtitulo" class="form-control" placeholder="Subtítulo">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción"></textarea>
                    </div>

                    <div class="form-group ">
                        <label class="col-form-label text-lg-right text-left">Foto</label>
                        <br>
                        <div class="image-input image-input-empty image-input-outline" id="foto" style="background-image: url('<?php echo base_url("assets/public/img/imagen3.jpg"); ?>')">
                            <div class="image-input-wrapper"></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar perfil">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="imagen" id="imagen" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="imagen_remove" />
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
                        <label for="fecha_inicio">Fecha (*) </label>
                        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control required" placeholder="Fecha Inicio">
                    </div>      

                    <div class="form-group">
                        <label for="">Metodología (*)</label>
                        <select name="metodologia" id="metodologia" class="form-control select2 required" style="width: 100%;">
                            <option value="" selected disabled>Seleccione la metodología</option>
                            <?php foreach ($consultar_metodologia as $metodologia) { ?>
                                <option value="<?= $metodologia->idmetodologia ?>"><?= $metodologia->nombre ?></option>
                            <?php } ?>  
                        </select>
                    </div>
                                        
                    <div class="form-group">
                        <label for="">Añadir otros profesores</label>
                        <select name="profesor" id="profesor" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_profesor as $profesor) { ?>
                                <option value="<?= $profesor->idusuario ?>"><?= $profesor->correo ?> (<?= $profesor->nombre ?>)</option>
                            <?php } ?>  
                        </select>
                    </div>
                                        
                    <div class="form-group">
                        <label for="">Grupos</label>
                        <select name="grupos" id="grupos" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_grupo as $grupo) { ?>
                                <option value="<?= $grupo->idgrupo ?>"><?= $grupo->nombre ?></option>
                            <?php } ?>  
                        </select>
                    </div>
                                        
                    <div class="form-group">
                        <label for="">Añadir otros estudiantes</label>
                        <select name="usuarios" id="usuarios" multiple="multiple" class="form-control select2" style="width: 100%;">
                            <?php foreach ($consultar_estudiante as $estudiante) { ?>
                                <option value="<?= $estudiante->idusuario ?>"><?= $estudiante->correo ?> (<?= $estudiante->nombre ?>)</option>
                            <?php } ?>  
                        </select>
                    </div>
                                        
                    <div class="form-group">
                        <label>Adjuntar archivos</label>   
                        <div class="dropzone" id="archivos">
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
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="action" id="action" class="btn btn-primary btn-flat" value="Agregar" />
                </div>
            </div>
        </form>
    </div>
</div>
<br><br>