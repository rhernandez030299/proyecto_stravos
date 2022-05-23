<?php $CI =&get_instance(); ?>


<div class="subheader py-2 py-lg-4 subheader-solid " id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			<!--begin::Title-->
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Miembros</h5>
			<!--end::Title-->
			<!--begin::Separator-->
			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
			<!--end::Separator-->
			<!--begin::Search Form-->
			<div class="d-flex align-items-center" id="kt_subheader_search">
				<span class="text-dark-50 font-weight-bold" id="kt_subheader_total"><?php echo count($consultar_miembro) ?> Total</span>
			</div>
			<!--end::Search Form-->
		</div>
		<!--end::Details-->
		<!--begin::Toolbar-->
		<div class="d-flex align-items-center">
			<!--begin::Button-->
			<a href="<?php echo base_url('proyectos'); ?>" class="btn btn-light-primary font-weight-bold ml-2">Regresar</a>
			<!--end::Button-->

		</div>
		<!--end::Toolbar-->
	</div>
</div>



<div class="row mt-10">

	<?php foreach ($consultar_miembro as $miembro): ?>
	<?php 
		$ruta_imagen = "";
		$nombre = "";
		$created_at = "";
		$id  = "";
		$estado = "Inactivo";
		$consultar_usuario = $CI->Usuarios_model->consultar_by_id($miembro->idusuario);
		if( ! empty($consultar_usuario)){
			$nombre = $consultar_usuario->nombre . " " . $consultar_usuario->apellido;
			$id = $consultar_usuario->idusuario;
			$ruta_imagen = $consultar_usuario->ruta_imagen;
			$fecha_ingreso = formato_fecha(date('Y-m-d',strtotime($consultar_usuario->created_at)));
			$created_at = $fecha_ingreso["dia"] . " de " . $fecha_ingreso["mes"] . " de " . $fecha_ingreso["ano"];	
			
			if($consultar_usuario->estado == USUARIO_ACTIVO){
				$estado = "Activo";
			}
		}
	?>	
	
	<a href="<?php echo base_url('usuarios/perfil/'.$id); ?>">
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<!--begin::Card-->
			
			<div class="card card-custom gutter-b card-stretch">
				<!--begin::Body-->
				<div class="card-body pt-4">

					<!--begin::User-->
					<div class="d-flex align-items-end mb-7">
						<!--begin::Pic-->
						<div class="d-flex align-items-center">
							<!--begin::Pic-->
							<div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
								<div class="symbol symbol-circle symbol-lg-75">
									<img src="<?php echo ( ! empty($ruta_imagen)) ? base_url('uploads/'.$ruta_imagen) : $pub_url . 'img/imagen3.jpg'; ?>" alt="image">
								</div>
								<div class="symbol symbol-lg-75 symbol-circle symbol-primary d-none">
									<span class="font-size-h3 font-weight-boldest">JM</span>
								</div>
							</div>
							<!--end::Pic-->
							<!--begin::Title-->
							<div class="d-flex flex-column">
								<a href="<?php echo base_url('usuarios/perfil/'.$id); ?>" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0"><?php echo $nombre ?></a>
								<span class="label font-weight-bold label-lg label-light-success label-inline" style="max-width: max-content;"><?php echo $estado ?></span>
							</div>
							<!--end::Title-->
						</div>
						<!--end::Title-->
					</div>
					<!--end::User-->
					<!--begin::Info-->
					<div class="mb-7">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-dark-75 font-weight-bolder mr-2">Email:</span>
							<a href="#" class="text-muted text-hover-primary"><?php echo $consultar_usuario->correo ?></a>
						</div>
						<div class="d-flex justify-content-between align-items-cente my-1">
							<span class="text-dark-75 font-weight-bolder mr-2">Usuario:</span>
							<a href="#" class="text-muted text-hover-primary"><?php echo $consultar_usuario->nombre_usuario ?></a>
						</div>
					</div>
				
				</div>
				<!--end::Body-->
			</div>
			<!--end::Card-->
			
		</div>
	</a>

	<?php endforeach ?>
</div>



<!-- <div class="col-md-3">
		
		</div>
		<div class="col-md-5">
			<div class="box box-danger">
				<div class="box-header with-border">
				  <h3 class="box-title">Lista de miembros</h3>
	
					<div class="box-tools pull-right">
						<span class="label label-danger"><?php echo count($consultar_miembro); ?> Miembros</span>
					</div>
				</div>
				<div class="box-body no-padding">
					<ul class="users-list clearfix">
						
	
							<li>
								<img style="width: 100px; height: 100px" src="<?php echo ( ! empty($ruta_imagen)) ? base_url('uploads/'.$ruta_imagen) : $pub_url . 'img/imagen3.jpg'; ?>" alt="User Image">
								
								  <span class="users-list-date"><?php echo $created_at ?></span>
							</li>
	
						
					</ul>
				</div>
			</div>	
		</div>
		
	</div>
	 -->