<div id="menusModal" class="modal fade">
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
                            <label for="nombre">Nombre (*) </label>
                            <input type="text" name="nombre" id="nombre" class="form-control required" placeholder="Nombre del menú">                                                        
                        </div>

                        <div class="form-group">
                            <label for="clase">Clase (*) </label>
                            <input type="text" name="clase" id="clase" class="form-control required" placeholder="Clases del menú">
                        </div>

                        <div class="form-group">
                            <label for="icono">Icono (*) </label>
                            <input type="text" name="icono" id="icono" class="form-control required" placeholder="Nombre del icono">
                        </div>
                                            
                        <div class="form-group">
                            <label for="padre">Padre </label>
                            <select name="padre" id="padre" class="form-control select2" style="width: 100%;" >
                                <option value="0" selected>Seleccione el padre</option>
                                <?php foreach ($consultar_menus as $p) { ?>
                                <option value="<?= $p->idmenu ?>"><?= $p->ruta . " - " . $p->nombre ?> </option>
                                <?php } ?>
                            </select>
                        </div>                        
                </div>
                <div class="modal-footer">
                    <button type="button" name="cerrar" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="action" id="action" class="btn btn-primary font-weight-bold" value="Agregar" />
                </div>
            </div>
        </form>
    </div>
</div>
<br><br>