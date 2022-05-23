<div id="historiaUsuarioModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Historia de usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="numeracion">Numeración (*)</label>
                        <input type= "text" name="numeracion" id="numeracion" class="form-control required" placeholder="Numeración">
                    </div>

                    <div class="form-group">
                        <label for="titulo">Título (*)</label>
                        <input type= "text" name="titulo" id="titulo" class="form-control required" placeholder="Título">
                    </div> 
                    <div class="form-group">
                        <label for="fecha_ini">Fecha (*) </label>
                        <input type="text" name="fecha_ini" id="fecha_ini" class="form-control required" placeholder="Fecha inicio">       
                    </div>
        
                    <div class="form-group">
                        <label for="objetivo">Objetivo (*) </label>
                        <input type="text" name="objetivo" id="objetivo" class="form-control required" placeholder="Objectivo">                            
                    </div>

                    <div class="form-group">
                        <label for="responsable">Responsable (*)</label>
                        <select name="responsable" id="responsable" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione el responsable</option>
                            <?php foreach ($consultar_miembro as $miembro) { ?>
                                <option <?php echo ( $miembro->idusuario == $this->session->UID ) ? 'selected' : '' ?>  value="<?= $miembro->idusuario ?>"><?= $miembro->nombre . " " . $miembro->apellido ?></option>
                            <?php } ?>  
                        </select>   
                    </div>

                    <div class="form-group">
                        <label for="prioridad">Prioridad (*)</label>
                        <select name="prioridad" id="prioridad" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione la prioridad</option>
                            <?php foreach ($consultar_prioridad as $prioridad) { ?>
                                <option value="<?= $prioridad->idprioridad ?>"><?= $prioridad->nombre ?></option>
                            <?php } ?>  
                        </select>   
                    </div>
    
                    <div class="form-group">
                        <label for="riesgodesarrollo">Riesgo de desarrollo (*) </label>
                        <select name="riesgodesarrollo" id="riesgodesarrollo" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione el riesgo de desarollo</option>
                            <?php foreach ($consultar_riesgo_desarrollo as $riesgo_desarrollo) { ?>
                                <option value="<?= $riesgo_desarrollo->idriesgo_desarrollo ?>"><?= $riesgo_desarrollo->nombre ?></option>
                            <?php } ?>  
                        </select>   
                                                    
                    </div> 
            
                    <div class="form-group">
                        <label for="descripcion">Descripcion (*)</label>
                        <textarea name="descripcion" id="descripcion" class="form-control required" placeholder="Descripcion" style="max-width: 100%"></textarea>
                    </div>  

                    <div class="form-group">
                        <label>Adjuntar evidencia</label>   
                        <div class="dropzone" id="archivos">
                            <div class="dz-message needsclick">
                                <i class="fas fa-cloud-download-alt fa-5x" aria-hidden="true"></i>
                                <h3 class="text-file text-center"> Haga clic aquí o suelte los archivos</h3>
                            </div>
                        </div>
                    </div>
     
                </div>
                <div class="modal-footer">
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="action" id="action" class="btn btn-primary btn-flat" value="Agregar Historia de usuario" />
                </div>
            </div>
        </form>
    </div>
</div>
<br><br>