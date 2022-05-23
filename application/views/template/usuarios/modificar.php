<div id="userModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="rol_id_update">Roles (*)</label>
                        <select name="rol_id_update" id="rol_id_update" class="form-control select2 required" style="width: 100%;">
                            <option value="" selected disabled>Seleccione el rol</option>
                            <?php foreach ($roles as $rol) { ?>
                            <option value="<?= $rol->idrol ?>"><?= $rol->nombre ?></option>
                            <?php } ?>  
                        </select>
                    </div>
       
                    <div class="form-group"> 
                    <label for="">Usuario (*)</label>
                        <input type="text" name="user_update" id="user_update" class="form-control required" placeholder="Ingresa el usuario">
                    </div>
                
                    <div class="form-group"> 
                        <label for="">Correo (*)</label>
                        <input type="text" name="correo_update" id="correo_update" class="form-control email" placeholder="Ingresa el correo">
                    </div>
                
                    <div class="form-group"> 
                        <label for="">Nombre (*)</label>
                        <input type="text" name="nombre_update" id="nombre_update" class="form-control required" placeholder="Ingresa el nombre">
                    </div>
                
                    <div class="form-group"> 
                        <label for="">Apellido (*)</label>
                        <input type="text" name="apellido_update" id="apellido_update" class="form-control required" placeholder="Ingresa el apellido">
                    </div>
                    
                    <div class="form-group ">
                        <label class="col-form-label text-lg-right text-left">Foto</label>
                        <br>
                        <div class="image-input image-input-empty image-input-outline" id="user_edit_update" style="">
                            <div class="image-input-wrapper"></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar perfil">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="imagen" accept=".png, .jpg, .jpeg" />
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
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="usuario_id_update" id="usuario_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>