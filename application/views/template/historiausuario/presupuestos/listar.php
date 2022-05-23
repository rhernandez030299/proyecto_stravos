<div id="presupuestoModalListar" class="modal fade ">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Listar presupuesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body">
          <div class="d-flex mb-5 justify-content-end">
            <button type="button" name="crear_presupuesto_boton" id="crear_presupuesto_boton" class="btn btn-primary btn-flat btn btn-shadow font-weight-bold" title="Nuevo" data-tooltip="tooltip"><i class=""></i>Crear presupuesto</button>
          </div>
        
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <table id="presupuesto_table" class="table table-separate table-checkable dataTable no-footer dtr-inline" style="width:100%">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Valor unidad</th>
                        <th>Total</th>
                        <th>Categoría</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
<br><br>