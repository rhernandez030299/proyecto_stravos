
<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" enctype="multipart/form-data" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="rol_id">Roles (*)</label>
                        <select name="rol_id" id="rol_id" class="form-control select2 required" style="width: 100%;">
                            <option value="" selected disabled>Seleccione el rol</option>
                            <?php foreach ($roles as $rol) { ?>
                            <option value="<?= $rol->idrol ?>"><?= $rol->nombre ?></option>
                            <?php } ?>  
                        </select>
                    </div>

                    <div class="form-group">                                        
                        <label for="">Usuario (*)</label>
                        <input type="text" name="user" id="user" class="form-control required" placeholder="Ingresa el usuario">
                    </div>
    
                    <div class="form-group">                                        
                        <label for="">Correo (*)</label>
                        <input type="text" name="correo" id="correo" class="form-control email" placeholder="Ingresa el correo">
                    </div>
    
                    <div class="form-group">                                        
                        <label for="">Contraseña (*)</label>
                        <input type="password" autocomplete="false" name="password" id="password" class="form-control required" placeholder="Contraseña">
                    </div>
    
                    <div class="form-group">                                        
                        <label for="">Nombre (*)</label>
                        <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Ingresa el nombre">
                    </div>
    
                    <div class="form-group">                                        
                        <label for="">Apellido (*)</label>
                        <input type="text" name="apellido" id="apellido" class="form-control required" placeholder="Ingresa el apellido">
                    </div>
    
                    <div class="form-group ">
                        <label class="col-form-label text-lg-right text-left">Foto</label>
                        <br>
                        <div class="image-input image-input-empty image-input-outline" id="user_edit" style="background-image: url('<?php echo base_url("assets/public/img/imagen3.jpg"); ?>')">
                            <div class="image-input-wrapper"></div>
                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar perfil">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="imagen" accept=".png, .jpg, .jpeg" />
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