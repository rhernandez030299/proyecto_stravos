<div id="modulosModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Modulo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
    
                    <div class="form-group">
                        <label for="nombre">Nombre (*) </label>
                        <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Nombre del Modulo">
                    </div>                 
            
                    <div class="form-group">
                        <label for="descripcion">Descripción (*) </label>
                        <div>
                            <textarea name="descripcion" id="descripcion" class="form-control required" style="width: 100%"></textarea>
                        </div>                            
                    </div>                 
      
                </div>
                <div class="modal-footer">
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="action" id="action" class="btn btn-primary btn-flat" value="Agregar Modulo" />
                </div>
            </div>
        </form>
    </div>
</div>
<br><br>