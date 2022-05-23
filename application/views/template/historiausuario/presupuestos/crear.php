<div id="presupuestoModalCrear" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_presupuesto" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Presupuesto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="descripcion">Descripción (*)</label>
                        <input type= "text" name="descripcion" id="descripcion" class="form-control required" placeholder="Descripción">
                    </div>

                    <div class="form-group">
                        <label for="cantidad">Cantidad (*)</label>
                        <input type= "text" name="cantidad" id="cantidad" class="form-control number" placeholder="Cantidad">
                    </div> 

                    <div class="form-group">
                        <label for="valor_unidad">Valor/Unidad (*)</label>
                        <input type= "text" name="valor_unidad" id="valor_unidad" class="form-control number" placeholder="Valor/Unidad">
                    </div> 

                    <div class="form-group">
                        <label for="idresponsable">Responsable (*)</label>
                        <select name="idresponsable" id="idresponsable" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione el responsable</option>
                            <?php foreach ($consultar_miembro as $miembro) { ?>
                                <option <?php echo ( $miembro->idusuario == $this->session->UID ) ? 'selected' : '' ?>  value="<?= $miembro->idusuario ?>"><?= $miembro->nombre . " " . $miembro->apellido ?></option>
                            <?php } ?>  
                        </select>   
                    </div>

                    <div class="form-group">
                        <label for="idcategoria">Categoría (*)</label>
                        <select name="idcategoria" id="idcategoria" class="form-control select2 required" style="width: 100%;">
                            <option value="">Seleccione la categoria</option>
                            <?php foreach ($consultar_categoria as $categoria) { ?>
                                <option value="<?= $categoria->idcategoria ?>"><?= $categoria->nombre ?></option>
                            <?php } ?>  
                        </select>   
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="historia_id_presupuesto" id="historia_id_presupuesto" />
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="action" id="action" class="btn btn-primary btn-flat" value="Agregar presupuesto" />
                </div>
            </div>
        </form>
    </div>
</div>
<br><br>