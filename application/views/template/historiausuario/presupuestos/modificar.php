<div id="presupuestoModalUpdate" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_presupuesto_update" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar presupuesto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                <div class="form-group">
                        <label for="descripcion">Descripción (*)</label>
                        <input type= "text" name="descripcion_update" id="descripcion_update" class="form-control required" placeholder="Descripción">
                    </div>

                    <div class="form-group">
                        <label for="cantidad">Cantidad (*)</label>
                        <input type= "text" name="cantidad_update" id="cantidad_update" class="form-control number" placeholder="Cantidad">
                    </div> 

                    <div class="form-group">
                        <label for="valor_unidad">Valor/Unidad (*)</label>
                        <input type= "text" name="valor_unidad_update" id="valor_unidad_update" class="form-control number" placeholder="Valor/Unidad">
                    </div> 

                    <div class="form-group">
                        <label for="idresponsable_update">Responsable (*)</label>
                        <select name="idresponsable_update" id="idresponsable_update" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione el responsable</option>
                            <?php foreach ($consultar_miembro as $miembro) { ?>
                                <option <?php echo ( $miembro->idusuario == $this->session->UID ) ? 'selected' : '' ?>  value="<?= $miembro->idusuario ?>"><?= $miembro->nombre . " " . $miembro->apellido ?></option>
                            <?php } ?>  
                        </select>   
                    </div>

                    <div class="form-group">
                        <label for="idcategoria_update">Categoría (*)</label>
                        <select name="idcategoria_update" id="idcategoria_update" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione la categoria</option>
                            <?php foreach ($consultar_categoria as $categoria) { ?>
                                <option value="<?= $categoria->idcategoria ?>"><?= $categoria->nombre ?></option>
                            <?php } ?>  
                        </select>   
                    </div> 
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idpresupuesto_update" id="idpresupuesto_update" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="update" id="update_button" class="btn btn-success btn-flat" value="Modificar" />
                </div>
            </div>
        </form>
    </div>
</div>