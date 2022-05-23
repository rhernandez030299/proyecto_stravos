<div id="userModalChangePassword" class="modal fade">
    <div class="modal-dialog">
        <form action="" method="POST" id="form_change_password">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cambiar contraseña</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">            
          
                    <div class="form-group">
                        <label for="">Contraseña (*)</label>
                        <input type="password" autocomplete="false" name="password" id="change_password" class="form-control password" placeholder="Contraseña">
                        
                    </div>
                    <div class="form-group">
                        <label for="">Repetir Contraseña (*)</label>
                        <input type="password" autocomplete="false" name="change_password_repit" id="change_password_repit" class="form-control password_repit" placeholder="Contraseña">
                        <input type="hidden" name="id_usuario_change" id="id_usuario_change" class="id_usuario_change" value="">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="cerrar" class="btn btn-default btn-flat" data-dismiss="modal">Cerrar</button>
                    <input type="submit" name="change_password_button" id="change_password_button" class="btn btn-primary change_password_submit btn-flat" value="Cambiar contraseña" />
                </div>
            </div>
        </form>
    </div>
</div>