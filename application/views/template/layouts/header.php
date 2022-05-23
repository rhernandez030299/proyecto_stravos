
<!--begin::Header-->
<div id="kt_header" class="header  header-fixed ">
    <!--begin::Container-->
    <div class=" container-fluid  d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            <!--begin::Header Menu-->
            <div id="kt_header_menu" class="header-menu header-menu-mobile  header-menu-layout-default ">
            </div>
            <!--end::Header Menu-->
        </div>
        <!--end::Header Menu Wrapper-->

        <!--begin::Topbar-->
        <div class="topbar">
        
            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary" id="kt_quick_panel_toggle">
                    <span class="svg-icon svg-icon-xl svg-icon-primary">
                        <i class="flaticon2-bell-4 "></i>
                    </span>
                    <span class="h-6px w-6px position-absolute  top-0 start-100 right-0"><?php echo count($consultar_notificaciones); ?></span>
                    <span class="pulse-ring"></span>
                </div>
            </div>

        
            <!--begin::User-->
            <div class="topbar-item">
                <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hola,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?= $this->session->USER_NAME  ?></span>
                    <span class="symbol symbol-35 symbol-light-success"><span class="symbol-label font-size-h5 font-weight-bold">
                    <?= substr($this->session->USER_NAME, 0, 1)  ?></span>
                    </span>
                </div>
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->