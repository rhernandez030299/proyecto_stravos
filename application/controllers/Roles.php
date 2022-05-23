<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Roles_model");
        $this->load->model("Menus_model");        
        $this->load->model("Permisos_model");
    }

	//-----------------------------------------------------------------------------
    public function index(){    	
		redirect('roles/listar');
	}
	
	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de roles
	 * @return void
	 */
	public function listar(){
		$this->lm->add_js('roles.js');
		$this->lm->set_title('Roles');
		$this->lm->set_page('roles/listar');

		$this->lm->add_js('jquery-tree/jstree.min.js');	
		$this->lm->add_css('jquery-tree/style.css');

		$arbol_permisos = $this->arbol_permisos_lista();
		$arbol_permisos_menu = $this->arbol_permisos_menu_lista();

		$function = [
            [
            	"method" 		=> "crear", 
            	"name"			=> "add_button", 
        		"id"			=> "add_button", 
        		"classes" 		=> "btn btn-primary btn-flat", 
        		"title" 		=> "Nuevo", 
				"text"			=> "Nuevo",
				"icon"			=> "fa fa-plus",
        		"link" 			=> FALSE
    		]
        ];

        $botones = $this->btn->get_buttons($function);
		
		$data = array("arbol_permisos" => $arbol_permisos, "botones"=>$botones, "arbol_permisos_menu" => $arbol_permisos_menu);

		$this->lm->render($data);
	}

	/**
	 * Lista de permisos con formato
	 * @return json
	 */
	public function arbol_permisos_lista($padre = 0, $user_tree_array = array()) 
  	{
  		$data_arbol = array("padre"=>$padre);
		$consultar_permisos = $this->Permisos_model->consultar_by_data($data_arbol);
		  
	    if ( ! empty($consultar_permisos)) 
	    {
	      $user_tree_array[] = "<ul>";
	      foreach ($consultar_permisos as $row) {

	      	$validador_permisos = FALSE;

	      	if($padre == 0){
		      	$data_permisos = array("estado" => PERMISOS_ARBOL_PRIVADO, "padre" => $row->idpermiso_arbol);
		      	$consultar_padre = $this->Permisos_model->consultar_by_data($data_permisos);
	
	      		if( ! empty($consultar_padre)){
	      			$validador_permisos = TRUE;
	      		}

	      	}

	      	if($row->estado == PERMISOS_ARBOL_PRIVADO){
	      		$validador_permisos = TRUE;
	      	}

	      	if( ! empty($validador_permisos)) {
		        $user_tree_array[] = "<li id='APR_" . $row->idpermiso_arbol . "'>". $row->ruta;
		        $user_tree_array = $this->arbol_permisos_lista($row->idpermiso_arbol, $user_tree_array);
		        $user_tree_array[] = "</li>" ;
	      	}

	      }
	      $user_tree_array[] = "</ul>";
	    }
	    return $user_tree_array;
	}

	/**
	 * Lista de permisos con formato
	 * @return json
	 */
	public function arbol_permisos_menu_lista($padre = 0, $user_tree_array = array()) 
  	{
  		$data_arbol = array("padre"=>$padre);
		$consultar_permisos = $this->Menus_model->consultar_by_data($data_arbol);
		
	    if ( ! empty($consultar_permisos)) 
	    {
	      $user_tree_array[] = "<ul>";
	      foreach ($consultar_permisos as $row) {

	        $user_tree_array[] = "<li id='APRM_" . $row->idmenu . "'>". $row->nombre;	        
	        $user_tree_array = $this->arbol_permisos_menu_lista($row->idmenu, $user_tree_array);
	        $user_tree_array[] = "</li>" ;
	      }
	      $user_tree_array[] = "</ul>";
	    }
	    return $user_tree_array;
	}

	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos (datatable)
	 * @param  array $data     Almacena los datos de los roles
	 * @return array      	   Datos de los roles	
	 */
	public function recorrer_datos( $data ){
	 	$datos =  array();
        $filtered_rows = count($data);
        foreach($data as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->idrol;                
            $sub_array[] = $row->nombre;

            $function = [
                [
                	"method" 		=> "actualizar", 
                	"name"			=> "update", 
            		"id"			=> $row->idrol, 
					"classes" 		=> "btn btn-success update", 
					"icon" 			=> "flaticon-edit",
            		"title" 		=> "Modificar",
    				"attributes" 	=> "",
            		"link" 			=> FALSE
        		],
        		[
                	"method" 		=> "actualizar_permisos", 
                	"name"			=> "actualizar-permisos", 
            		"id"			=> "", 
            		"classes" 		=> "btn btn-danger asignar-permisos", 
					"title" 		=> "Permisos",
					"icon"          => "fas fa-eye",
    				"attributes" 	=> "data-id=" .$row->idrol. "",
            		"link" 			=> FALSE
        		],
        		[
                	"method" 		=> "actualizar_permisos_menu", 
                	"name"			=> "actualizar-permisos-menu", 
            		"id"			=> "", 
            		"classes" 		=> "btn btn-primary asignar-permisos-menu", 
					"title" 		=> "Permisos Menú",
					"icon"          => "fas fa-bars",
    				"attributes" 	=> "data-id=" .$row->idrol. "",
            		"link" 			=> FALSE
        		]
            ];

            $botones = $this->btn->get_buttons($function);
          
            $sub_array[] = $botones;
            $datos[] = $sub_array;                
        }

        $busqueda="";
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
            	$busqueda = $_POST["search"]["value"];
            }
        }

        $output = array(
            "draw"              =>  intval($this->input->post("draw")),
            "recordsTotal"      =>  $filtered_rows,
            "recordsFiltered"   =>  $filtered_rows/*$this->Roles_model->consultar_conteo_by_filtro($busqueda)*/,
            "data"              =>  $datos
        );
        return $output;
        print($output);
	}

	//-----------------------------------------------------------------------------
	
	/**
	 * Lista de roles
	 * @return json
	 */
	public function listar_roles(){
		$data = $this->Roles_model->consultar_by_filtros();
		$output = $this->recorrer_datos($data);
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de usuarios
	 * @return json
	 */
	public function crear(){		
		$nombre = $this->input->post("rol", TRUE);	
		$ruta = $this->input->post("ruta", TRUE);	
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el rol del usuario") ) );
		}

		if( empty( $ruta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre de la ruta principal") ) );
		}

		$data = array("nombre"=>$nombre, "ruta" => $ruta);

		$usuario_almacenado = $this->Roles_model->ingresar($data);

		if( empty($usuario_almacenado) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El rol no se ingreso a la base de datos" ) ));
		}
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El rol fue ingresado correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * Listar roles filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Roles_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El rol no existe" ) ) );
		}

		$data_rol = array("idrol" => $id);
		$consultar_permiso_rol = $this->Roles_model->consultar_permisos_by_data($data_rol);

		$data_rol = array("idrol" => $id);
		$consultar_permiso_menu = $this->Roles_model->consultar_menu_by_data($data_rol);
		
		$output = array();
		$output["id"] = $data->idrol;   
		$output["nombre"] = $data->nombre;   
		$output["ruta"] = $data->ruta;        
        $output["consultar_permiso_rol"] = $consultar_permiso_rol;
        $output["consultar_permiso_menu"] = $consultar_permiso_menu;
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de usuarios
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
		$nombre = $this->input->post("rol", TRUE);
		$ruta = $this->input->post("ruta", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre del rol") ) );
		}

		if( empty( $ruta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre de la ruta principal") ) );
		}

        $data = array("nombre"=>$nombre, "ruta" => $ruta);

		$data = $this->Roles_model->modificar($id,$data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El rol no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El rol se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización los permisos del rol
	 * @return json
	 */
	public function actualizar_permisos(){
		$id = $this->input->post("id", TRUE);
		$permisos = $this->input->post("permisos", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_rol = $this->Roles_model->consultar_by_id($id);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador valido" ) ) );
		}

		$this->db->trans_begin();

		$eliminar_rol = $this->Roles_model->eliminar_permisos($id);

		if(empty($eliminar_rol)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Los permisos del rol no se eliminaron correctamente" ) ) );
		}
        
        for ($i=0; $i < count($permisos); $i++) { 

        	$consultar_permiso = $this->Permisos_model->consultar_by_id(substr($permisos[$i], 4));

        	if(empty($consultar_permiso)){
        		$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El permiso no existe, por favor vuelve a intentarlo" ) ) );
        	}

  	        $data_permisos_roles = array("idrol"=>$id, "idpermiso_arbol" => substr($permisos[$i], 4));
			$data = $this->Roles_model->ingresar_permisos($data_permisos_roles);

			if( empty($data)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Los permisos no tiene cambios" ) ) );
			}
        }

        $this->db->trans_commit();

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El rol permiso se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización los permisos del rol por menu
	 * @return json
	 */
	public function actualizar_permisos_menu(){
		$id = $this->input->post("id", TRUE);
		$permisos = $this->input->post("permisos", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_rol = $this->Roles_model->consultar_by_id($id);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador valido" ) ) );
		}

		$this->db->trans_begin();
		$eliminar_menu_rol = $this->Roles_model->eliminar_permisos_menu($id);

		if(empty($eliminar_menu_rol)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Los permisos del rol no se eliminaron correctamente" ) ) );
		}
        
        for ($i=0; $i < count($permisos); $i++) {

        	$consultar_permiso = $this->Menus_model->consultar_by_id(substr($permisos[$i], 5));

        	if(empty($consultar_permiso)){
        		$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El permiso no existe, por favor vuelve a intentarlo" ) ) );
        	}

  	        $data_permisos_roles = array("idrol"=>$id, "idmenu" => substr($permisos[$i], 5));
			$data = $this->Roles_model->ingresar_permisos_menu($data_permisos_roles);

			if( empty($data)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Los permisos no tiene cambios" ) ) );
			}
        }

        $this->db->trans_commit();

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El rol permiso se modifico correctamente" ) );
	}

}