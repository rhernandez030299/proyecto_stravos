<div class="card card-custom">
	<div class="card-header flex-wrap py-5">
		<div class="card-title">
			<h3 class="card-label">
                Grupos   
				<span class="d-block text-muted pt-2 font-size-sm">Lista de grupos registrados</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <?php echo $botones ?>
            <?php include('crear.php'); ?>
		</div>
       
	</div>
	<div class="card-body">
        <div class="dataTables_wrapper dt-bootstrap4 no-footer">

            <table id="grupos_table" class="table table-separate table-checkable dataTable no-footer dtr-inline" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cantidad de personas</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            </table>
  
        </div>
	</div>
</div>


<?php include('modificar.php') ?>
<?php include('eliminar.php') ?>

