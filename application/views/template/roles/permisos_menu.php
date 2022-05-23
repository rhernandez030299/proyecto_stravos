<div id="permisosMenuModal" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_permisos_menu" class="form" onsubmit="return false">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Permisos Menú</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="jstree_menu">
                        <?php foreach($arbol_permisos_menu as $permisos_menu) { 
                            echo $permisos_menu;  
                        } ?> 
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="rol_id_permiso_menu" value="" />
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" id="permisos_button_menu" class="btn btn-success btn-flat" value="Guardar" />
                </div>
            </div>
        </form>
    </div>
</div>