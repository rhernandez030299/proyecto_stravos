<?php 
    $CI =&get_instance(); 
    
    if(! empty($CI->session->USER_ROL) ) {
        $arbol_permisos_menu_header = arbol_permisos_menu_lista(FALSE, FALSE , $CI);
    }

    function arbol_permisos_menu_lista($padre = 0, $user_tree_array = '', $CI) 
    {

        $data_arbol = array("padre"=>$padre);
        $consultar_permisos = $CI->Menus_model->consultar_by_data($data_arbol);

        if ( ! empty($consultar_permisos)) 
        {
            if($padre != 0) {
                $user_tree_array[] = "<div class='menu-submenu '><i class='menu-arrow'></i><ul class='menu-subnav'>";
            }

            foreach ($consultar_permisos as $row) {
                
                $validador = FALSE;
                $permisos_menu = $CI->Roles_model->has_permission_menu($CI->session->USER_ROL, $row->idmenu);
                if( ! empty($permisos_menu)){
                    $validador = TRUE;
                }

                if($padre == 0) {

                    $permisos_listar = $CI->Roles_model->has_permission_menu_by_parent( $CI->session->USER_ROL, $row->idmenu );

                    if( ! empty($permisos_listar)){
                        $validador = TRUE;
                    }
                }

                if( ! empty($validador)) {

                    $data_arbol = array("padre"=>$row->idmenu);
                    $consultar_conteo = count($CI->Menus_model->consultar_by_data($data_arbol));

                    $classAdd = "";
                    $classAddLink = "";
                    $attributeAdd  = "";
                    $span_adicional = "";
                    if($row->padre == 0) {
                        if($consultar_conteo >= 1) {
                            $classAdd = "menu-item-submenu";
                            $attributeAdd = "aria-haspopup='true' data-menu-toggle='hover'";
                            $classAddLink = "menu-toggle";
                        }
                    }

                    if($consultar_conteo >= 1) {
                        $span_adicional = '<i class="menu-arrow"></i>';
                    }

                    $icono = '<i class="menu-bullet menu-bullet-dot"><span></span></i>';
                    if(!empty($row->icono)){
                        $icono = $row->icono;
                    }

                    $user_tree_array[] = "<li class='menu-item " .  $classAdd . " " . $row->clase . "' aria-haspopup='true' ".$attributeAdd.">";
                    $user_tree_array[] = "<a href='" . base_url($row->ruta) . "' class='menu-link ".$classAddLink."' >
                    <span class='svg-icon menu-icon'>" . $icono . "</span>
                    <span class='menu-text'>" . $row->nombre . " </span> " . $span_adicional . "</a>";
                    $user_tree_array = arbol_permisos_menu_lista($row->idmenu, $user_tree_array, $CI);
                    $user_tree_array[] = "</li>" ;
                }
            }

            if($padre != 0) {
                $user_tree_array[] = "</ul></div>";
            } 
        }
        return $user_tree_array;
    }
?>

<!--begin::Aside-->
<div class="aside aside-left  aside-fixed  d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto " id="kt_brand">
        <!--begin::Logo-->
        <a href="#" class="brand-logo">
            <?= $this->session->USER_ROL_NAME  ?>
        </a>
        <!--end::Logo-->

        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24"/>
                    <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "/>
                    <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "/>
                </g>
            </svg><!--end::Svg Icon--></span>
            </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->

    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4 " data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav ">
                
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <h4 class="menu-text text-primary">MENÚ NAVEGACIÓN</h4>
                    </a>
                </li>

                <?php 
                    if ( ! empty($arbol_permisos_menu_header)) {
                        foreach($arbol_permisos_menu_header as $menu) { 
                              echo $menu;  
                        }
                    } 
                ?> 
          
            </ul>  
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>
<!--end::Aside-->

