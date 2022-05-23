<div id="menusModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_update" class="form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Menú</h4>
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
                            <label for="nombre_update">Nombre (*) </label>
                            <input type="text" name="nombre_update" id="nombre_update" class="form-control required" placeholder="Nombre del menú">
                        </div>

                        <div class="form-group">
                            <label for="clase_update">Clase (*) </label>
                            <input type="text" name="clase_update" id="clase_update" class="form-control required" placeholder="Clases del menú">
                        </div>

                        <div class="form-group">
                            <label for="icono_update">Icono (*) </label>
                            <input type="text" name="icono_update" id="icono_update" class="form-control required" placeholder="Nombre del icono">
                        </div>
                                            
                        <div class="form-group">
                            <label for="padre_update">Padre </label>
                            <select name="padre_update" id="padre_update" class="form-control select2" style="width: 100%;" >
                                <option value="0" selected>Seleccione el padre</option>
                                <?php foreach ($consultar_menus as $p) { ?>
                                <option value="<?= $p->idmenu ?>"><?= $p->ruta . " - " . $p->nombre ?> </option>
                                <?php } ?>
                            </select>
                        </div>                        
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="menus_id_update" id="menus_id_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>