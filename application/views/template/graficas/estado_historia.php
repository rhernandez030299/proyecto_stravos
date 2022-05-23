
<div class="card card-custom">
	<div class="card-header flex-wrap py-5">
		<div class="card-title">
			<h3 class="card-label">
                Estados historia de usuario
				<span class="d-block text-muted pt-2 font-size-sm">Graficas estados historia de usuarios registrados</span>
            </h3>
        </div>

	</div>
	<div class="card-body">
        <div class="mb-15">
            <div class="row mb-6">
                <div class="col-lg-4 mb-lg-0 mb-6">
                    <label>Proyecto:</label>
                    <select name="proyecto" id="proyecto" class="form-control select2 required" style="width: 100%;">
                        <option value="">Seleccione el proyecto</option>
                        <?php foreach ($consultar_proyecto as $proyecto) { ?>
                            <option value="<?= $proyecto->idproyecto ?>"><?= $proyecto->nombre ?>
                            </option>
                        <?php } ?>  
                    </select>   
                </div>
                <div class="col-lg-4 mb-lg-0 mb-6">
                    <label>Fases:</label>
                    <select name="fases" id="fases" class="form-control select2 required" style="width: 100%;">
                        <option value="">Seleccione las fases</option>
                    </select>   
                </div>
            </div>
        </div>

        <div id="container_estado_usuario" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	</div>
</div>

