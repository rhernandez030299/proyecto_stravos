<div id="permisosModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Permisos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ruta_update">Ruta (*)</label>
                        <input type="text" name="ruta_update" id="ruta_update" class="form-control required" placeholder="Ruta">
                    </div>
                    <div class="form-group">
                        <label for="alias_update">Alias </label>
                        <input type="text" name="alias_update" id="alias_update" class="form-control" placeholder="Alias de la ruta">
                    </div>
                    <div class="form-group">
                        <label for="estado_update">Público (*)</label>
                        <select name="estado_update" id="estado_update" class="form-control select2" style="width: 100%;" >
                            <option value="1" selected>Público</option>
                            <option value="2">Privado</option>  
                            <option value="3">Protegido</option>                                
                        </select>
                    </div>                        
                    <div class="form-group">
                        <label for="padre_update">Padre </label>
                        <select name="padre_update" id="padre_update" class="form-control select2" style="width: 100%;" >
                            <option value="0">Seleccione el padre</option>
                            <?php foreach ($consultar_permisos as $p) { ?>
                            <option value="<?= $p->idpermiso_arbol ?>"><?= $p->ruta ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="permisos_id_update" id="permisos_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>