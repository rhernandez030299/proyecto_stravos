<div id="permisosModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_ingresar" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Permiso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                        <div class="form-group">
                            <label for="ruta">Ruta (*)</label>
                            <input type="text" name="ruta" id="ruta" class="form-control required" placeholder="Ruta">
                        </div>
                        <div class="form-group">
                            <label for="alias">Alias </label>
                            <input type="text" name="alias" id="alias" class="form-control" placeholder="Alias de la ruta"> 
                        </div>
                        <div class="form-group">
                            <label for="estado">Público (*)</label>
                            <select name="estado" id="estado" class="form-control select2" style="width: 100%;" >
                                <option value="1" selected>Público</option>
                                <option value="2">Privado</option>  
                                <option value="3">Protegido</option>                                
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="padre">Padre </label>
                            <select name="padre" id="padre" class="form-control select2" style="width: 100%;" >
                                <option value="0" selected>Seleccione el padre</option>
                                <?php foreach ($consultar_permisos as $p) { ?>
                                <option value="<?= $p->idpermiso_arbol ?>"><?= $p->ruta ?></option>
                                <?php } ?>
                            </select>
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