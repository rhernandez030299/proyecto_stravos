<div id="historiaUsuarioModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Historia de usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="numeracion_update">Numeración (*)</label>
                        <input type= "text" name="numeracion_update" id="numeracion_update" class="form-control required" placeholder="Numeración">
                    </div>

                    <div class="form-group">
                        <label for="titulo_update">Titulo (*)</label>
                        <input type= "text" name="titulo_update" id="titulo_update" class="form-control required" placeholder="Titulo">
                    </div> 
                    <div class="form-group">
                        <label for="fecha_ini_update">Fecha (*) </label>
                        <input type="text" name="fecha_ini_update" id="fecha_ini_update" class="form-control required" placeholder="Fecha inicio">
                    </div>    
        
                    <div class="form-group">
                        <label for="objetivo_update">Objetivo (*) </label>
                        <input type="text" name="objetivo_update" id="objetivo_update" class="form-control required" placeholder="Objectivo">
                    </div> 

                    <div class="form-group">
                        <label for="prioridad_update">Prioridad (*)</label>
                        <select name="prioridad_update" id="prioridad_update" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione la prioridad</option>
                            <?php foreach ($consultar_prioridad as $prioridad) { ?>
                                <option value="<?= $prioridad->idprioridad ?>"><?= $prioridad->nombre ?></option>
                            <?php } ?>  
                        </select>   
                    </div>
        
                    <div class="form-group">
                        <label for="riesgodesarrollo_update">Riesgo de desarrollo (*) </label>
                        <select name="riesgodesarrollo_update" id="riesgodesarrollo_update" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione el riesgo de desarollo</option>
                            <?php foreach ($consultar_riesgo_desarrollo as $riesgo_desarrollo) { ?>
                                <option value="<?= $riesgo_desarrollo->idriesgo_desarrollo ?>"><?= $riesgo_desarrollo->nombre ?></option>
                            <?php } ?>  
                        </select>                        
                    </div> 
            
                    <div class="form-group">
                        <label for="descripcion_update">Descripcion (*)</label>
                        <textarea name="descripcion_update" id="descripcion_update" class="form-control required" placeholder="Descripcion"></textarea>
                    </div>  

                    <div class="form-group">
                        <label>Adjuntar evidencia</label>   
                        <div class="dropzone" id="archivos_update">
                            <div class="dz-message needsclick">
                                <i class="fas fa-cloud-download-alt fa-5x" aria-hidden="true"></i>
                                <h3 class="text-file text-center"> Haga clic aquí o suelte los archivos</h3>
                            </div>
                        </div>
                    </div>
                        
           
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="historia_id_update" id="historia_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>