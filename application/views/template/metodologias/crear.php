<div id="metodologiasModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Metodología</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre (*) </label>
                        <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Nombre">                        
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