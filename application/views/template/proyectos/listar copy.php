<div class="content d-flex flex-column flex-column-fluid">


<div class="subheader py-2 py-lg-4 subheader-solid " id="subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			<!--begin::Title-->
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Proyectos</h5>
			<!--end::Title-->
			<!--begin::Separator-->
			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
			<!--end::Separator-->
			<!--begin::Search Form-->
			<div class="d-flex align-items-center" id="subheader_search">
				<span class="text-dark-50 font-weight-bold" id="subheader_total"><?php echo count($consultar_proyectos) ?> Total</span>
				<!-- <form class="ml-5">
					<div class="input-group input-group-sm input-group-solid" style="max-width: 175px">
						<input type="text" class="form-control" id="subheader_search_form" placeholder="Buscar...">
						<div class="input-group-append">
							<span class="input-group-text">
								<span class="svg-icon">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24"></rect>
											<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
											<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
										</g>
									</svg>
								</span>
							</span>
						</div>
					</div>
				</form> -->
			</div>
			<!--end::Search Form-->
		</div>
		<!--end::Details-->
		<!--begin::Toolbar-->
		<div class="d-flex align-items-center">
			<!--begin::Button-->
			<?php echo $botones ?>
            
			<!--end::Button-->

		</div>
		<!--end::Toolbar-->
	</div>
</div>

