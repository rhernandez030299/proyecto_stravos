<div class="subheader py-2 py-lg-4 subheader-solid ">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Modulos</h5>

			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>

			<div class="d-flex align-items-center" >
				<span class="text-dark-50 font-weight-bold" id="subheader_total">2 Total</span>
			</div>	

			<div class="subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>

			<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
				<li class="breadcrumb-item">
					<a href="<?php echo base_url('proyectos/listar') ?>" class="text-muted">Proyectos</a>
				</li>
				<li class="breadcrumb-item">
					<a href="<?php echo base_url('proyectos/listar') ?>" class="text-muted"><?php echo $nombre; ?></a>
				</li>
				<li class="breadcrumb-item">
					<a href="<?php echo base_url('proyectos/fases/'.$url_proyecto.'/'. $url_metodologia); ?>" class="text-muted"><?php echo $nombre_metodologia; ?></a>
				</li>
				<li class="breadcrumb-item">
					<a href="" class="text-muted"><?php echo $nombre_fase; ?></a>
				</li>
			</ul>
		</div>
		<div class="d-flex align-items-center">
		<?php echo $botones; ?>
		</div>
	</div>
</div>

<div class="row mt-10 justify-content-center" id="contador-elementos"></div>



<?php include('crear.php') ?>
<?php include('modificar.php') ?>
<?php include('eliminar.php') ?>