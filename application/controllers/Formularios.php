<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formularios extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Formularios_model");
				$this->load->model("Preguntas_model");
				$this->load->model("Opciones_model");
				$this->load->model("Usuarios_model");
				$this->load->model("Grupos_model");
				$this->load->model("Respuestas_model");
    }

    //-----------------------------------------------------------------------------
    public function index(){
		redirect('formularios/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de formularios
	 * @return void
	 */
	public function listar(){

		$this->lm->add_js('formularios/index.js');
		$this->lm->set_title('Formularios');
		$this->lm->set_page('formularios/listar');		

    $function = [
        [
          "method" 		=> "crear", 
          "name"			=> "add_button", 
          "id"			  => "add_button", 
          "classes" 	=> "btn btn-primary btn-flat", 
          "title" 		=> "Nuevo", 
          "text"			=> "Nuevo",
          "icon"			=> "fa fa-plus",
          "ruta"      => base_url('formularios/crear'),
          "link" 			=> TRUE
        ]
    ];

    $botones = $this->btn->get_buttons($function);

		$data = array("botones"=>$botones);

		$this->lm->render($data);
	}


	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de los formularios
	 * @return array      	   Datos de los formularios	
	 */
	public function recorrer_datos( $data ){
		 	$datos =  array();
            $filtered_rows = count($data);
            foreach($data as $row)
            {
                $sub_array = array();
                $sub_array[] = $row->idformulario;
                $sub_array[] = $row->nombre;
                $estado  = "";
                $span = "";            	
            	
                if($row->estado == FORMULARIO_PROGRAMADO){
                    $estado = "Programado";
                    $span = "warning";
                }else if($row->estado == FORMULARIO_INACTIVO){
                    $estado = "Inactivo";
                    $span = "warning";
                }else if($row->estado == FORMULARIO_ACTIVO){
                    $estado = "Activo";
                    $span = "success";
                }
              
				        $sub_array[] = "<span class='label font-weight-bold label-lg label-light-".$span." label-inline'>".$estado."</span>";

                $function = [
	                [
	                	"method" 		=> "actualizar", 
	                	"name"			=> "update", 
                    "id"			  => $row->idformulario, 
                    "classes" 	=> "btn btn-success update", 
                    "title" 		=> "Modificar",
                    "icon" 			=> "flaticon-edit",
										"attributes" 	=> "",
										"ruta"      => base_url('formularios/modificar/'.$row->idformulario),
	            		  "link" 			=> TRUE
									],
									[
	                	"method" 		=> "ver_formulario", 
	                	"name"			=> "ver_formulario", 
                    "id"			  => $row->idformulario, 
                    "classes" 	=> "btn btn-primary ver_formulario", 
                    "title" 		=> "Ver formulario",
                    "icon" 			=> "far fa-eye",
										"attributes" 	=> "target='_blank'",
										"ruta"      => base_url('formularios/ver_formulario/'.$row->idformulario),
	            		  "link" 			=> TRUE
									],
									[
	                	"method" 		=> "ver_respuesta", 
	                	"name"			=> "ver_respuesta", 
                    "id"			  => $row->idformulario, 
                    "classes" 	=> "btn btn-light-primary ver_respuesta", 
                    "title" 		=> "Ver respuesta",
                    "icon" 			=> "flaticon-questions-circular-button",
										"attributes" 	=> "target='_blank'",
										"ruta"      => base_url('formularios/ver_respuesta/'.$row->idformulario),
	            		  "link" 			=> TRUE
									],
									[
										"method" 		=> "eliminar", 
										"name"			=> "delete", 
										"id"			=> $row->idformulario, 
										"classes" 		=> "btn btn-danger delete", 
										"icon"			=> "fas fa-trash-alt",
										"title" 		=> "Eliminar", 
										"attributes" 	=> "",
										"link" 			=> FALSE
									]
                ];

                $fecha_publicacion = "";

                if( ! empty($row->fecha_inicio) && ! empty($row->fecha_inicio)){
                  $date_inicio = formato_fecha($row->fecha_inicio);
                  $date_finalizacion = formato_fecha($row->fecha_final);
                    
                  $date_inicio = $date_inicio["dia"] . " de " . $date_inicio["mes"] . " de " . $date_inicio["ano"];
                  $date_finalizacion = $date_finalizacion["dia"] . " de " . $date_finalizacion["mes"] . " de " . $date_finalizacion["ano"];

                  $fecha_publicacion = $date_inicio . " - " . $date_finalizacion;
                }
                
                $botones = $this->btn->get_buttons($function);

                $sub_array[] = $fecha_publicacion;
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
                "recordsFiltered"   =>  $this->Formularios_model->consultar_conteo_by_filtro($busqueda),
                "data"              =>  $datos
            );
            return $output;
	}

	//-----------------------------------------------------------------------------
	
	/**
	 * Lista de formularios
	 * @return json
	 */
	public function listar_formularios(){
		$data = $this->Formularios_model->consultar_by_filtros();
		$output = $this->recorrer_datos($data);
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * Listar formularios filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Formularios_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no existe" ) ) );
		}
		
		$output = array();
		$output["id"] = $data->idformulario;
    $output["nombre"] = $data->nombre;
		
		echo json_encode($output);
  }
  
  public function modificar($idformulario){

		if( empty( $idformulario ) ){
			redirect('404_override');
		}

		$consultar_formulario = $this->Formularios_model->consultar_by_id($idformulario);

		if( empty( $consultar_formulario ) ){
			redirect('404_override');
		}

		if($this->session->USER_ROL != ROL_ADMIN){

			if($consultar_formulario->idusuario_creacion != $this->session->UID){
				redirect('404_override');
			}				

		}

		$preguntas = [];

		$consultar_formulario_activo = $this->Formularios_model->consultar_activo($idformulario);

		if( empty($consultar_formulario_activo)){

			$data = array("idformulario" => $idformulario);
			$consultar_preguntas = $this->Preguntas_model->consultar_by_data($data);

			foreach($consultar_preguntas as $key => $pregunta) {

				$data_opciones = array("idpregunta" => $pregunta->idpregunta);
				$consultar_opciones = $this->Opciones_model->consultar_by_data($data_opciones);

				$preguntas[$key] = [
					"nombre" => $pregunta->nombre,
					"requerido" => $pregunta->requerido,
					"tipo_pregunta" => $pregunta->idtipo_pregunta,
					"opciones" => $consultar_opciones
				];
			}
		}

		$data_usuario = array();
		if($this->session->USER_ROL != ROL_ADMIN){
			$data_usuario = array("idusuario_creacion" => $this->session->UID);
		}

		$consultar_estudiante = $this->Formularios_model->consultar_participante_by_idformulario($idformulario);

		$this->lm->add_css('jquery-datetimepicker/jquery-datetimepicker.min.css');
		$this->lm->add_css('formulario.css');

		$this->lm->add_js('jquery-datetimepicker/jquery-datetimepicker-full.min.js');
		$this->lm->add_js('formularios/modificar.js');
		
		$this->lm->set_title('Formularios - Modificar');
    $this->lm->set_page('formularios/modificar');		

    $consultar_tipo_pregunta = $this->Preguntas_model->consultar_tipo_pregunta();
		$this->lm->add_jsvars(["consultar_tipo_pregunta" => $consultar_tipo_pregunta]);
		
		$data = array("consultar_formulario" => $consultar_formulario, "preguntas" => $preguntas, "consultar_estudiante" => $consultar_estudiante );
		$this->lm->render($data);

  }

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de formularios
	 * @return json
	 */
	public function actualizar(){
		
		$idformulario = $this->input->post("idformulario", TRUE);
		$nombre = $this->input->post("nombre", TRUE);
		$descripcion = $this->input->post("descripcion", TRUE);
		$estado = $this->input->post("estado", TRUE);
		$fecha_final = $this->input->post("fecha_final", TRUE);
		$fecha_inicio = $this->input->post("fecha_inicio", TRUE);
		$pregunta = $this->input->post("pregunta", TRUE);
		$usuarios = $this->input->post("usuarios", TRUE);

		if( empty( $idformulario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_formulario = $this->Formularios_model->consultar_by_id($idformulario);

		if( empty( $consultar_formulario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if($this->session->USER_ROL != ROL_ADMIN){

			if($consultar_formulario->idusuario_creacion != $this->session->UID){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para actualizar este formulario" ) ) );
			}				

		}
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $estado ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado" ) ) );
		}

		if( $estado != FORMULARIO_ACTIVO && $estado != FORMULARIO_INACTIVO && $estado != FORMULARIO_PROGRAMADO ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un estado valido" ) ) );
		}

		if($estado == FORMULARIO_PROGRAMADO){

			if( ! validar_fecha($fecha_inicio, 'Y-m-d') ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha inicial valida" ) ) );
			}

			if( ! validar_fecha($fecha_final, 'Y-m-d') ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha final valida" ) ) );
			}

			if(strtotime($fecha_inicio) > strtotime($fecha_final)){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fecha de inicio no puede ser mayor a la fecha de finalización" ) ) );
			}

		}else{
			$fecha_final = NULL;
			$fecha_inicio = NULL;
		}

		$data = array("nombre"=>$nombre,"descripcion"=>$descripcion,"estado"=>$estado,"fecha_inicio"=>$fecha_inicio,"fecha_final"=>$fecha_final);
		$modificar_formulario = $this->Formularios_model->modificar($idformulario, $data);

		$this->db->trans_begin();

		if( empty($modificar_formulario)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no se modifico correctamente" ) ) );
		}

		if( empty($usuarios)){
			$usuarios = array();
		}

		$data_formulario_participante = array("idformulario" => $idformulario);
		$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

		$usuario_registrados = [];
		foreach($consultar_participante as $participante){
			$usuario_registrados[] = $participante->idusuario;
		}

		$usuarios_noregistrados = array_merge(array_diff($usuarios, $usuario_registrados), array_diff($usuario_registrados, $usuarios));

		for ($i=0; $i < count($usuarios_noregistrados); $i++) { 
			
			$consultar_usuarios = $this->Usuarios_model->consultar_by_id($usuarios_noregistrados[$i]);
					
			if( empty($consultar_usuarios)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ));
			}

			if( $this->session->USER_ROL == ROL_PROFESOR ){
				if( $consultar_usuarios->idrol != ROL_ESTUDIANTE) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ));
				}
			}

			$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $consultar_usuarios->idusuario);
			$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

			if( empty($consultar_participante) ){

				$data_formulario_participante = array("idusuario" => $consultar_usuarios->idusuario, "idformulario" => $idformulario);
				$ingresar_formulario = $this->Formularios_model->ingresar_formulario_participante($data_formulario_participante);

				if( empty($ingresar_formulario)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El participante no se creo correctamente" ) ) );
				}

			} else {
				
				if( $consultar_participante[0]->estado == 1 ){
				
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario " . $consultar_usuarios->nombre . " no se puede eliminar de lista de participantes, porque tiene una respuesta registrada " ) ) );
				
				} else {

					$eliminar_participante = $this->Formularios_model->eliminar_formulario_participante($idformulario, $consultar_usuarios->idusuario);

					if( empty($eliminar_participante)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El participante no se borro correctamente" ) ) );
					}
				
				}
			}
		}

		$consultar_formulario_activo = $this->Formularios_model->consultar_activo($idformulario);

		if( empty($consultar_formulario_activo)){

			$eliminar_preguntas = $this->Preguntas_model->eliminar_by_idformulario($idformulario);
			
			if( empty( $eliminar_preguntas ) ) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no se modifico correctamente" ) ) );
			}	

			if( empty( $pregunta ) ) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese al menos una pregunta" ) ) );
			}

			for ($i=0; $i < count($pregunta); $i++) { 
				
				if( empty($pregunta[$i]["nombre"])) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre de la pregunta" ) ) );
				}
				
				if( empty($pregunta[$i]["tipo-pregunta"])) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el tipo de pregunta" ) ) );
				}
		
				if( $pregunta[$i]["tipo-pregunta"] != 1 && $pregunta[$i]["tipo-pregunta"] != 2) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un tipo de pregunta valido" ) ) );
				}

				if( ! isset($pregunta[$i]["obligatorio"])) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el requerido de la pregunta" ) ) );
				}

				if( $pregunta[$i]["obligatorio"] != 1 && $pregunta[$i]["obligatorio"] != 0) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un requerido válido" ) ) );
				}

				$data = array("nombre"=>$pregunta[$i]["nombre"],"requerido"=>$pregunta[$i]["obligatorio"],"idformulario"=>$idformulario,"idtipo_pregunta"=>$pregunta[$i]["tipo-pregunta"],"posicion"=>($i+1));

				$idpregunta = $this->Preguntas_model->ingresar($data);

				if( empty($idpregunta)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La pregunta no se creo correctamente" ) ) );
				}

				if( $pregunta[$i]["tipo-pregunta"] == 1 ) {

					if( empty($pregunta[$i]["opciones"])) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese almenos una opción de pregunta" ) ) );
					}

					for ($j=0; $j < count($pregunta[$i]["opciones"]); $j++) { 

						$data = array("nombre"=>$pregunta[$i]["opciones"][$j], "idpregunta"=>$idpregunta,"posicion"=>($j+1));

						$idopcion = $this->Opciones_model->ingresar($data);

						if( empty($idopcion)) {
							$this->db->trans_rollback();
							die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La opción no se creo correctamente" ) ) );
						}
					}
				}
			}

		}
		

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El formulario se modifico correctamente" ) );


  }
  
  public function crear(){
		$this->lm->add_css('jquery-datetimepicker/jquery-datetimepicker.min.css');
		$this->lm->add_css('formulario.css');

		$this->lm->add_js('jquery-datetimepicker/jquery-datetimepicker-full.min.js');
		$this->lm->add_js('formularios/crear.js');
		
		$this->lm->set_title('Formularios - Crear');
		$this->lm->set_page('formularios/crear');
	
		$data_grupo = array();
		$data_usuario = array("idrol" => ROL_ESTUDIANTE);
		$consultar_profesor = array();

		if($this->session->USER_ROL == ROL_ADMIN){
			$data_usuario = array("idrol" => ROL_PROFESOR);
			$consultar_profesor = $this->Usuarios_model->consultar_by_data($data_usuario);
		}else if($this->session->USER_ROL == ROL_PROFESOR){
			$data_grupo = array("idusuario_creacion" => $this->session->UID);
		}

		$consultar_estudiante = $this->Usuarios_model->consultar_by_data($data_usuario);
		$consultar_grupo = $this->Grupos_model->consultar_by_data($data_grupo);
		$consultar_tipo_pregunta = $this->Preguntas_model->consultar_tipo_pregunta();
		
		$this->lm->add_jsvars(["consultar_tipo_pregunta" => $consultar_tipo_pregunta]);
		$data = array("consultar_grupo" => $consultar_grupo, "consultar_estudiante" => $consultar_estudiante, "consultar_profesor" => $consultar_profesor);
		$this->lm->render($data);
  }

	//-----------------------------------------------------------------------------
	/**
	 * creación de formularios
	 * @return json
	 */
	public function insertar(){

		$nombre = $this->input->post("nombre", TRUE);
		$descripcion = $this->input->post("descripcion", TRUE);
		$estado = $this->input->post("estado", TRUE);
		$fecha_final = $this->input->post("fecha_final", TRUE);
		$fecha_inicio = $this->input->post("fecha_inicio", TRUE);
		$pregunta = $this->input->post("pregunta", TRUE);
		$grupos = $this->input->post("grupos", TRUE);
		$usuarios = $this->input->post("usuarios", TRUE);
		$profesores = $this->input->post("profesores", TRUE);
		$todos_usuarios = $this->input->post("todos_usuarios", TRUE);
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $estado ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado" ) ) );
		}

		if( empty( $pregunta ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese al menos una pregunta" ) ) );
		}

		if( $estado != FORMULARIO_ACTIVO && $estado != FORMULARIO_INACTIVO && $estado != FORMULARIO_PROGRAMADO ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un estado valido" ) ) );
		}

		if($estado == FORMULARIO_PROGRAMADO){

			if( ! validar_fecha($fecha_inicio, 'Y-m-d') ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha inicial valida" ) ) );
			}

			if( ! validar_fecha($fecha_final, 'Y-m-d') ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha final valida" ) ) );
			}

			if(strtotime($fecha_inicio) > strtotime($fecha_final)){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fecha de inicio no puede ser mayor a la fecha de finalización" ) ) );
			}

		}else{
			$fecha_final = NULL;
			$fecha_inicio = NULL;
		}

		$data = array("nombre"=>$nombre,"descripcion"=>$descripcion,"estado"=>$estado,"fecha_inicio"=>$fecha_inicio,"fecha_final"=>$fecha_final, "idusuario_creacion" => $this->session->UID);
		$idformulario = $this->Formularios_model->ingresar($data);

		$this->db->trans_begin();

		if( empty($idformulario)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no se creo correctamente" ) ) );
		}

		if( ! empty($todos_usuarios)){

			$data_usuario = array("estado" => USUARIO_ACTIVO, "idrol" => ROL_ESTUDIANTE);
			$consultar_usuario = $this->Usuarios_model->consultar_by_data($data_usuario);

			foreach($consultar_usuario as $usuario){

				$data_formulario_participante = array("idusuario" => $usuario->idusuario, "idformulario" => $idformulario);
				$ingresar_formulario = $this->Formularios_model->ingresar_formulario_participante($data_formulario_participante);

				if( empty($ingresar_formulario)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El participante no se creo correctamente" ) ) );
				}

			}

		} else {

			if( ! empty($grupos)){

				for ($i=0; $i < count($grupos); $i++) { 
					$data_grupo = array("idgrupo" => $grupos[$i]);			
					$consultar_grupos = $this->Grupos_model->consultar_grupo_by_data($data_grupo);			
					
					if( empty($consultar_grupos)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo ingresado, no existe" ) ));
					}

					foreach ($consultar_grupos as $row_grupos) {

						$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $row_grupos->idusuario);
						$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

						if(empty($consultar_participante)){

							$consultar_usuarios = $this->Usuarios_model->consultar_by_id($row_grupos->idusuario);

							if( empty($consultar_usuarios)) {
								$this->db->trans_rollback();
								die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario ingresado, no existe" ) ));
							}

							if($consultar_usuarios->estado != USUARIO_INACTIVO){

								$data_formulario_participante = array("idusuario" => $consultar_usuarios->idusuario, "idformulario" => $idformulario);
								$ingresar_formulario = $this->Formularios_model->ingresar_formulario_participante($data_formulario_participante);

								if( empty($ingresar_formulario)) {
									$this->db->trans_rollback();
									die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El participante no se creo correctamente" ) ) );
								}
							}
						}
					}
				}
			}

			if( ! empty($usuarios)){

				for ($i=0; $i < count($usuarios); $i++) { 

					$consultar_usuarios = $this->Usuarios_model->consultar_by_id($usuarios[$i]);
					
					if( empty($consultar_usuarios)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El grupo ingresado, no existe" ) ));
					}

					if($consultar_usuarios->estado != USUARIO_INACTIVO){

						$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $usuarios[$i]);
						$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

						if(empty($consultar_participante)){
							$data_formulario_participante = array("idusuario" => $consultar_usuarios->idusuario, "idformulario" => $idformulario);
							$ingresar_formulario = $this->Formularios_model->ingresar_formulario_participante($data_formulario_participante);

							if( empty($ingresar_formulario)) {
								$this->db->trans_rollback();
								die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El participante no se creo correctamente" ) ) );
							}
						}
					}
				}
			}
		}

		if( ! empty($profesores) && $this->session->USER_ROL == ROL_ADMIN){

			for ($i=0; $i < count($profesores); $i++) { 
	
				$consultar_profesores = $this->Usuarios_model->consultar_by_id($profesores[$i]);
				
				if( empty($consultar_profesores)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario ingresado, no existe" ) ));
				}

				if($consultar_profesores->estado != USUARIO_INACTIVO){

					$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $profesores[$i]);
					$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

					if(empty($consultar_participante)){
						$data_formulario_participante = array("idusuario" => $consultar_profesores->idusuario, "idformulario" => $idformulario);
						$ingresar_formulario = $this->Formularios_model->ingresar_formulario_participante($data_formulario_participante);

						if( empty($ingresar_formulario)) {
							$this->db->trans_rollback();
							die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El participante no se creo correctamente" ) ) );
						}
					}
				}
			}
		}

		for ($i=0; $i < count($pregunta); $i++) { 
			
			if( empty($pregunta[$i]["nombre"])) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre de la pregunta" ) ) );
			}
			
			if( empty($pregunta[$i]["tipo-pregunta"])) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el tipo de pregunta" ) ) );
			}
	
			if( $pregunta[$i]["tipo-pregunta"] != 1 && $pregunta[$i]["tipo-pregunta"] != 2) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un tipo de pregunta valido" ) ) );
			}

			if( ! isset($pregunta[$i]["obligatorio"])) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el requerido de la pregunta" ) ) );
			}

			if( $pregunta[$i]["obligatorio"] != 1 && $pregunta[$i]["obligatorio"] != 0) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un requerido válido" ) ) );
			}

			$data = array("nombre"=>$pregunta[$i]["nombre"],"requerido"=>$pregunta[$i]["obligatorio"],"idformulario"=>$idformulario,"idtipo_pregunta"=>$pregunta[$i]["tipo-pregunta"],"posicion"=>($i+1));

			$idpregunta = $this->Preguntas_model->ingresar($data);

			if( empty($idpregunta)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La pregunta no se creo correctamente" ) ) );
			}

			if( $pregunta[$i]["tipo-pregunta"] == 1 ) {

				if( empty($pregunta[$i]["opciones"])) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese almenos una opción de pregunta" ) ) );
				}

				for ($j=0; $j < count($pregunta[$i]["opciones"]); $j++) { 

					$data = array("nombre"=>$pregunta[$i]["opciones"][$j], "idpregunta"=>$idpregunta,"posicion"=>($j+1));

					$idopcion = $this->Opciones_model->ingresar($data);

					if( empty($idopcion)) {
						$this->db->trans_rollback();
						die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La opción no se creo correctamente" ) ) );
					}
				}
			}
		}

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El formulario fue ingresado correctamente" ) );
	}

	public function eliminar(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Formularios_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no existe" ) ) );
		}

		if($this->session->USER_ROL != ROL_ADMIN){

			if($data->idusuario_creacion != $this->session->UID){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para eliminar este formulario" ) ) );
			}				

		}

		$consultar_formulario_activo = $this->Formularios_model->consultar_activo($id);

		if( ! empty($consultar_formulario_activo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No es posible eliminar el formulario, tiene respuestas registradas" ) ) );
		}

		$data = $this->Formularios_model->eliminar($id);
		
		if(empty($data)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no se elimino" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El formulario se elimino correctamente! " ) );

	}

	public function ver_formulario($idformulario) {

		if( empty( $idformulario ) ){
			redirect('404_override');
		}

		$consultar_formulario = $this->Formularios_model->consultar_by_id($idformulario);

		if( empty( $consultar_formulario ) ){
			redirect('404_override');
		}

		$preguntas = [];
		$estado = "";

		if( $this->session->USER_ROL == ROL_ADMIN ){
			$consultar_formulario_activo = FALSE;
		}else if ($this->session->USER_ROL == ROL_PROFESOR){
			
			$consultar_formulario_activo = FALSE;

			if($consultar_formulario->idusuario_creacion != $this->session->UID){
				
				$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $this->session->UID);
				$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

				if( empty($consultar_participante)) {
					redirect('404_override');
				}

			}	
			
		}else{

			$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $this->session->UID);
			$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

			if( empty($consultar_participante)) {
				redirect('404_override');
			}

			if($consultar_formulario->estado == FORMULARIO_PROGRAMADO ){

				if( strtotime($consultar_formulario->fecha_inicio) > strtotime(date('Y-m-d')) || strtotime($consultar_formulario->fecha_final) <  strtotime(date('Y-m-d')) ){
					$estado = "El formulario no esta disponible.";
				}

			}else if($consultar_formulario->estado == FORMULARIO_INACTIVO ){
				$estado = "El formulario no esta disponible.";
			}

			if($consultar_participante[0]->estado == 1 ){
				$estado = "El formulario ya fue enviado";
			}

		}

		if( empty($estado)) {

			$data = array("idformulario" => $idformulario);
			$consultar_preguntas = $this->Preguntas_model->consultar_by_data($data);

			foreach($consultar_preguntas as $key => $pregunta) {

				$data_opciones = array("idpregunta" => $pregunta->idpregunta);
				$consultar_opciones = $this->Opciones_model->consultar_by_data($data_opciones);

				$preguntas[$key] = [
					"idpregunta" => $pregunta->idpregunta,
					"nombre" => $pregunta->nombre,
					"requerido" => $pregunta->requerido,
					"tipo_pregunta" => $pregunta->idtipo_pregunta,
					"opciones" => $consultar_opciones
				];
			}
			
		}

		$this->lm->add_css('formulario.css');
		$this->lm->add_js('formularios/ver_formulario.js');
		
		$this->lm->set_title('Formularios - Ver formulario');
    $this->lm->set_page('formularios/ver_formulario');		

		$data = array("consultar_formulario" => $consultar_formulario, "preguntas" => $preguntas, "estado" => $estado);
		$this->lm->render($data);

	}

	public function ver_respuesta($idformulario) {
		
		if( empty( $idformulario ) ){
			redirect('404_override');
		}

		$consultar_formulario = $this->Formularios_model->consultar_by_id($idformulario);

		if( empty( $consultar_formulario ) ){
			redirect('404_override');
		}


		if ($this->session->USER_ROL != ROL_ADMIN){
			if($consultar_formulario->idusuario_creacion != $this->session->UID){
				redirect('404_override');
			}
		}

		$consultar_participantes = $this->Formularios_model->consultar_usuario_participante_by_idformulario($idformulario, 1);


		$this->lm->add_js('hightcharts/highcharts');
		$this->lm->add_js('hightcharts/modules/exporting');
		$this->lm->add_js('hightcharts/modules/export-data');
		$this->lm->add_css('formulario.css');
		$this->lm->add_js('formularios/ver_respuesta.js');
		
		$this->lm->set_title('Formularios - Respuesta');
    $this->lm->set_page('formularios/ver_respuesta');		

		$data = array("consultar_formulario" => $consultar_formulario, "conteo_participantes" => count($consultar_participantes), "consultar_participantes" => $consultar_participantes);
		$this->lm->render($data);

	}

	public function obtener_respuesta($idformulario){

		$idparticipante = $this->input->post("idparticipante", TRUE );

		if( empty( $idformulario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_formulario = $this->Formularios_model->consultar_by_id($idformulario);

		if( empty( $consultar_formulario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if ($this->session->USER_ROL != ROL_ADMIN){
			
			if($consultar_formulario->idusuario_creacion != $this->session->UID){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		
		}

		$data = array("idformulario" => $idformulario);
		$consultar_preguntas = $this->Preguntas_model->consultar_by_data($data);

		foreach($consultar_preguntas as $key => $pregunta) {

			$consultar_respuesta = array();
			$array_opciones = array();
			if($pregunta->idtipo_pregunta == 1 ){

				$data_opciones = array("idpregunta" => $pregunta->idpregunta);
				$consultar_opciones = $this->Opciones_model->consultar_by_data($data_opciones);

				$data_respuesta = array("idpregunta" => $pregunta->idpregunta);
				$contador_opciones = $this->Respuestas_model->consultar_by_data_conteo($data_respuesta);
				foreach( $consultar_opciones as $opciones){

					if(!empty($idparticipante)){
						$data_respuesta = array("idpregunta" => $pregunta->idpregunta, "idopcion" => $opciones->idopcion, "idparticipante" => $idparticipante);
					}else{
						$data_respuesta = array("idpregunta" => $pregunta->idpregunta, "idopcion" => $opciones->idopcion);
					}
					
					$cantidad_respuesta = $this->Respuestas_model->consultar_by_data_conteo($data_respuesta);

					$contador_opciones = ($contador_opciones == 0) ? 1 : $contador_opciones;

					$array_opciones[] = [
						"name" => $opciones->nombre . " - " . $cantidad_respuesta,
						"y" => ($cantidad_respuesta * 100) / $contador_opciones
					];
				}

			} else {

				if(!empty($idparticipante)){
					$data_respuesta = array("idpregunta" => $pregunta->idpregunta, "idparticipante" => $idparticipante);
				}else{
					$data_respuesta = array("idpregunta" => $pregunta->idpregunta);
				}

				$consultar_respuesta = $this->Respuestas_model->consultar_by_data($data_respuesta);

			}
			
			$preguntas[$key] = [
				"idpregunta" => $pregunta->idpregunta,
				"nombre" => $pregunta->nombre,
				"tipo_pregunta" => $pregunta->idtipo_pregunta,
				"opciones" => $array_opciones,
				"valor" => $consultar_respuesta
			];
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "Datos enviados", "data" => $preguntas ) ) ;

	}

	public function agregar_respuesta($idformulario){

		$preguntas = $this->input->post("preguntas", TRUE);

		if( empty( $idformulario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $preguntas ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese las preguntas" ) ) );
		}

		$consultar_formulario = $this->Formularios_model->consultar_by_id($idformulario);

		if( empty( $consultar_formulario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if($consultar_formulario->estado == FORMULARIO_INACTIVO ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no esta disponible" ) ) );
		}

		if($consultar_formulario->estado == FORMULARIO_PROGRAMADO ){

			if( strtotime($consultar_formulario->fecha_inicio) > strtotime(date('Y-m-d')) || strtotime($consultar_formulario->fecha_final) <  strtotime(date('Y-m-d')) ){
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no esta disponible" ) ) );
			}
		}

		$data_formulario_participante = array("idformulario" => $idformulario, "idusuario" => $this->session->UID);
		$consultar_participante = $this->Formularios_model->consultar_by_data_formulario_participante($data_formulario_participante);

		if( empty($consultar_participante) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para rellenar el formulario, porque no eres un participante" ) ) );
		}

		if($consultar_participante[0]->estado == 1 ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario ya fue enviado" ) ) );
		}

		$data = array("idformulario" => $idformulario);
		$consultar_preguntas = $this->Preguntas_model->consultar_by_data($data);

		$this->db->trans_begin();

		$data = array("estado" => 1);
		$modificar_formulario_participante = $this->Formularios_model->modificar_formulario_participante($consultar_participante[0]->idformulario_participante, $data);

		if( empty($modificar_formulario_participante) ){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El formulario no se modifico correctamente" ) ) );
		}

		foreach($consultar_preguntas as $key => $consultar_pregunta) {

			$validadorPregunta = false;
			$valor = NULL;
			for ($i=0; $i < count($preguntas); $i++) {
				if($preguntas[$i]["idpregunta"] == $consultar_pregunta->idpregunta) {
					$validadorPregunta = true;
					$valorPregunta = NULL;
					if(  empty(  $preguntas[$i]["valor"] )){
						if( $consultar_pregunta->requerido == 1){
							die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor seleccione la respuesta: " . $consultar_pregunta->nombre ) ) );
						}
					}else{
						$valorPregunta = $preguntas[$i]["valor"];
					}
					
					$valor = $valorPregunta;
					break;
				}
			}

			if( empty($validadorPregunta)){
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La pregunta no se encuentra registrada" ) ) );
			}

			if($consultar_pregunta->idtipo_pregunta == 1){

				$data_opciones = array("idopcion" => $valor, "idpregunta" => $consultar_pregunta->idpregunta);
				$consultar_opciones = $this->Opciones_model->consultar_by_data($data_opciones);

				if( empty($consultar_opciones) && $consultar_pregunta->requerido == 1){
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La opción ingresada no existe" ) ) );
				}

				$data_respuesta = array("idparticipante" => $consultar_participante[0]->idformulario_participante, "idpregunta" => $consultar_pregunta->idpregunta, "idopcion" => $valor);
				$ingresar_respuesta = $this->Respuestas_model->ingresar($data_respuesta);

				if( empty($ingresar_respuesta)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La respuesta no se creo correctamente" ) ) );
				}

			} else {

				$data_respuesta = array("idparticipante" => $consultar_participante[0]->idformulario_participante, "idpregunta" => $consultar_pregunta->idpregunta, "idopcion" => NULL, "valor" => $valor);
				$ingresar_respuesta = $this->Respuestas_model->ingresar($data_respuesta);

				if( empty($ingresar_respuesta)) {
					$this->db->trans_rollback();
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La respuesta no se creo correctamente" ) ) );
				}
			}
		}

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "!El formulario se envio correctamente! ", "url" => base_url('proyectos') ) );
	}
}