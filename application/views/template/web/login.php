<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="login">
				<!--begin::Aside-->
				<div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #F2C98A;">
					<!--begin::Aside Top-->
					<div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
						<!--begin::Aside header-->
						
						<!--end::Aside header-->
						<!--begin::Aside title-->
						<h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #986923;">Descubre el mundo de Stravos
						<br />con excelentes herramientas de construcción</h3>
						<!--end::Aside title-->
					</div>
					<!--end::Aside Top-->
					<!--begin::Aside Bottom-->
					<div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url('<?php echo base_url('assets/template/img/login-visual-1.svg') ?>'"></div>
					<!--end::Aside Bottom-->
				</div>
				<!--begin::Aside-->
				<!--begin::Content-->
				<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
					<!--begin::Content body-->
					<div class="d-flex flex-column-fluid flex-center">
						<!--begin::Signin-->
						<div class="login-form in-login-signin">
							<!--begin::Form-->
							<form class="form" id="form_login" onsubmit="return false;">
								<!--begin::Title-->
								<div class="pb-13 pt-lg-0 pt-5">
									<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Bienvenido a Stravos</h3>
									<span class="text-muted font-weight-bold font-size-h4">¿Eres nuevo? 
									<a href="javascript:;" id="login_signup" class="text-primary font-weight-bolder">Crea una cuenta</a></span>
								</div>
								<!--begin::Title-->
								<!--begin::Form group-->
								<div class="form-group">
									<label class="font-size-h6 font-weight-bolder text-dark">Usuario</label>
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg required" type="text" name="txt_usuario" id="txt_usuario" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<div class="d-flex justify-content-between mt-n5">
										<label class="font-size-h6 font-weight-bolder text-dark pt-5">Contraseña</label>
									
									</div>
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg required" type="password" name="txt_pass" id="txt_pass" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Action-->
								<div class="pb-lg-0 pb-5">
									<button type="button" id="btnLogin" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Ingresar</button>
								</div>
								<!--end::Action-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Signin-->
						<!--begin::Signup-->
						<div class="login-form in-login-signup hide">
							<!--begin::Form-->
							<form class="form" id="form_ingresar" onsubmit="return false">
								<!--begin::Title-->
								<div class="pb-13 pt-lg-0 pt-5">
									<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Regístrate</h3>
									<p class="text-muted font-weight-bold font-size-h4">Ingrese sus datos para crear su cuenta</p>
								</div>
								<!--end::Title-->
								<!--begin::Form group-->
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 required" type="text" placeholder="Nombre" name="nombre" id="nombre" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 required" type="text" placeholder="Apellido" name="apellido" id="apellido" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 required" type="text" placeholder="Usuario" name="user" id="user" autocomplete="off" />
								</div>
								<!--end::Form group-->
                <!--begin::Form group-->
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 email" type="email" placeholder="Correo electrónico" name="email" id="email" autocomplete="off" />
								</div>
								<!--end::Form group-->
								<!--begin::Form group-->
								<div class="form-group">
									<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 password" type="password" placeholder="Contraseña" name="password" id="password" autocomplete="off" />
								</div>
								<!--end::Form group-->
		
								<!--begin::Form group-->
								<div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
									<button type="submit" id="login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Registrar</button>
									<button type="button" id="login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Volver</button>
								</div>
								<!--end::Form group-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Signup-->
					</div>
					<!--end::Content body-->
				
				</div>
				<!--end::Content-->
			</div>
			<!--end::Login-->
		</div>