
<div class="subheader py-2 py-lg-4 subheader-solid ">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Historias</h5>

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
					<a href="<?php echo base_url('proyectos/modulos/'.$url_proyecto.'/'. $url_metodologia.'/'. $url_fase); ?>" class="text-muted"><?php echo $nombre_fase; ?></a>
				</li>
				<li class="breadcrumb-item">
					<a href="#" class="text-muted"><?php echo $nombre_modulo; ?></a>
				</li>
			</ul>
		</div>
		<div class="d-flex align-items-center">
		<?php echo $botones; ?>
		</div>
	</div>
</div>

<div class="example-preview mt-10">
  <ul class="nav nav-pills" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">
        <span class="nav-icon">
          <i class="flaticon2-layers-1"></i>
        </span>
        <span class="nav-text">Listado</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="calendar-tab" data-toggle="tab" href="#nav-calendar" aria-controls="calendar">
        <span class="nav-icon">
          <i class="flaticon-calendar-1"></i>
        </span>
        <span class="nav-text">Calendario</span>
      </a>
    </li>
	<li class="nav-item ml-auto">
      <a class="nav-link" data-toggle="tab" aria-controls="calendar">
        <span class="nav-text texto-presupuesto"><strong>$ 0 </strong></span>
      </a>
    </li>
  </ul>
  <div class="tab-content mt-5" id="myTabContent">
    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
	  <div class="row" id="contador-elementos"></div>
    </div>
    <div class="tab-pane fade in" id="nav-calendar" role="tabpanel" aria-labelledby="calendar-tab">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<h3 class="card-label">Calendario</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="calendar"></div>
			</div>
		</div>

    </div>
    
  </div>
</div>

<?php include('crear.php') ?>
<?php include('modificar.php') ?>
<?php include('eliminar.php') ?>
<?php include('entregada.php') ?>
<?php include('estado.php') ?>
<?php include('finalizar_modulo.php') ?>
<?php include('abrir_modulo.php') ?>
<?php include('presupuestos/crear.php') ?>
<?php include('presupuestos/listar.php') ?>
<?php include('presupuestos/eliminar.php') ?>
<?php include('presupuestos/modificar.php') ?>