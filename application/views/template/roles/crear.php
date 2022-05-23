<div id="rolModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Crear Rol</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">                    
                
                    <div class="form-group">
                        <label for="">Rol (*)</label>
                        <input type="text" name="rol" id="rol" class="form-control required" placeholder="Ingresa el rol">
                    </div>
                    <div class="form-group">
                        <label for="ruta">Ruta Principal (*)</label>
                        <input type="text" name="ruta" id="ruta" class="form-control required" placeholder="Ruta">
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="action" class="btn btn-primary btn-flat" value="Agregar" />                    
                </div>
            </div>
        </form>
    </div>
</div>
<br><br>