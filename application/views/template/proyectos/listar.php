<div class="content d-flex flex-column flex-column-fluid">

<div class="subheader py-2 py-lg-4 subheader-solid " id="subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<!--begin::Details-->
		<div class="d-flex align-items-center flex-wrap mr-2">
			<!--begin::Title-->
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5 ">Proyectos</h5>
			<!--end::Title-->
			<!--begin::Separator-->
			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
			<!--end::Separator-->
			<!--begin::Search Form-->
			<div class="d-flex align-items-center" id="subheader_search">
				<span class="text-dark-50 font-weight-bold" id="subheader_total">Total</span>
				<form class="ml-5">
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
				</form>
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
		<ul class="nav nav-pills" id="myTab1" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="proyectos-activos" data-toggle="tab" href="#proyectos-activos-1">
					<span class="nav-icon">
						<i class="flaticon2-check-mark"></i>
					</span>
					<span class="nav-text">Activos</span>
				</a>
			</li>
			<li class="nav-item nav-success-project">
				<a class="nav-link " id="proyectos-inactivos-1" data-toggle="tab" href="#proyectos-vencidos" aria-controls="profile">
					<span class="nav-icon">
						<i class="flaticon-close"></i>
					</span>
					<span class="nav-text">Vencidos</span>
				</a>
			</li>
		</ul>
		<div class="tab-content mt-5" id="myTabContent1">
			<div class="tab-pane fade active show" id="proyectos-activos-1" role="tabpanel" aria-labelledby="proyectos-activos">

				<div class="row" id="contador-elementos-activos"></div>

				<div class="d-flex justify-content-between align-items-center flex-wrap" id="paginador-elementos-activos">
					<div class="d-flex flex-wrap mr-3">
						<button href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 primero">
							<i class="ki ki-bold-double-arrow-back icon-xs"></i>
						</button>
						<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 anterior">
							<i class="ki ki-bold-arrow-back icon-xs"></i>
						</a>

						<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 siguiente">
							<i class="ki ki-bold-arrow-next icon-xs"></i>
						</a>
						<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 ultimo">
							<i class="ki ki-bold-double-arrow-next icon-xs"></i>
						</a>
					</div>
					<div class="d-flex align-items-center">
						<select id="length-filter" class="form-control form-control-sm text-primary font-weight-bold mr-4 border-0 bg-light-primary" style="width: 75px;">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<span class="text-muted display-text">Displaying 10 of 230 records</span>
					</div>
				</div>

			</div>
			<div class="tab-pane fade" id="proyectos-vencidos" role="tabpanel" aria-labelledby="proyectos-inactivos-1">

				<div class="row" id="contador-elementos-inactivos"></div>

				<div class="d-flex justify-content-between align-items-center flex-wrap" id="paginador-elementos-inactivos">
					<div class="d-flex flex-wrap mr-3">
						<button href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 primero">
							<i class="ki ki-bold-double-arrow-back icon-xs"></i>
						</button>
						<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 anterior">
							<i class="ki ki-bold-arrow-back icon-xs"></i>
						</a>

						<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 siguiente">
							<i class="ki ki-bold-arrow-next icon-xs"></i>
						</a>
						<a href="#" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1 ultimo">
							<i class="ki ki-bold-double-arrow-next icon-xs"></i>
						</a>
					</div>
					<div class="d-flex align-items-center">
						<select id="length-filter-inactivo" class="form-control form-control-sm text-primary font-weight-bold mr-4 border-0 bg-light-primary" style="width: 75px;">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<span class="text-muted display-text">Displaying 10 of 230 records</span>
					</div>
				</div>

			</div>
		</div>

	</div>

</div>

</div>

<?php include('crear.php'); ?>
<?php include('eliminar.php'); ?>
<?php include('modificar.php') ?>
<?php include('presupuesto.php') ?>