<div class="d-flex flex-column-fluid">

	<div class="container">

		<?php $CI =&get_instance(); ?>
		<div class="">

		<?php if (empty($consultar_proyectos)): ?>
			<div class="col-md-12 sin-proyecto text-center">
				No tienes proyectos asignados
			</div>
		<?php endif ?>


		<div class="row" id="contenedor-elementos">

		</div>

		<?php foreach ($consultar_proyectos as $proyecto): ?>
			<?php 
				$data_miembro_proyecto = array("idproyecto" => $proyecto->idproyecto);
				$consultar_miembro_profesor = $CI->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);
				
				$data_archivo = array("idproyecto" => $proyecto->idproyecto);
				$consultar_archivo = $CI->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);
				$consultar_metodologia = $CI->Proyectos_model->consultar_proyecto_metodologia_by_id_proyecto($proyecto->idproyecto);

				$nombre_metodologia = "";
				$url_metodologia = "";
				foreach ($consultar_metodologia as $metodologia){
					$nombre_metodologia = $metodologia->nombre;
					$url_metodologia = $metodologia->url;
				}

				$data_miembro_proyecto = array("idproyecto" => $proyecto->idproyecto);
				$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

				$consultar_modulo = $this->Proyectos_model->consultar_modulos_by_proyecto(FALSE, $proyecto->idproyecto);
				$consultar_modulo_finalizado = $this->Proyectos_model->consultar_modulos_by_proyecto(MODULO_FINALIZADO, $proyecto->idproyecto);
				$porcentaje = 0;

				if( ! empty($consultar_modulo) && ! empty($consultar_modulo_finalizado) && $consultar_modulo->contador_modulos > 0){
					$porcentaje = ceil(($consultar_modulo_finalizado->contador_modulos * 100) / $consultar_modulo->contador_modulos);
				}

				$date_inicio = formato_fecha($proyecto->fecha_inicio);
				$date_finalizacion = formato_fecha($proyecto->fecha_finalizacion);
					
				$date_inicio = $date_inicio["dia"] . " de " . $date_inicio["mes"] . " de " . $date_inicio["ano"];
				$date_finalizacion = $date_finalizacion["dia"] . " de " . $date_finalizacion["mes"] . " de " . $date_finalizacion["ano"];

			?>	

			<div class="col-xl-6">
				<!--begin::Card-->
				<div class="card card-custom gutter-b card-stretch">
					<!--begin::Body-->
					<div class="card-body">
						<!--begin::Section-->
						<div class="d-flex align-items-center">
							<!--begin::Pic-->

							<div class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle">
					
								
							</div>

							<div class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle">
								<?php if( ! empty($proyecto->ruta_imagen) ){ ?> 
									<img src="<?php echo base_url('uploads/'.$proyecto->ruta_imagen) ?>" alt="image">
								<?php }else{ ?>
									<div class="symbol symbol-lg-65 symbol-circle symbol-primary">
										<span class="symbol-label font-size-h3 font-weight-boldest"><?php echo strtoupper(substr($proyecto->nombre, 0, 1)); ?></span>
									</div>
								<?php } ?>
								
							</div>
							<!--end::Pic-->
							<!--begin::Info-->
							<div class="d-flex flex-column mr-auto">
								<!--begin: Title-->
								<a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1"><?php echo $proyecto->nombre; ?></a>
								<span class="text-muted font-weight-bold"><?php echo $proyecto->subtitulo; ?></span>
								<!--end::Title-->
							</div>
							<!--end::Info-->
						</div>
						<!--end::Section-->
						<!--begin::Content-->
						<div class="d-flex flex-wrap mt-14">
							<div class="mr-12 d-flex flex-column mb-7">
								<span class="d-block font-weight-bold mb-4">Fecha de inicio</span>
								<span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text"><?php echo $date_inicio ?></span>
							</div>
							<div class="mr-12 d-flex flex-column mb-7">
								<span class="d-block font-weight-bold mb-4">Fecha de vencimiento</span>
								<span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text"><?php echo $date_finalizacion ?></span>
							</div>
							<!--begin::Progress-->
							<div class="flex-row-fluid mb-7">
								<span class="d-block font-weight-bold mb-4">Progreso</span>
								<div class="d-flex align-items-center pt-2">
									<div class="progress progress-xs mt-2 mb-2 w-100">
										<div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $porcentaje ?>%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="ml-3 font-weight-bolder"><?php echo $porcentaje ?>%</span>
								</div>
							</div>
							<!--end::Progress-->
						</div>
						<!--end::Content-->
						<!--begin::Text-->
						<p class="mb-7 mt-3"><?php echo $proyecto->descripcion ?></p>
						<!--end::Text-->
						<!--begin::Blog-->
						<div class="d-flex flex-wrap">
							<!--begin: Item-->
							<div class="mr-12 d-flex flex-column mb-7">
								<span class="font-weight-bolder mb-4">Metodología</span>
								<span class="font-weight-bolder font-size-h5 pt-1">
								<span class="font-weight-bold text-dark-50"></span><?php echo $nombre_metodologia ?></span>
							</div>
							<!--end::Item-->
				
							<!--begin::Item-->
							<div class="d-flex flex-column flex-lg-fill float-left mb-10">
								<a class=" mb-4" href="<?php echo base_url('trabajos/miembros/'.$proyecto->url.'/'.$url_metodologia); ?>">
									<span class="font-weight-bolder">Miembros</span>
								</a>
								<div class="symbol-group symbol-hover">
									
									<?php 
										$contador = 0;
										foreach ($consultar_miembro as $miembro){
										$contador++;
										if($contador>5){
											break;
										}
										$ruta_imagen = "";
										$nombre = "";
										$created_at = "";
										$id  = "";
										$consultar_usuario = $CI->Usuarios_model->consultar_by_id($miembro->idusuario);
										if( ! empty($consultar_usuario)){
											$nombre = $consultar_usuario->nombre . " " . $consultar_usuario->apellido;
											$id = $consultar_usuario->idusuario;
											$ruta_imagen = $consultar_usuario->ruta_imagen;
										}
									?>

									<div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="<?php echo $nombre; ?>" data-original-title="<?php echo $nombre; ?>">
										<img alt="Pic" src="<?php echo ( ! empty($ruta_imagen)) ? base_url('uploads/'.$ruta_imagen) : $pub_url . 'img/imagen3.jpg'; ?>">
									</div>

									<?php } ?>

									<?php if(count($consultar_miembro) > 5){ ?> 
										<div class="symbol symbol-30 symbol-circle symbol-light">
											<span class="symbol-label font-weight-bold"><?php echo count($consultar_miembro) - 5 ?>+</span>
										</div>
									<?php } ?>
								</div>

							</div>
							<!--end::Item-->

							<!--begin::Item-->
							<div class="d-flex flex-column flex-lg-fill float-left mb-7">
								<span class="font-weight-bolder mb-4">Profesores</span>
								<div class="symbol-group symbol-hover">
		
									<?php 
										$contador = 0;
										foreach ($consultar_miembro_profesor as $miembro) {
										$contador++;
										if($contador>5){
											break;
										}
										$ruta_imagen = "";
										$nombre = "";
										$created_at = "";
										$id  = "";
										$consultar_usuario = $CI->Usuarios_model->consultar_by_id($miembro->idusuario);
										if( ! empty($consultar_usuario)){
											$nombre = $consultar_usuario->nombre . " " . $consultar_usuario->apellido;
											$id = $consultar_usuario->idusuario;
											$ruta_imagen = $consultar_usuario->ruta_imagen;
										}
									?>	

									<div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="<?php echo $nombre; ?>" data-original-title="<?php echo $nombre; ?>">
										<img alt="Pic" src="<?php echo ( ! empty($ruta_imagen)) ? base_url('uploads/'.$ruta_imagen) : $pub_url . 'img/imagen3.jpg'; ?>">
									</div>

									<?php } ?>

									<?php if(count($consultar_miembro_profesor) > 5){ ?> 
										<div class="symbol symbol-30 symbol-circle symbol-light">
											<span class="symbol-label font-weight-bold"><?php echo count($consultar_miembro_profesor) - 5 ?>+</span>
										</div>
									<?php } ?>
								</div>
							</div>
							<!--end::Item-->


						</div>
						<!--end::Blog-->
						<div class="ver-mas hide">
							<p>
								<strong>Archivos</strong>
							</p>
							<div class="row">
									
								<?php foreach ($consultar_archivo as $archivo): ?>
									<?php 
										$url = base_url() . 'uploads/' . $archivo->ruta;
										$ext = explode(".", $archivo->ruta);
										$ext = array_pop($ext);
										
										if ($ext == "pdf") {
											$url = base_url() .  "assets/public/img/pdf.jpg";
										} else if (strpos($ext, "doc") !== false) {
											$url = base_url() .  "assets/public/img/word.png";
										} else if (strpos($ext, "xls") !== false) {
											$url = base_url() .  "assets/public/img/excel.png";
										}
									?>

									<div class="col-md-6">
										<div class="card card-custom gutter-b card-stretch">
											<div class="card-body border-rgba">
												<div class="d-flex flex-column align-items-center">
													<img alt="" class="max-h-100px" src="<?php echo $url; ?>">
													<a href="<?php echo base_url('trabajos/descargas/'.$archivo->ruta); ?>" class="text-dark-75 font-weight-bold mt-15 font-size-lg word-break"><?php echo $archivo->client_name; ?></a>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach ?>
							</div>
						</div>

						
					</div>
					<!--end::Body-->
					<!--begin::Footer-->
					<div class="card-footer d-flex align-items-center">
						<a href="<?php echo base_url('trabajos/fases/'.$proyecto->url.'/'.$url_metodologia); ?>" class="btn btn-success btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 mr-2 ">Fases</a>
						<a href="<?php echo base_url('graficas/index/'.$proyecto->idproyecto); ?>" class="btn btn-success btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 mr-2 ">Gráficas</a>
						<button type="button" class="btn btn-primary btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 ml-sm-auto mr-2 ver-mas-boton">Detalles</button>
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Card-->
			</div>
							
		<?php endforeach ?>

		</div>
	</div>
</div>

</div>

<?php include('crear.php'); ?>
<?php include('modificar.php') ?>