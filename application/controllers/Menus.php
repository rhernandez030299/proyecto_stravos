<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Menus_model");        
    }

    //-----------------------------------------------------------------------------
    public function index(){
    	//redirect('menus/login');
    	
		redirect('menus/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de menus
	 * @return void
	 */
	public function listar(){
		$this->lm->add_js('menus.js');
		$this->lm->set_title('Menú');
		$this->lm->set_page('menus/listar');		

		$function = [
            [
            	"method" 		=> "crear", 
            	"name"			=> "add_button", 
        		"id"			=> "add_button", 
        		"classes" 		=> "btn btn-primary btn-flat", 
				"title" 		=> "Nuevo", 
				"icon"			=> "fa fa-plus",
				"text"			=> "Nuevo",
				"icon"			=> "fa fa-plus",
        		"attributes" 	=> "",
        		"link" 			=> FALSE
    		]
        ];

        $botones = $this->btn->get_buttons($function);
        
		$consultar_menus = $this->Menus_model->consultar(true);
		$data = array("consultar_menus"=>$consultar_menus, "botones"=>$botones);

		$this->lm->render($data);
	}

	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de los menus
	 * @return array      	   Datos de los menus	
	 */
	public function recorrer_datos( $data ){
		 	$datos =  array();
            $filtered_rows = count($data);
            foreach($data as $row)
            {
                $sub_array = array();
                $sub_array[] = $row->ruta;
                $sub_array[] = $row->nombre;
                $sub_array[] = $row->clase;
                $sub_array[] = $row->icono;
     
                if($row->padre == 0){
                	$sub_array[] = "Principal";
                }else{
                	$consultar_menus = $this->Menus_model->consultar_by_id($row->padre);
                	if( ! empty($consultar_menus)){
						$sub_array[] = $consultar_menus->nombre;
                	}else{
                		$sub_array[] = "No existe";
                	}
                }

                $function = [
	                [
	                	"method" 		=> "actualizar", 
	                	"name"			=> "update", 
	            		"id"			=> $row->idmenu, 
						"classes" 		=> "btn-icon  btn-success update", 
						"icon" 			=> "flaticon-edit", 
	            		"title" 		=> "Modificar",
        				"attributes" 	=> "",
	            		"link" 			=> FALSE
            		]
                ];

                $botones = $this->btn->get_buttons($function);

                $sub_array[] = $botones;
                $sub_array[] = $row->idmenu;
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
                "recordsFiltered"   =>  $this->Menus_model->consultar_conteo_by_filtro($busqueda),
                "data"              =>  $datos
            );
            return $output;
	}

	//-----------------------------------------------------------------------------
	/**
	 * Lista de menus
	 * @return json
	 */
	public function listar_menus(){
		$data = $this->Menus_model->consultar_by_filtros();
		$output = $this->recorrer_datos($data);
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * Listar menus filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Menus_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ) );
		}
		
		$output = array();
		$output["id"] = $data->idmenu;
        $output["ruta"] = $data->ruta;
        $output["padre"] = $data->padre;
        $output["clase"] = $data->clase;
        $output["icono"] = $data->icono;
        $output["nombre"] = $data->nombre;
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de menus
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
		$ruta = strtolower($this->input->post("ruta", TRUE));
		$nombre = ucwords($this->input->post("nombre", TRUE));
		$clase = $this->input->post("clase", TRUE);
		$icono = $this->input->post("icono", TRUE);
		$padre = $this->input->post("padre", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_permiso = $this->Menus_model->consultar_by_id($id);

    	if( empty($consultar_permiso)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador valido")));
    	}

		if( empty( $ruta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la ruta" ) ) );
		}

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $clase ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la clase" ) ) );
		}

		if( empty( $icono ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el icono" ) ) );
		}

		if( ! empty( $padre  ) ){
			$consultar_padre = $this->Menus_model->consultar_by_id($padre);
        	if( empty($consultar_padre)){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un padre valido")));
        	}
		}

		$data = array("ruta"=>$ruta,"nombre"=>$nombre,"clase"=>$clase,"icono"=>$icono,"padre"=>$padre);
		$data = $this->Menus_model->modificar($id,$data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El menu no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El menu se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de menus
	 * @return json
	 */
	public function crear(){
		$ruta = strtolower($this->input->post("ruta", TRUE));
		$nombre = ucwords($this->input->post("nombre", TRUE));
		$clase = $this->input->post("clase", TRUE);
		$icono = $this->input->post("icono", TRUE);
		$padre = $this->input->post("padre", TRUE);

		if( empty( $ruta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la ruta" ) ) );
		}

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $clase ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la clase" ) ) );
		}

		if( empty( $icono ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el icono" ) ) );
		}

		if( ! empty( $padre  ) ){
			$consultar_padre = $this->Menus_model->consultar_by_id($padre);
        	if( empty($consultar_padre)){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un padre valido")));
        	}
		}

		$data = array("ruta"=>$ruta,"nombre"=>$nombre,"clase"=>$clase,"icono"=>$icono,"padre"=>$padre);
		$menu_arbol = $this->Menus_model->ingresar($data);

		if( empty($menu_arbol)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El menu no se ingreso a la base de datos" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El menu fue ingresado correctamente" ) );
	}
}