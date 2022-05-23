<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Grupos_model");
        $this->load->model("Usuarios_model");
    }

    //-----------------------------------------------------------------------------
    public function index(){
		redirect('grupos/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de grupos
	 * @return void
	 */
	public function listar(){
		$this->lm->add_js('grupos.js');
		$this->lm->set_title('Grupos');
		$this->lm->set_page('grupos/listar');		

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
        
        $data_usuario = array("estado" => USUARIO_ACTIVO, "idrol" => ROL_ESTUDIANTE);
		$consultar_usuario = $this->Usuarios_model->consultar_by_data($data_usuario);
		
		$data = array("consultar_usuario" => $consultar_usuario, "botones"=>$botones);

		$this->lm->render($data);
	}

	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de los grupos
	 * @return array      	   Datos de los grupos	
	 */
	public function recorrer_datos( $data, $idusuario ){
		 	$datos =  array();
            $filtered_rows = count($data);
            foreach($data as $row)
            {
            	$cantidad_grupos = $this->Grupos_model->consultar_conteo_by_grupo_usuario($row->idgrupo);
                $sub_array = array();
                $sub_array[] = $row->idgrupo;
                $sub_array[] = $row->nombre;
                $sub_array[] = $cantidad_grupos->conteo;
   
                $function = [
	                [
	                	"method" 		=> "actualizar", 
	                	"name"			=> "update", 
	            		"id"			=> $row->idgrupo, 
						"classes" 		=> "btn btn-success update", 
						"icon" 			=> "flaticon-edit",
	            		"title" 		=> "Modificar",
        				"attributes" 	=> "",
	            		"link" 			=> FALSE
            		],
            		[
            			"method" 		=> "eliminar", 
	                	"name"			=> "delete", 
	            		"id"			=> $row->idgrupo, 
						"classes" 		=> "btn btn-danger delete", 
						"icon"			=> "fas fa-trash-alt",
	            		"title" 		=> "Eliminar", 
        				"attributes" 	=> "",
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
                "recordsFiltered"   =>  $this->Grupos_model->consultar_conteo_by_filtro($busqueda, $idusuario),
                "data"              =>  $datos
            );
            return $output;
	}

	//-----------------------------------------------------------------------------
	/**
	 * Lista de grupos
	 * @return json
	 */
	public function listar_grupos(){

		$idusuario = $this->session->UID;
		if($this->session->USER_ROL == ROL_ADMIN){
			$idusuario = FALSE;
		}
		$data = $this->Grupos_model->consultar_by_filtros($idusuario);
		$output = $this->recorrer_datos($data, $idusuario);
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * Listar grupos filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Grupos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ) );
		}

		$data_grupo = array("idgrupo" => $id);
		$cantidad_grupos = $this->Grupos_model->consultar_grupo_by_data($data_grupo);
		
		$output = array();
		$output["idgrupo"] = $data->idgrupo;
        $output["nombre"] = $data->nombre;
        $output["usuarios"] = $cantidad_grupos;
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de grupos
	 * @return json
	 */
	public function actualizar(){
		$id= $this->input->post("id", TRUE);
		$nombre = $this->input->post("nombre", TRUE);
		$usuarios = $this->input->post("usuarios", TRUE);
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $usuarios ) || count($usuarios) == 0){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese los estudiantes" ) ) );
		}

		$consultar_grupo = $this->Grupos_model->consultar_by_id($id);

		if( empty( $consultar_grupo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un grupo válido" ) ) );
		}

		if($this->session->USER_ROL != ROL_ADMIN){
			
			$data_grupo = array("idusuario_creacion" => $this->session->UID, "idgrupo" => $id);
		    $consultar_grupo_usuario = $this->Grupos_model->consultar_by_data($data_grupo);

		    if( empty( $consultar_grupo_usuario ) ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para modificar este grupo" ) ) );
			}

		}

		$data = array("nombre"=>$nombre);
		$modificar_grupo = $this->Grupos_model->modificar($id, $data);
		$this->db->trans_begin();
		if( empty($modificar_grupo)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo no se modifico" ) ));
		}

		$eliminar_grupo_usuario = $this->Grupos_model->eliminar_grupo_usuario($id);

		if( empty($eliminar_grupo_usuario)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo no se elimino correctamente" ) ));
		}

		for ($i=0; $i < count($usuarios); $i++) {
			$data_grupo_usuario = array("idgrupo"=> $id, "idusuario" => $usuarios[$i]["idusuario"]);
			$consultar_grupo = $this->Grupos_model->ingresar_grupo_usuario($data_grupo_usuario);

			if( empty($consultar_grupo)){
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo usuario no se modifico" ) ));
			}
		}

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El grupo se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de grupos
	 * @return json
	 */
	public function crear(){
		$nombre = $this->input->post("nombre", TRUE);
		$usuarios = $this->input->post("usuarios", TRUE);
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $usuarios ) || count($usuarios) == 0){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese los estudiantes" ) ) );
		}

		$data = array("nombre"=>$nombre, "idusuario_creacion" => $this->session->UID);
		$idgrupo = $this->Grupos_model->ingresar($data);
		$this->db->trans_begin();
		if( empty($idgrupo)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo no se ingreso a la base de datos" ) ));
		}

		for ($i=0; $i < count($usuarios); $i++) { 

			$data_grupo_usuario = array("idgrupo"=> $idgrupo, "idusuario" => $usuarios[$i]["idusuario"]);
			$consultar_grupo = $this->Grupos_model->ingresar_grupo_usuario($data_grupo_usuario);

			if( empty($consultar_grupo)){
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo usuario no se ingreso a la base de datos" ) ));
			}
		}

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El grupo fue ingresado correctamente" ) );
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Eliminar el grupo
	 * @return json
	 */
	public function eliminar(){
		$id = $this->input->post("id",TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}
		
		$consultar_grupo = $this->Grupos_model->consultar_by_id($id);

		if(empty($consultar_grupo)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo no existe" ) ) );
		}

		if($this->session->USER_ROL != ROL_ADMIN){
			
			$data_grupo = array("idusuario_creacion" => $this->session->UID, "idgrupo" => $id);
		    $consultar_grupo_usuario = $this->Grupos_model->consultar_by_data($data_grupo);

		    if( empty( $consultar_grupo_usuario ) ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para modificar este grupo" ) ) );
			}
		}

		$data = $this->Grupos_model->eliminar($id);
		
		if(empty($data)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo no se elimino" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo se elimino correctamente! " ) );
	}

	public function consultar_estudiantes_sin_grupo(){

		$grupos = $this->input->post("grupos",TRUE);

		$usuarios_grupos = [];

		if( ! empty($grupos)) {
			for ($i=0; $i < count($grupos); $i++) {

				$data_grupo_usuario = array("idgrupo" => $grupos[$i]["idgrupo"]);
				$consultar_usuarios = $this->Grupos_model->consultar_grupo_by_data($data_grupo_usuario);

				if( empty( $consultar_usuarios ) ){
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo no existe" ) ) );
				}

				foreach ($consultar_usuarios as $usuario) {
					array_push($usuarios_grupos, $usuario->idusuario);
				}
			}
		}
		
		$data_usuario = array("estado" => USUARIO_ACTIVO, "idrol" => ROL_ESTUDIANTE);
		$consultar_estudiante = $this->Usuarios_model->consultar_by_data($data_usuario, array_unique($usuarios_grupos), TRUE);	

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "estudiantes registrados", "data" => $consultar_estudiante ) );
	}
}