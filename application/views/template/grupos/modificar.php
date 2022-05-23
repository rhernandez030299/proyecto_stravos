<div id="gruposModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Grupos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
  
                    <div class="form-group">
                        <label for="nombre_update">Nombre (*) </label>
                        <input type="text" name="nombre_update" id="nombre_update" class="form-control required" placeholder="Nombre del grupo">
                    </div>

                    <div class="form-group">
                        <label for="usuarios_update">Estudiantes (*)</label>
                        <select name="usuarios_update" id="usuarios_update" multiple="multiple" class="form-control select2 required" style="width: 100%;">
                            <?php foreach ($consultar_usuario as $usuario) { ?>
                                <option value="<?= $usuario->idusuario ?>"><?= $usuario->correo ?> (<?= $usuario->nombre ?>)</option>
                            <?php } ?>  
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="grupos_id_update" id="grupos_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>