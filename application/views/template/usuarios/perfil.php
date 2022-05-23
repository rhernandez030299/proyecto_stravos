<?php 
  $image =  ( ! empty($ruta_imagen)) ? base_url('uploads/'.$ruta_imagen) : '';
?>

<div class="card card-custom gutter-b">
									<!--Begin::Body-->
									<div class="card-body">
										<div class="d-flex">

                      <?php if( ! empty($image)){ ?> 
                      <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                        <div class="symbol-label" style="background-image:url('<?php echo $image; ?>')"></div>
                        <i class="symbol-badge bg-success"></i>
                      </div>
                      <?php }else{ ?> 
											<!--begin::Pic-->
											<div class="flex-shrink-0 mr-7">
												<div class="symbol symbol-50 symbol-lg-120 symbol-light-danger">
													<span class="font-size-h3 symbol-label font-weight-boldest"><?php echo substr($nombre, 0, 1) . substr($apellido, 0, 1); ?></span>
												</div>
											</div>
                      <?php } ?> 
											<!--end::Pic-->
											<!--begin: Info-->
											<div class="flex-grow-1">
												<!--begin::Title-->
												<div class="d-flex align-items-center justify-content-between flex-wrap">
													<!--begin::User-->
													<div class="mr-3">
														<div class="d-flex align-items-center mr-3">
															<!--begin::Name-->
															<a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3"><?php echo $nombre . " " . $apellido; ?></a>
															<!--end::Name-->
															<span class="label label-light-success label-inline font-weight-bolder mr-1"><?php echo $nombre_rol; ?></span>
														</div>
														<!--begin::Contacts-->
														<div class="d-flex flex-wrap my-2">
															<a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
															<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
																<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Mail-notification.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24"></rect>
																		<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000"></path>
																		<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"></circle>
																	</g>
																</svg>
																<!--end::Svg Icon-->
															</span><?php echo $email; ?></a>
															<a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
															<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
																<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/General/Lock.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<mask fill="white">
																			<use xlink:href="#path-1"></use>
																		</mask>
																		<g></g>
																		<path d="M7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 Z M12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L15,10 L15,8 C15,6.34314575 13.6568542,5 12,5 Z" fill="#000000"></path>
																	</g>
																</svg>
																<!--end::Svg Icon-->
															</span><?php echo $user; ?></a>
															<a href="#" class="text-muted text-hover-primary font-weight-bold">
															<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
																<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Map/Marker2.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24"></rect>
																		<path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"></path>
																	</g>
																</svg>
																<!--end::Svg Icon-->
															</span>
                              <span class="label label-light-success label-inline font-weight-bolder mr-1"><?php echo $estado; ?></span>
                              </a>
														</div>
														<!--end::Contacts-->
													</div>
													<!--begin::User-->
	
												</div>
												<!--end::Title-->
												<!--begin::Content-->
												<div class="d-flex align-items-center flex-wrap justify-content-between">
													<!--begin::Description-->
													<div class="flex-grow-1 text-muted-50 py-2 py-lg-2 mr-5">
                            Descripción del usuario
                          </div>
													<!--end::Description-->
												</div>
												<!--end::Content-->
											</div>
											<!--end::Info-->
										</div>
									</div>
									<!--end::Body-->
								</div>

<?php if ($id == $this->session->UID): ?>
<div class="card card-custom">
        <!--begin::Card header-->
        <div class="card-header card-header-tabs-line nav-tabs-line-3x">
            <!--begin::Toolbar-->
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                    <!--begin::Item-->
                    <li class="nav-item mr-3">
                        <a class="nav-link active" data-toggle="tab" href="#user_edit_tab_1">
                            <span class="nav-icon">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24" />
                                            <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                                            <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                                        </g>
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-text font-size-lg">Perfil</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mr-3">
                        <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_3">
                            <span class="nav-icon">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3" />
                                            <path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3" />
                                            <path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3" />
                                        </g>
                                    </svg>
                                </span>
                            </span>
                            <span class="nav-text font-size-lg">Cambiar contraseña</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    
                </ul>
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body px-0">
                <div class="tab-content">
                    <!--begin::Tab-->
                    <div class="tab-pane show active px-7" id="user_edit_tab_1" role="tabpanel">
                        <form id="form-change-datos" onsubmit="return false">
                        <!--begin::Row-->
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-7 my-2">
                                    
                                    <div class="row">
                                        <label class="col-3"></label>
                                        <div class="col-9">
                                            <h6 class="text-dark font-weight-bold mb-10">Información personal:</h6>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Foto</label>
                                        <div class="col-9">
                                            <div class="image-input image-input-empty image-input-outline" id="user_edit" style="background-image: url('<?php echo $image; ?>')">
                                                <div class="image-input-wrapper"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar perfil">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="imagen" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="imagen_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancelar perfil">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove perfil">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Nombre</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" type="text" name="nombre-profile-update" id="nombre-profile-update" value="<?php echo $this->session->USER_NAME ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-3 text-lg-right text-left">Apellido</label>
                                        <div class="col-9">
                                            <input class="form-control form-control-lg form-control-solid" type="text" name="apellido-profile-update" id="apellido-profile-update" value="<?php echo $this->session->USER_LAST_NAME ?>" />
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="card-footer pb-0">
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7">
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col-9">
                                                <button type="submit" class="btn btn-light-primary font-weight-bold">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::Tab-->
                    
                    <!--begin::Tab-->
                    <div class="tab-pane px-7" id="kt_user_edit_tab_3" role="tabpanel">
                        <form id="form-change-password-general" onsubmit="return false">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7">
                                       
                                        <!--begin::Row-->
                                        <div class="row">
                                            <label class="col-3"></label>
                                            <div class="col-9">
                                                <h6 class="text-dark font-weight-bold mb-10">Cambiar contraseña:</h6>
                                            </div>
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Contraseña actual</label>
                                            <div class="col-9">
                                                <input class="form-control form-control-lg form-control-solid mb-1 password" type="password" placeholder="Contraseña actual" name="change-password-general-actual" id="change-password-general-actual" />
                                            </div>
                                        </div>
                                        <!--end::Group-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Nueva contraseña</label>
                                            <div class="col-9">
                                                <input class="form-control form-control-lg form-control-solid password" type="password" placeholder="Nueva contraseña" name="change-password-general" id="change-password-general" />
                                            </div>
                                        </div>
                                        <!--end::Group-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Repetir contraseña</label>
                                            <div class="col-9">
                                                <input class="form-control form-control-lg form-control-solid password_repit" type="password" placeholder="Repetir contraseña" name="change-password-repit-general" id="change-password-repit-general" />
                                            </div>
                                        </div>
                                        <!--end::Group-->
                                    </div>
                                </div>

                                <!--end::Row-->
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer pb-0">
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7">
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col-9">
                                                <button type="submit" class="btn btn-light-primary font-weight-bold">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Footer-->
                        </form>
                    </div>
                    <!--end::Tab-->
                </div>
        </div>
        <!--begin::Card body-->
    </div>
    <!--end::Card-->
<?php endif ?>