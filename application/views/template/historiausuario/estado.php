<div id="historiaUsuarioModalestado" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_estado" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title titulo-estado-historia">Modificar Modulo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="observacion_estado">Observacion (*) </label>
                            <div>
                                <textarea name="observacion_estado" id="observacion_estado" class="form-control required" style="width: 100%"></textarea>
                            </div>                            
                        </div>                 
                     
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="historia_id_estado" id="historia_id_estado" />
                    <input type="hidden" name="estado_id" id="estado_id" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="estado" id="estado_button" class="btn btn-primary btn-flat" value="Enviar respuesta" />
                </div>
            </div>
        </form>
    </div>
</div>