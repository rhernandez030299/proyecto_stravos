<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Permisos_model");        
    }

    //-----------------------------------------------------------------------------
    public function index(){
		redirect('permisos/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de permisos
	 * @return void
	 */
	public function listar(){
		
		$this->lm->add_js('permisos.js');
		$this->lm->set_title('Permisos');
		$this->lm->set_page('permisos/listar');		

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

		$consultar_permisos = $this->Permisos_model->consultar(true);
		$data = array("consultar_permisos"=>$consultar_permisos, "botones"=>$botones);

		$this->lm->render($data);
	}


	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de los permisos
	 * @return array      	   Datos de los permisos	
	 */
	public function recorrer_datos( $data ){
		 	$datos =  array();
            $filtered_rows = count($data);
            foreach($data as $row)
            {
                $sub_array = array();
                $sub_array[] = $row->ruta;
                $sub_array[] = $row->alias;
                $estado  = "Público";
                $span = "success";            	
            	
	            if($row->estado == PERMISOS_ARBOL_PRIVADO){
	                $estado = "Privado";
	                $span = "danger";
	            }else if($row->estado == PERMISOS_ARBOL_PROTEGIDO){
	                $estado = "Protegido";
	                $span = "warning";
	            }
              
				$sub_array[] = "<span class='label font-weight-bold label-lg label-light-".$span." label-inline'>".ucfirst($estado)."</span>";
                
                if($row->padre == 0){
                	$sub_array[] = "Principal";
                }else{
                	$consultar_permisos = $this->Permisos_model->consultar_by_id($row->padre);
                	if( ! empty($consultar_permisos)){
						$sub_array[] = $consultar_permisos->alias;
                	}else{
                		$sub_array[] = "No existe";
                	}
                }

                $function = [
	                [
	                	"method" 		=> "actualizar", 
	                	"name"			=> "update", 
	            		"id"			=> $row->idpermiso_arbol, 
	            		"classes" 		=> "btn btn-success update", 
						"title" 		=> "Modificar",
						"icon" 			=> "flaticon-edit",
        				"attributes" 	=> "",
	            		"link" 			=> FALSE
            		]
                ];

                $botones = $this->btn->get_buttons($function);

                $sub_array[] = $botones;
                $sub_array[] = $row->idpermiso_arbol;
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
                "recordsFiltered"   =>  $this->Permisos_model->consultar_conteo_by_filtro($busqueda),
                "data"              =>  $datos
            );
            return $output;
	}

	//-----------------------------------------------------------------------------
	
	/**
	 * Lista de permisos
	 * @return json
	 */
	public function listar_permisos(){
		$data = $this->Permisos_model->consultar_by_filtros();
		$output = $this->recorrer_datos($data);
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * Listar permisos filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Permisos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ) );
		}
		
		$output = array();
		$output["id"] = $data->idpermiso_arbol;
        $output["ruta"] = $data->ruta;
        $output["padre"] = $data->padre;
        $output["estado"] = $data->estado;
        $output["alias"] = $data->alias;
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de permisos
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
		$ruta = strtolower($this->input->post("ruta", TRUE));
		$alias = ucwords($this->input->post("alias", TRUE));
		$estado = $this->input->post("estado", TRUE);
		$padre = $this->input->post("padre", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_permiso = $this->Permisos_model->consultar_by_id($id);

    	if( empty($consultar_permiso)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador valido")));
    	}

		if( empty( $ruta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la ruta" ) ) );
		}

		$consultar_permisos = $this->Permisos_model->consultar_by_id($id);

		if( ! empty($consultar_permisos)){

			if($consultar_permisos->ruta!=$ruta){
	            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Esta ruta ya se encuentra ingresada" ) ) );
	        }	
		}
		
		if( $estado != PERMISOS_ARBOL_PUBLICO && $estado != PERMISOS_ARBOL_PRIVADO&& $estado != PERMISOS_ARBOL_PROTEGIDO){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado ") ) );
		}

		if( ! empty( $padre  ) ){
			$consultar_padre = $this->Permisos_model->consultar_by_id($padre);
        	if( empty($consultar_padre)){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un padre valido")));
        	}
		}

		$data = array("ruta"=>$ruta,"alias"=>$alias,"estado"=>$estado,"padre"=>$padre);
		$data = $this->Permisos_model->modificar($id,$data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El permiso no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El permiso se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de permisos
	 * @return json
	 */
	public function crear(){
		$ruta = strtolower($this->input->post("ruta", TRUE));
		$alias = ucwords($this->input->post("alias", TRUE));
		$estado = $this->input->post("estado", TRUE);
		$padre = $this->input->post("padre", TRUE);

		if( empty( $ruta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la ruta" ) ) );
		}

		$data_permisos = array("ruta"=>$ruta);
		$consultar_permisos = $this->Permisos_model->consultar_by_data($data_permisos);

		if( ! empty($consultar_permisos)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Esta ruta ya se encuentra ingresada" ) ) );
		}

		if( $estado != PERMISOS_ARBOL_PUBLICO && $estado != PERMISOS_ARBOL_PRIVADO&& $estado != PERMISOS_ARBOL_PROTEGIDO){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado ") ) );
		}

		if( ! empty( $padre  ) ){
			$consultar_padre = $this->Permisos_model->consultar_by_id($padre);
        	if( empty($consultar_padre)){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un padre valido")));
        	}
		}

		$data = array("ruta"=>$ruta,"alias"=>$alias,"estado"=>$estado,"padre"=>$padre);

		$permiso_almacenado = $this->Permisos_model->ingresar($data);

		if( empty($permiso_almacenado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El permiso no se ingreso a la base de datos" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El permiso fue ingresado correctamente" ) );
	}

}