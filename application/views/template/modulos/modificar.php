<div id="modulosModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Modulo</h4>
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
                        <label for="descripcion_update">Descripción (*) </label>
                        <div>
                            <textarea name="descripcion_update" id="descripcion_update" class="form-control required" style="width: 100%"></textarea>
                        </div>                            
                    </div>                 
                   
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="modulos_id_update" id="modulos_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>