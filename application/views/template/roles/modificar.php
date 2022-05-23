<div id="rolModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Rol</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">                    
                    
                    <div class="form-group">
                        <label for="rol_update">Rol (*)</label>
                        <input type="text" name="rol_update" id="rol_update" class="form-control required" placeholder="Ingresa el rol">
                    </div>
                        <div class="form-group">
                        <label for="ruta_update">Ruta Principal (*)</label>
                        <input type="text" name="ruta_update" id="ruta_update" class="form-control required" placeholder="Ruta">
                    </div>
                
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="rol_id_update" id="rol_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>