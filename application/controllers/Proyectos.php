<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos extends CI_Controller {

	public function __construct(){
		parent::__construct();   
		$this->load->library('Layout_manager',NULL,'lm'); 
		$this->load->model("Proyectos_model");
		$this->load->model("Metodologias_model");
		$this->load->model("Fases_model");
		$this->load->model("Modulos_model");
		$this->load->model("Usuarios_model");
		$this->load->model("Historias_model");
		$this->load->model("Grupos_model");
		$this->load->model("Presupuestos_model");		
		$this->load->model("Categorias_model");		
		$this->load->helper(array('download'));
		$this->load->helper(array('phpmailer_helper'));
  }

  //-----------------------------------------------------------------------------
  public function index(){
		redirect('proyectos/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de proyectos
	 * @return void
	 */
	public function listar(){
		$this->lm->set_title('Proyectos');
		$this->lm->add_css('daterangepicker/daterangepicker.css');
	 	$this->lm->add_js('daterangepicker/daterangepicker.min.js');
		$this->lm->set_page('proyectos/listar');
		$this->lm->add_js('proyectos');
		
		$function = [
      [
				"method" 		=> "crear", 
				"name"			=> "add_button", 
				"id"			=> "add_button", 
				"classes" 		=> "btn btn-light-primary btn-flat", 
				"title" 		=> "Agregar proyecto", 
				"text"			=> "Agregar proyecto",
				"link" 			=> FALSE
			]
		];
		
		$botones = $this->btn->get_buttons($function);
		
		
		$consultar_profesor = array();
		$data_usuario = array("estado" => USUARIO_ACTIVO, "idrol" => ROL_PROFESOR, "idusuario !=" => $this->session->UID);
		$data_usuario = array("estado" => USUARIO_ACTIVO, "idrol" => ROL_ESTUDIANTE);
		$consultar_estudiante = array();
		$consultar_metodologia = $this->Metodologias_model->consultar_by_data(array());

		$data_usuario = array();
		if($this->session->USER_ROL != ROL_ADMIN){
			$data_usuario = array("idusuario_creacion" => $this->session->UID);
		}else{
			$consultar_profesor = $this->Usuarios_model->consultar_by_data($data_usuario);
			$consultar_estudiante = $this->Usuarios_model->consultar_by_data($data_usuario);
		}
		
		$consultar_grupo = $this->Grupos_model->consultar_by_data($data_usuario);
		
		$data = array("consultar_grupo" => $consultar_grupo, "consultar_estudiante" => $consultar_estudiante, "consultar_profesor" => $consultar_profesor, "botones"=>$botones, "consultar_metodologia" => $consultar_metodologia, "consultar_profesor");
	

		$this->lm->render($data);
	}

	public function descargas($ruta){        

		if( empty( $ruta ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El nombre no existe" ) ) );
		}

		$data_archivo = array("ruta" => $ruta);
		$consultar_archivo = $this->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);

		if( empty( $consultar_archivo ) ) {
			
			$data_archivo = array("ruta" => $ruta);
			$consultar_archivo = $this->Metodologias_model->consultar_archivo_metodologia_by_data($data_archivo);

			if( empty( $consultar_archivo ) ) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El archivo no existe" ) ) );
			}
		}

	  $data = file_get_contents('./uploads/'.$ruta);
	  force_download($consultar_archivo[0]->client_name,$data);
	}
	/* SOLUCION ERROR DEL DUPLICADO DEL ARCHIVO   
	public function descargas($ruta){        

		if( empty( $ruta ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El nombre no existe" ) ) );
		}

		$data_archivo = array("ruta" => $ruta);
		$consultar_archivo = $this->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);

		if( empty( $consultar_archivo ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El archivo no existe" ) ) );
		}

	    $data = file_get_contents('./uploads/'.$ruta);
	    force_download($consultar_archivo[0]->client_name,$data);
	}*/

	public function miembros($url_proyecto, $url_metodologia){

		if(empty($url_proyecto) || empty($url_metodologia)){
			redirect("errores/not_found");	
		}

		$data_proyecto = array("url" => $url_proyecto);
		$consultar_proyecto = $this->Proyectos_model->consultar_by_data($data_proyecto);

		if(empty($consultar_proyecto)) {
			redirect("errores/not_found");	
		}

		$data_metodologia = array("url" => $url_metodologia);
		$consultar_metodologia = $this->Metodologias_model->consultar_by_data($data_metodologia);

		if(empty($consultar_metodologia)) {
			redirect("errores/not_found");	
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				redirect("errores/not_found");	
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				redirect("errores/not_found");	
			}
		}

		$data_miembro_proyecto = array("idproyecto" => $consultar_proyecto[0]->idproyecto);
		$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

		$this->lm->set_title('Miembros');
		$this->lm->set_page('miembros/listar');			
		$this->lm->add_css('miembros');

		$data = array("consultar_miembro"=>$consultar_miembro);
		$this->lm->render($data);
	}


	//-----------------------------------------------------------------------------
	/**
	 * Listar proyectos filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Proyectos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $data->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $data->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$data_proyecto_metodologia = array("idproyecto" => $id);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		$data_miembro_profesor = array("idproyecto" => $id, "idusuario !=" => $this->session->UID);
		$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_profesor);

		$data_miembro = array("idproyecto" => $id);
		$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro);

		$data_archivo = array("idproyecto" => $id);
		$consultar_archivo = $this->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);

		$output = array();
		$output["id"] = $data->idproyecto;
        $output["url"] = $data->url;
				$output["proyecto_base"] = $data->proyecto_base;
		$output["nombre"] = $data->nombre;
		$output["subtitulo"] = $data->subtitulo;
		$output["descripcion"] = $data->descripcion;
		$output["ruta_imagen"] = $data->ruta_imagen;
        $output["fecha_inicio"] = date('Y/m/d', strtotime($data->fecha_inicio));
        $output["fecha_finalizacion"] = date('Y/m/d', strtotime($data->fecha_finalizacion));
        $output["metodologia"] = $consultar_proyecto_metodologia;
        $output["profesor"] = $consultar_miembro_profesor;
        $output["usuario"] = $consultar_miembro;
        $output["archivos"] = $consultar_archivo;

		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de proyectos
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
		$nombre = ucwords($this->input->post("nombre", TRUE));
		$fecha_inicio = $this->input->post("fecha_inicio", TRUE);
		$metodologia = $this->input->post("metodologia", TRUE);
		$profesor = $this->input->post("profesor", TRUE);
		$subtitulo = $this->input->post("subtitulo", TRUE);
		$descripcion = $this->input->post("descripcion", TRUE);
		$usuarios = $this->input->post("usuarios", TRUE);
		$proyecto_base = $this->input->post("proyecto_base", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($id);

    if( empty($consultar_proyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador valido")));
    }

		if( $proyecto_base != 1 && $proyecto_base != 0 ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un proyecto base" ) ) );
		}

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		$dataProyecto = array("nombre" => $nombre);
		$consultarProyecto = $this->Proyectos_model->consultar_by_data( $dataProyecto );
	
		if(!empty($consultarProyecto)){
			if($consultarProyecto[0]->idproyecto!=$id){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este nombre ya se encuentra registrado") ) );
			}
		}


		if( empty( $fecha_inicio ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la fecha de inicio" ) ) );
		}

		list($fecha_inicio, $fecha_finalizacion) = explode(' - ', $_POST['fecha_inicio']);

		if( empty( validar_fecha($fecha_inicio, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		if( empty( validar_fecha($fecha_finalizacion, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		if($this->session->USER_ROL != ROL_ADMIN){
			$data_profesor = array("idusuario" => $this->session->UID);
			$consultar_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_profesor);
			if( empty($consultar_profesor)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tienes permisos para realizar esta acción" ) ));
			}
		}

		$data = array("nombre" => $nombre, "fecha_inicio" => $fecha_inicio, "fecha_finalizacion" => $fecha_finalizacion, "subtitulo" => $subtitulo, "descripcion" => $descripcion, "proyecto_base" => $proyecto_base);

		if( ! empty(  $_FILES["imagen_update"]["name"] ) ){
           
			$config['upload_path']          = "./uploads/"; //Directiorio en archivo config
	        $config['allowed_types']        = 'jpg|png|gif|jpeg';
	        $config['file_name']            = time() . rand(1000, 100000);
	        $config['file_ext_tolower']     = TRUE;
	        $config['overwrite']            = TRUE;
	        $config['max_size']             = 10000;

	        $this->load->library('upload', $config);   

	        if ( ! $this->upload->do_upload('imagen_update') ){
	            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => $this->upload->display_errors() ) ) );
	        }
	      
	        $upload_data = $this->upload->data();
			$ruta_imagen = $upload_data["orig_name"];
			
			$data = array_merge($data, array("ruta_imagen" => $ruta_imagen));
		}
	
		$data = $this->Proyectos_model->modificar($id,$data);

		$this->db->trans_begin();
		if( empty($data)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no tiene cambios" ) ) );
		}
		
		if(empty($usuarios)){
			$eliminar_miembro = $this->Proyectos_model->eliminar_miembro($id);
			if( empty($eliminar_miembro)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El miembro no se elimino correctamente" ) ));
			}	
		}
		
		$eliminar_miembro_profesor = $this->Proyectos_model->eliminar_miembro_profesor($id, $this->session->UID);
		if( empty($eliminar_miembro_profesor)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El miembro profesor no se elimino correctamente" ) ));
		}

	    $texto_metodologia = "";
	    $validar_metodologia = FALSE;
		/*for ($i=0; $i < count($metodologia); $i++) {

			$consultar_metodologia = $this->Metodologias_model->consultar_by_id($metodologia[$i]["idmetodologia"]);

			if( empty($consultar_metodologia)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología ingresada, no existe" ) ));
			}	

			$data_proyecto_metodologia = array("idproyecto" => $consultar_proyecto->idproyecto, "idmetodologia" => $consultar_metodologia->idmetodologia);
			$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

			if( empty($consultar_proyecto_metodologia)) {
				$data_proyecto_metodologia = array("idproyecto"=> $id, "idmetodologia" => $metodologia[$i]["idmetodologia"]);
				$crear_proyecto_metodologia = $this->Proyectos_model->ingresar_proyecto_metodologia($data_proyecto_metodologia);

				if( empty($crear_proyecto_metodologia)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto metodología no se creo correctamente" ) ));
				}
			}

			
			$texto_metodologia .= $consultar_metodologia->nombre . ", ";
		}*/

		if( ! empty($profesor)){
			for ($i=0; $i < count($profesor); $i++) { 

				$consultar_profesor = $this->Usuarios_model->consultar_by_id($profesor[$i]["idusuario"]);

				if( empty($consultar_profesor)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El profesor ingresado, no existe" ) ));
				}

				$data_proyecto_profesor = array("idproyecto"=> $id, "idusuario" => $profesor[$i]["idusuario"]);
				$crear_proyecto_profesor = $this->Proyectos_model->ingresar_miembro_profesor($data_proyecto_profesor);

				if( empty($crear_proyecto_profesor)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El profesor no se creo correctamente" ) ));
				}
			}
		}

		if( ! empty($usuarios)){
			$mail = phpmailer_init();
			$texto_metodologia = substr($texto_metodologia, 0, strlen($texto_metodologia) - 2) . ".";
			$consultar_profesor = $this->Usuarios_model->consultar_by_id($this->session->UID);
			$usuario_eliminar = [];
			for ($i=0; $i < count($usuarios); $i++) { 

				$data_proyecto_usuarios = array("idproyecto"=> $id, "idusuario" => $usuarios[$i]["idusuario"]);
				$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_proyecto_usuarios);

				if(empty($consultar_miembro)){

					$consultar_usuarios = $this->Usuarios_model->consultar_by_id($usuarios[$i]["idusuario"]);

					if( empty($consultar_usuarios)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario ingresado, no existe" ) ));
					}

					if( $consultar_usuarios->estado == USUARIO_INACTIVO) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario " . $consultar_usuarios->correo . " no se encunetra activo, por favor borrelo de la lista de estudiantes o refresque la página" ) ));
					}
		
					$data_proyecto_usuarios = array("idproyecto"=> $id, "idusuario" => $usuarios[$i]["idusuario"], "sent_email" => 1);
					$crear_proyecto_usuarios = $this->Proyectos_model->ingresar_miembro($data_proyecto_usuarios);

					if( empty($crear_proyecto_usuarios)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto usuarios no se creo correctamente" ) ));
					}

					$data_archivo = array("idproyecto" => $id);
					$consultar_archivo = $this->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);

					$archivos_guardados = [];

					foreach ($consultar_archivo as $archivo) {
						$archivos_guardados[] = [$archivo->ruta, $archivo->client_name, $archivo->peso];
					}

					$consultar_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_id_proyecto($id);
		            
				  	$data = array("nombre" => $consultar_usuarios->nombre . " " . $consultar_usuarios->apellido, "nombre_profesor" => $consultar_profesor->nombre . " " . $consultar_profesor->apellido, "url" => $consultar_proyecto->url, "fecha_inicio" => $fecha_inicio,"fecha_fin"=>$fecha_finalizacion, "metodologia" => $texto_metodologia, "correo" => $consultar_usuarios->correo, "archivos_guardados" => $archivos_guardados, "nombre_proyecto" => $consultar_proyecto->nombre, "url_metodologia" => $consultar_metodologia[0]->url);
					
					$this->enviar_correo($data, $mail);
				}

				array_push($usuario_eliminar, $usuarios[$i]["idusuario"]);
			}

			$eliminar_miembro = $this->Proyectos_model->eliminar_miembro_in($usuario_eliminar, $id);
			if( empty($eliminar_miembro)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El miembro no se elimino correctamente" ) ));
			}	
		}

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El proyecto se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de proyectos
	 * @return json
	 */
	public function crear(){
		$nombre = $this->input->post("nombre", TRUE);
		$metodologia = $this->input->post("metodologia", TRUE);
		$profesor = $this->input->post("profesor", TRUE);
		$grupos = $this->input->post("grupos", TRUE);
		$usuarios = $this->input->post("usuarios", TRUE);
		$fecha_inicio = $this->input->post("fecha_inicio", TRUE);
		$subtitulo = $this->input->post("subtitulo", TRUE);
		$descripcion = $this->input->post("descripcion", TRUE);
		$proyecto_base = $this->input->post("proyecto_base", TRUE);
		$archivos_guardados = [];

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		$dataProyecto = array("nombre" => $nombre);
		$consultarProyecto = $this->Proyectos_model->consultar_by_data( $dataProyecto );
	
		if( ! empty($consultarProyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este nombre ya se encuentra registrado") ) );
		}

		if( $proyecto_base != 1 && $proyecto_base != 0 ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un proyecto base" ) ) );
		}

		if( empty( $fecha_inicio ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la fecha de inicio" ) ) );
		}

		if( empty( $metodologia ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la metodología" ) ) );
		}

		list($fecha_inicio, $fecha_finalizacion) = explode(' - ', $_POST['fecha_inicio']);

		if( empty( validar_fecha($fecha_inicio, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		if( empty( validar_fecha($fecha_finalizacion, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		$ruta_imagen = "";

		if( ! empty(  $_FILES["imagen"]["name"] ) ){
			$config['upload_path']          = "./uploads/"; //Directiorio en archivo config
			$config['allowed_types']        = 'jpg|png|gif|jpeg';
			$config['file_name']            = time() . rand(1000, 100000);
			$config['file_ext_tolower']     = TRUE;
			$config['overwrite']            = TRUE;
			$config['max_size']             = 10000;

			$this->load->library('upload', $config);   

			if ( ! $this->upload->do_upload('imagen') ){
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => $this->upload->display_errors() ) ) );
			}
		
			$upload_data = $this->upload->data();
			$ruta_imagen = $upload_data["orig_name"];
		}

		$data = array("nombre"=>$nombre,"fecha_inicio"=>$fecha_inicio,"fecha_finalizacion"=>$fecha_finalizacion, "subtitulo" => $subtitulo, "descripcion" => $descripcion, "ruta_imagen" => $ruta_imagen, "proyecto_base" => $proyecto_base);
		$idingresado = $this->Proyectos_model->ingresar($data);

		$this->db->trans_begin();
		if( empty($idingresado)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no se ingreso a la base de datos" ) ));
		}

		if(isset($_FILES['file'])){
			$archivos_guardados = $this->upload_files("./uploads/", $_FILES['file']);        

			for ($i=0; $i < count($archivos_guardados); $i++) { 
			
					$data_archivo = array("ruta"=>$archivos_guardados[$i][0], "client_name" => $archivos_guardados[$i][1], "idproyecto" => $idingresado, "peso" => $archivos_guardados[$i][2]);
					$crear_archivo = $this->Proyectos_model->ingresar_archivo($data_archivo);
					
					if(empty($crear_archivo)){

							for ($i=0; $i < count($archivos_guardados); $i++) { 
									$path = "./uploads/" . $archivos_guardados[$i];
									if (file_exists($path) &&  ! empty($archivos_guardados[$i])){
											unlink($path);
									}
							}
							
							$this->db->trans_rollback();
							die(json_encode( array('res' => EXIT_ERROR, 'id' => $id, 'name' => 'El archivo no se ingreso correctamente' ) ) );
					}
			}  
	}

	$texto_metodologia = "";
		for ($i=0; $i < count($metodologia); $i++) { 
 			
			$consultar_metodologia = $this->Metodologias_model->consultar_by_id($metodologia[$i]["idmetodologia"]);

			if( empty($consultar_metodologia)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología ingresada, no existe" ) ));
			}

			$data_proyecto_metodologia = array("idproyecto"=> $idingresado, "idmetodologia" => $metodologia[$i]["idmetodologia"]);
			$crear_proyecto_metodologia = $this->Proyectos_model->ingresar_proyecto_metodologia($data_proyecto_metodologia);

			if( empty($crear_proyecto_metodologia)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto metodología no se creo correctamente" ) ));
			}

			$texto_metodologia .= $consultar_metodologia->nombre . ", ";
		}

		if( ! empty($profesor)){
			for ($i=0; $i < count($profesor); $i++) { 

				$consultar_profesor = $this->Usuarios_model->consultar_by_id($profesor[$i]["idusuario"]);

				if( empty($consultar_profesor)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El profesor ingresado, no existe" ) ));
				}

				$data_proyecto_profesor = array("idproyecto"=> $idingresado, "idusuario" => $profesor[$i]["idusuario"]);
				$crear_proyecto_profesor = $this->Proyectos_model->ingresar_miembro_profesor($data_proyecto_profesor);

				if( empty($crear_proyecto_profesor)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El profesor no se creo correctamente" ) ));
				}
			}
		}

		$mail = phpmailer_init();
		$url = generar_url($idingresado .  "-" . strtolower($nombre));

		$consultar_profesor = $this->Usuarios_model->consultar_by_id($this->session->UID);
		$texto_metodologia = substr($texto_metodologia, 0, strlen($texto_metodologia) - 2) . ".";

		if( ! empty($grupos)){

			for ($i=0; $i < count($grupos); $i++) { 

				$data_grupo = array("idgrupo" => $grupos[$i]["idgrupo"]);			
				$consultar_grupos = $this->Grupos_model->consultar_grupo_by_data($data_grupo);			
				if( empty($consultar_grupos)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo ingresado, no existe" ) ));
				}

				foreach ($consultar_grupos as $row_grupos) {

					$data_proyecto_grupos = array("idproyecto"=> $idingresado, "idusuario" => $row_grupos->idusuario);
					$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_proyecto_grupos);

					if(empty($consultar_miembro)){

						$consultar_usuarios = $this->Usuarios_model->consultar_by_id($row_grupos->idusuario);

						if( empty($consultar_usuarios)) {
							$this->db->trans_rollback();
							die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario ingresado, no existe" ) ));
						}

						if($consultar_usuarios->estado != USUARIO_INACTIVO){

							$data_proyecto_usuarios = array("idproyecto"=> $idingresado, "idusuario" => $row_grupos->idusuario, "sent_email" => 1);
							$crear_proyecto_usuarios = $this->Proyectos_model->ingresar_miembro($data_proyecto_usuarios);

							if( empty($crear_proyecto_usuarios)) {
								$this->db->trans_rollback();
								die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto usuarios no se creo correctamente" ) ));
							}

							$data = array("nombre" => $consultar_usuarios->nombre . " " . $consultar_usuarios->apellido, "nombre_profesor" => $consultar_profesor->nombre . " " . $consultar_profesor->apellido, "url" => $url, "fecha_inicio" => $fecha_inicio,"fecha_fin"=>$fecha_finalizacion, "metodologia" => $texto_metodologia, "correo" => $consultar_usuarios->correo, "archivos_guardados" => $archivos_guardados, "nombre_proyecto" => $nombre);
							
							$this->enviar_correo($data, $mail);

						}
					}
				}
			}
		}

		if( ! empty($usuarios)){
			for ($i=0; $i < count($usuarios); $i++) { 

				$data_miembro = array("idproyecto"=> $idingresado, "idusuario" => $usuarios[$i]["idusuario"]);
				$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro);

				if(empty($consultar_miembro)){

					$consultar_usuarios = $this->Usuarios_model->consultar_by_id($usuarios[$i]["idusuario"]);

					if( empty($consultar_usuarios)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario ingresado, no existe" ) ));
					}

					$data_proyecto_usuarios = array("idproyecto"=> $idingresado, "idusuario" => $usuarios[$i]["idusuario"], "sent_email" => 1);
					$crear_proyecto_usuarios = $this->Proyectos_model->ingresar_miembro($data_proyecto_usuarios);

					if( empty($crear_proyecto_usuarios)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto usuarios no se creo correctamente" ) ));
					}

		     		$data = array("nombre" => $consultar_usuarios->nombre . " " . $consultar_usuarios->apellido, "nombre_profesor" => $consultar_profesor->nombre . " " . $consultar_profesor->apellido, "url" => $url, "fecha_inicio" => $fecha_inicio,"fecha_fin"=>$fecha_finalizacion, "metodologia" => $texto_metodologia, "correo" => $consultar_usuarios->correo, "archivos_guardados" => $archivos_guardados, "nombre_proyecto" => $nombre);
					
					$this->enviar_correo($data, $mail);
				}
			}
		}

		$data = array("url"=>$url);
		$data = $this->Proyectos_model->modificar($idingresado, $data);

		if( empty($data)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La url no se ingreso correctamente" ) ));
		}

		$data_proyecto_profesor = array("idproyecto"=> $idingresado, "idusuario" => $consultar_profesor->idusuario, "usuario_creacion" => 1);
		$crear_proyecto_profesor = $this->Proyectos_model->ingresar_miembro_profesor($data_proyecto_profesor);

		if( empty($crear_proyecto_profesor)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El profesor no se creo correctamente" ) ));
		}

		$this->db->trans_commit();

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El proyecto fue ingresado correctamente" ) );
	}

	public function enviar_correo($data, $mail){
		
		$mail->AddAddress($data["correo"]);

        $mail->Subject = "Proyecto: Nuevo miembro";
       
        $body = $this->load->view("template/correos/nuevo_miembro", $data, true);
        $mail->Body = $body;

        if( ! empty($data["archivos_guardados"])){

        	for ($i=0; $i < count($data["archivos_guardados"]); $i++) {
                $archivo = "./uploads/".$data["archivos_guardados"][$i][0];
        		$mail->AddAttachment($archivo, $data["archivos_guardados"][$i][1]);
            }
        }
        
        $exito = $mail->Send();

        if(empty($exito)) {
            log_message('debug', 'El correo no se envio correctamente');
        }

        $mail->ClearAllRecipients();
        return;
	}

	private function upload_files($path, $files)
    {

        $config = array(
            'upload_path'   => $path,
            'allowed_types' => '*',
            'overwrite'     => 1,
            'max_size'      => 6000,
            'file_ext_tolower' => 1,

        );

        $this->load->library('upload', $config);

        $images = array();

        foreach ($files['name'] as $key => $image) {
            $_FILES['images[]']['name']= $files['name'][$key];
            $_FILES['images[]']['type']= $files['type'][$key];
            $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['images[]']['error']= $files['error'][$key];
            $_FILES['images[]']['size']= $files['size'][$key];

            $nombre_archivo = time() . rand(1000, 100000) . $key;

            $config['file_name'] = $nombre_archivo;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('images[]')) {
                
                for ($i=0; $i < count($images); $i++) {
                    $path_image = $path . $images[$i][0];
                    if (file_exists($path_image) &&  ! empty($images[$i][0])) {
                        unlink($path_image);
                    }
                }
                die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => $this->upload->display_errors() ) ) );
            }
            
            $upload_data = $this->upload->data();
            $images[] = [$upload_data["orig_name"], $upload_data["client_name"], $upload_data["file_size"]];
        }

        return $images;
    }

    //-----------------------------------------------------------------------------
	/**
	 * Carga de imagenes
	 * @return json
	 */
	public function upload_file() {

		$id = $this->input->post("idproyecto", true);

		if( empty( $id ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($id);

    	if( empty($consultar_proyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador válido")));
    	}

		if( ! empty(  $_FILES["file"]["name"] ) ){
           
			$config['upload_path']          = "./uploads/"; //Directiorio en archivo config
	        $config['allowed_types']        = '*';
	        $config['file_name']            = time() . rand(0, 100000) . $id;
	        $config['file_ext_tolower']     = TRUE;
	        $config['overwrite']            = TRUE;
	        $config['max_size']             = 10000;

	        $this->load->library('upload', $config);   

	        if ( ! $this->upload->do_upload('file') ){
	            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => $this->upload->display_errors() ) ) );
	        }
	      
	        $upload_data = $this->upload->data();

	        $data = array("ruta"=>$upload_data["orig_name"], "client_name" => $upload_data["client_name"], "idproyecto" => $id, "peso" => $upload_data["file_size"]);
        	$data_modificar = $this->Proyectos_model->ingresar_archivo($data);
        	
        	if(empty($data_modificar)){

        		$path = "./uploads/" . $upload_data["orig_name"];
	        	if (file_exists($path) &&  ! empty($upload_data["orig_name"])) {
    				unlink($path);
	        	}

	        	die(json_encode( array('res' => EXIT_ERROR, 'id' => $id, 'name' => 'El archivo no se ingreso correctamente' ) ) );
        	}
		}

		echo json_encode( array('res' => EXIT_SUCCESS, 'id' => $id, 'name' => $upload_data["orig_name"] ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * Eliminación imagenes
	 * @return json
	*/
	public function removed_file() {

		$id = $this->input->post("idproyecto", true);
		$nombre = $this->input->post("nombre", true);

		if( empty( $id ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		if( empty( $nombre ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El nombre no existe" ) ) );
		}
	
		$eliminar_foto = $this->Proyectos_model->eliminar_archivo_proyecto($nombre, $id);

		if( empty( $eliminar_foto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El archivo no se elimino" ) ) );
		}

		$path = "./uploads/" . $nombre;

		if (file_exists($path)) {
			unlink($path);
    	}

    	echo json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Se elimino correctamente" ) );
	}

	public function obtener_proyectos(){

		$start = $this->input->post("start", true);
		$length = $this->input->post("length", true);
		$search = $this->input->post("search", true);
		$estado = $this->input->post("estado", true);
		
		$consultar_proyecto = $this->Proyectos_model->consultar_proyecto_con_miembro($start, $length, $search, $estado);

		$consultar_conteo = $this->Proyectos_model->consultar_conteo_proyecto_con_miembro($search, $estado);

		$datos =  array();
		foreach($consultar_proyecto as $row)
		{
			$sub_array = array();
			
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_id_proyecto($row->idproyecto);
			
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_id_proyecto($row->idproyecto);

			$data_archivo = array("idproyecto" => $row->idproyecto);
			$consultar_archivo = $this->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);

			$consultar_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_id_proyecto($row->idproyecto);

			$consultar_archivo_metodologia = array();

			$nombre_metodologia = "";
			$url_metodologia = "";
			foreach ($consultar_metodologia as $metodologia) {
				$nombre_metodologia = $metodologia->nombre;
				$url_metodologia = $metodologia->url;

				$data_archivo_metodologia = array("idmetodologia" => $metodologia->idmetodologia);
				$consultar_archivo_metodologia = $this->Metodologias_model->consultar_archivo_metodologia_by_data($data_archivo_metodologia);
			}

			$consultar_modulo = $this->Proyectos_model->consultar_modulos_by_proyecto(FALSE, $row->idproyecto);
			$consultar_modulo_finalizado = $this->Proyectos_model->consultar_modulos_by_proyecto(MODULO_FINALIZADO, $row->idproyecto);
			$porcentaje = 0;

			if( ! empty($consultar_modulo) && ! empty($consultar_modulo_finalizado) && $consultar_modulo->contador_modulos > 0){
				$porcentaje = ceil(($consultar_modulo_finalizado->contador_modulos * 100) / $consultar_modulo->contador_modulos);
			}

			$date_inicio = formato_fecha($row->fecha_inicio);
			$date_finalizacion = formato_fecha($row->fecha_finalizacion);
				
			$date_inicio = $date_inicio["dia"] . " de " . $date_inicio["mes"] . " de " . $date_inicio["ano"];
			$date_finalizacion = $date_finalizacion["dia"] . " de " . $date_finalizacion["mes"] . " de " . $date_finalizacion["ano"];

			$sub_array["idproyecto"] = $row->idproyecto;
			$sub_array["miembro_profesor"] = $consultar_miembro_profesor;
			$sub_array["miembro"] = $consultar_miembro;
			$sub_array["archivo"] = $consultar_archivo;
			$sub_array["archivo_metodologia"] = $consultar_archivo_metodologia;
			$sub_array["metodologia"] = $consultar_metodologia;
			$sub_array["porcentaje"] = $porcentaje;
			$sub_array["date_inicio"] = $date_inicio;
			$sub_array["date_finalizacion"] = $date_finalizacion;
			$sub_array["ruta_imagen"] = $row->ruta_imagen;
			$sub_array["url"] = $row->url;
			$sub_array["subtitulo"] = (empty($row->subtitulo) ? '' : $row->subtitulo);
			$sub_array["nombre"] = $row->nombre;
			$sub_array["descripcion"] = (empty($row->descripcion) ? '' : $row->descripcion);
			
			$function = [
				[
					"method" 		=> "actualizar", 
					"name"			=> "update", 
					"id"			=> $row->idproyecto, 
					"classes" 		=> "dropdown-item font-weight-normal update",
					"title" 		=> "Modificar",
					"text" 		    => "Modificar",
					"attributes" 	=> "",
					"link" 			=> FALSE
				],				
				[
					"method" 		=> "eliminar", 
					"name"			=> "delete", 
					"id"			=> $row->idproyecto, 
					"classes" 		=> "dropdown-item font-weight-normal delete", 
					"title" 		=> "Eliminar", 
					"text" 		    => "Eliminar", 
					"attributes" 	=> "",
					"link" 			=> FALSE
				],
				[
					"method" 		=> "actualizar", 
					"name"			=> "clonar", 
					"id"			=> $row->idproyecto, 
					"classes" 		=> "dropdown-item font-weight-normal clonar",
					"title" 		=> "Clonar",
					"text" 		    => "Clonar",
					"attributes" 	=> "",
					"link" 			=> FALSE
				],
			];
			$botones = $this->btn->get_buttons($function);

            $sub_array["botones"] = $botones;
			
			$datos[] = $sub_array;
			
        }

        echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "FASES REGISTRADAS", "data" => $datos, "count" => 	$consultar_conteo ) );
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Eliminar el proyecto
	 * @return json
	 */
	public function eliminar(){
		$id = $this->input->post("id",TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}
		
		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($id);

		if(empty($consultar_proyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}
		
		$data = $this->Proyectos_model->eliminar($id);
		
		if(empty($data)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no se elimino" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto se elimino correctamente! " ) );
	}

	public function obtener_presupuesto(){

		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Proyectos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $data->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $data->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$consultar_categoria = $this->Categorias_model->consultar();
		$datos_categorias = [];
		$total_presupuesto = 0;
		foreach($consultar_categoria as $categoria){
			$presupuesto = $this->Presupuestos_model->consultar_presupuesto(FALSE, FALSE, FALSE, $id, $categoria->idcategoria);
			if( $presupuesto != 0){
				$total_presupuesto += $presupuesto;
				$datos_categorias[] = [
					'nombre' => $categoria->nombre,
					'total' => "$ " . number_format($presupuesto, 2, ",", ".")
				];
			}
		}

		$datos_categorias[] = [
			'nombre' => 'Total',
			'total' => "$ " . number_format($total_presupuesto, 2, ",", ".")
		];

		echo json_encode($datos_categorias);
	}
}