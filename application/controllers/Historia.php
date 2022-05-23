<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class Historia extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Proyectos_model");
        $this->load->model("Metodologias_model");
        $this->load->model("Fases_model");
        $this->load->model("Modulos_model");
        $this->load->model("Historias_model");
				$this->load->model("Vistas_model");
		$this->load->model("Usuarios_model");
		$this->load->model("Categorias_model");
		$this->load->model("Presupuestos_model");
        $this->load->helper(array('download'));
        $this->load->helper(array('phpmailer_helper'));
    }


    public function listar($url_proyecto, $url_metodologia, $url_fase, $url_modulo){

		if(empty($url_proyecto) || empty($url_metodologia) || empty($url_fase) || empty($url_modulo)){
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

		$data_fase = array("url" => $url_fase);
		$consultar_fase = $this->Fases_model->consultar_by_data($data_fase);

		if(empty($consultar_fase)) {
			redirect("errores/not_found");	
		}

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			redirect("errores/not_found");	
		}

		$puedeCrear = true;
		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				$puedeCrear = false;
				if($consultar_proyecto[0]->proyecto_base == 0){
					redirect("errores/not_found");	
				}
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				$puedeCrear = false;
				if($consultar_proyecto[0]->proyecto_base == 0){
					redirect("errores/not_found");	
				}
			}
		}

		$consultar_prioridad = $this->Historias_model->consultar_by_prioridad_by_data(array());
		$consultar_riesgo_desarrollo = $this->Historias_model->consultar_riesgo_desarrollo_by_data(array());

		$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_id_proyecto($consultar_proyecto[0]->idproyecto);

		$consultar_categoria = $this->Categorias_model->consultar();
			
		$this->lm->set_title('Historia de usuarios');
		$this->lm->set_page('historiausuario/listar');
		$this->lm->add_js('historiausuario');
		$this->lm->add_js('jquery-UI/jquery-ui.js');
		$this->lm->add_css('daterangepicker/daterangepicker.css');
		$this->lm->add_js('daterangepicker/daterangepicker.min.js');
		
		$this->lm->add_css('calendar/core/main.css');
        $this->lm->add_css('calendar/daygrid/main.css');
        $this->lm->add_css('calendar/timegrid/main.css');
        
        $this->lm->add_js('calendar/core/main.js');
        $this->lm->add_js('calendar/interaction/main.js');
        $this->lm->add_js('calendar/daygrid/main.js');
        $this->lm->add_js('calendar/timegrid/main.js');

		$this->lm->add_js('calendar/core/locales/es.js');

		$this->lm->add_jsvars(array("url_metodologia" => $url_metodologia, "url_proyecto" => $url_proyecto, "url_fase" => $url_fase, "url_modulo" => $url_modulo, "fecha_inicio" => $consultar_proyecto[0]->fecha_inicio, "fecha_finalizacion" => $consultar_proyecto[0]->fecha_finalizacion));

		$function = [];

		if( $puedeCrear ) {
			if($consultar_modulo[0]->estado != MODULO_FINALIZADO){

				$function[] = [
							"method" 		=> "crear", 
							"name"			=> "add_button", 
						"id"			=> "add_button", 
						"classes" 		=> "btn btn-primary btn-flat", 
						"title" 		=> "Nuevo", 
						"text"			=> "Crear Historia",
						"attributes" 	=> "",
						"link" 			=> FALSE
					];

					$function[] = [
							"method" 		=> "finalizar_modulo", 
							"name"			=> "finalizar_modulo", 
						"id"			=> "finalizar_modulo", 
						"classes" 		=> "btn btn-light-primary btn-flat", 
						"title" 		=> "Finalizar modulo", 
						"text"			=> "Finalizar modulo",
						"attributes" 	=> "",
						"link" 			=> FALSE
					];
			}
		
			if($consultar_modulo[0]->estado == MODULO_FINALIZADO){
				$function[] = [
						"method" 		=> "abrir_modulo", 
						"name"			=> "abrir_modulo", 
						"id"			=> "abrir_modulo", 
						"classes" 		=> "btn btn-light-success btn-flat", 
						"title" 		=> "Abrir modulo", 
						"text"			=> "Abrir modulo",
						"attributes" 	=> "",
						"link" 			=> FALSE
				];
			}
		}

    $botones = $this->btn->get_buttons($function);

		$data = array("nombre"=>$consultar_proyecto[0]->nombre, "nombre_metodologia"=>$consultar_metodologia[0]->nombre, "nombre_fase"=>$consultar_fase[0]->nombre, "url_metodologia" => $url_metodologia, "url_proyecto" => $url_proyecto, "nombre_modulo" => $consultar_modulo[0]->nombre, "url_fase" => $url_fase, "url_modulo" => $url_modulo, "consultar_prioridad" => $consultar_prioridad, "botones" => $botones, "consultar_riesgo_desarrollo" => $consultar_riesgo_desarrollo, "consultar_miembro" => $consultar_miembro, "consultar_categoria" => $consultar_categoria);

		// print_r($data);
		// return;

		$this->lm->render($data);
	}


	public function obtener_historia(){
		
		$url_proyecto = $this->input->post("url_proyecto", true);

		if( empty( $url_proyecto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		$url_metodologia = $this->input->post("url_metodologia", true);

		if( empty( $url_metodologia ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		$url_fase = $this->input->post("url_fase", true);

		if( empty( $url_fase ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$data_fase = array("url" => $url_fase);
		$consultar_fase = $this->Fases_model->consultar_by_data($data_fase);

		if( empty( $consultar_fase ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$data_proyecto_metodologia = array("idproyecto_metodologia" => $consultar_fase[0]->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($consultar_proyecto_metodologia[0]->idproyecto);

		if(empty($consultar_proyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}
		
		$puedeCrear = true;
		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				$puedeCrear = false;
				if($consultar_proyecto->proyecto_base == 0){
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
				}
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				$puedeCrear = false;
				if($consultar_proyecto->proyecto_base == 0){
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
				}
			}
		}

		$url_modulo = $this->input->post("url_modulo", true);

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$data_historias = array("idmodulo" => $consultar_modulo[0]->idmodulo);
		$consultar_historias = $this->Historias_model->consultar_by_data($data_historias);

		$datos =  array();
		foreach($consultar_historias as $row)
		{
			$sub_array = array();
			$created_at = formato_fecha(date('Y-m-d', strtotime($row->created_at)));
			
			$consultar_usuario = $this->Usuarios_model->consultar_by_id($row->idusuario);
			$consultar_prioridad = $this->Historias_model->consultar_by_prioridad_by_id($row->idprioridad);
			$consultar_riesgo_desarrollo = $this->Historias_model->consultar_riesgo_desarrollo_by_id($row->idriesgo_desarrollo);

			$data_archivo = array("idhistoria" => $row->idhistoria);
			$consultar_archivos = $this->Historias_model->consultar_archivo_historia_by_data($data_archivo);
			$fecha_fin = formato_fecha($row->fecha_fin);

			$estado = "En proceso";
			$color = "#f39c12";
			$estadoColor = "warning";

      if($row->estado == HISTORIA_PENDIENTE){
				$estado = "Pendiente";
				$color = "#f39c12";
				$estadoColor = "warning";
      }else if($row->estado == HISTORIA_APROBADA){
				$estado = "Aprobada";
				$color = "#00a65a";
				$estadoColor = "success";
      }else if($row->estado == HISTORIA_ENTREGADA){
				$estado = "Entregado";
				$color = "#00a65a";
				$estadoColor = "success";
      }else if($row->estado == HISTORIA_INCOMPLETA){
				$estado = "Incompleta";
				$color = "#f39c12";
				$estadoColor = "danger";
      }else if($row->estado == HISTORIA_FINALIZADA){
				$estado = "Finalizada";
				$color = "#00a65a";
				$estadoColor = "success";
      }

      if($row->fecha_fin < date("Y-m-d") && ($row->estado != HISTORIA_FINALIZADA &&  $row->estado != HISTORIA_ENTREGADA) ){
				$color = "#dd4b39";
      }

			$sub_array[] = $row->idhistoria;
			$sub_array[] = $row->titulo;
			$sub_array[] = $fecha_fin["dia"] . " de " . $fecha_fin["mes"] . " de " . $fecha_fin["ano"];
			$sub_array[] = $row->objetivo;
			$sub_array[] = $consultar_riesgo_desarrollo->nombre;
			$sub_array[] = $row->descripcion;
			$sub_array[] = $row->observaciones;
			$sub_array[] = $row->tiempo_estimado;
			$sub_array[] = $estado;
			$sub_array[] = $consultar_usuario->nombre . " " . $consultar_usuario->apellido;
			$sub_array["foto_usuario"] = ( ! empty($consultar_usuario->ruta_imagen)) ? base_url('uploads/'.$consultar_usuario->ruta_imagen) : base_url('assets/public/') . 'img/imagen3.jpg'; 
			$sub_array["url_usuario"] =  base_url('usuarios/perfil/'.$consultar_usuario->idusuario);
			$sub_array[] = $created_at["dia"] . " de " . $created_at["mes"] . " de " . $created_at["ano"];
			$sub_array[] = $consultar_prioridad->nombre;
			$sub_array["archivos"] = $consultar_archivos;
			$sub_array["idhistoria"] = $row->idhistoria;
			$sub_array["estado"] = $row->estado;
			$sub_array["fecha_ini"] = $row->fecha_ini;
			$sub_array["fecha_fin"] = $row->fecha_fin;

			$sub_array["color"] = $color;
			$sub_array["estadoColor"] = $estadoColor;

			$function = [];

			$function[] = [
				"method" 		=> "ver_pdf", 
				"name"			=> "ver_pdf", 
				"id"			  => $row->idhistoria, 
				"classes"   => "btn btn-primary", 
				"title" 		=> "",
				"icon"			=> "fas fa-file-pdf",
				"attributes"=> "target='_blank'",
				"link" 			=> TRUE,
				"ruta"      => base_url("historia/ver_pdf/".$row->idhistoria)
			];

			if( $puedeCrear ){
				if($row->estado == HISTORIA_PENDIENTE || $row->estado == HISTORIA_INCOMPLETA){
					$function[] = [
						"method" 		=> "actualizar", 
						"name"			=> "update", 
						"id"			=> $row->idhistoria, 
						"classes" 		=> "btn btn-success update",
						"icon" 			=> "flaticon-edit",
						"attributes" 	=> "",
						"link" 			=> FALSE
					];
					$function[] = [
						"method" 			=> "eliminar", 
						"name"				=> "delete", 
						"id"					=> $row->idhistoria, 
						"classes" 		=> "btn btn-danger delete", 
						"icon"				=> "flaticon2-trash",
						"attributes" 	=> "",
						"link" 				=> FALSE
					];

					$function[] = [
						"method" 		=> "entregar", 
						"name"			=> "entregada", 
						"id"			=> $row->idhistoria, 
						"classes" 		=> "btn btn-primary entregada", 
						"icon"			=> "flaticon2-check-mark",
						"title" 		=> "Entregar Historia",
						"attributes" 	=> "",
						"link" 			=> FALSE
					];	
							
					$function[] = [
						"method" 		=> "presupuesto", 
						"name"			=> "presupuesto", 
						"id"			=> $row->idhistoria, 
						"classes" 		=> "btn btn-success presupuesto", 
						"icon"			=> "fas fa-dollar-sign",
						"title" 		=> "Agregar presupuesto",
						"attributes" 	=> "",
						"link" 			=> FALSE
					];

				}

				if($row->estado == HISTORIA_FINALIZADA) {  	
					$function[] = [
						"method" 		=> "cambiar_estado", 
						"name"			=> "incompleta", 
						"id"			=> $row->idhistoria, 
						"classes" 		=> "btn btn-icon btn-danger incompleta", 
						"title" 		=> "Marcar historia como incompleta",
						"icon"			=> "fas fa-ban",
						"text"			=> ""
					];
				}
			}
			
			$presupuesto = "$ " . number_format($this->Presupuestos_model->consultar_presupuesto($row->idhistoria), 2, ",", ".");
      $botones = $this->btn->get_buttons($function);
			$sub_array["botones"] = $botones;
			$sub_array["presupuesto"] = $presupuesto;
			$datos[] = $sub_array;
    }
    echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "Historias registradas", "data" => $datos ) );
	}
	
	//-----------------------------------------------------------------------------
	/**
	 * creación de historia
	 * @return json
	 */
	public function crear(){
		$titulo = ucwords($this->input->post("titulo", TRUE));
        $fecha_ini = $this->input->post("fecha_ini", TRUE);
		$objetivo = $this->input->post("objetivo", TRUE);
        $prioridad = $this->input->post("prioridad", TRUE);
        $riesgodesarrollo = $this->input->post("riesgodesarrollo", TRUE);
		$numeracion = $this->input->post("numeracion", TRUE);
        $descripcion = $this->input->post("descripcion", TRUE);
        $url_proyecto = $this->input->post("url_proyecto", TRUE);
		$url_modulo = $this->input->post("url_modulo", TRUE);
		$responsable = $this->input->post("responsable", TRUE);

		if( empty( $url_proyecto ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la url" ) ) );
		}

		if( empty( $url_modulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la url" ) ) );
		}

		if( empty( $responsable ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el responsable" ) ) );
		}

		if( empty( $titulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el titulo" ) ) );
		}

        if( empty( $fecha_ini ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la fecha" ) ) );
		}

		if( empty( $objetivo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el objetivo" ) ) );
		}

        if( empty( $prioridad ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el prioridad" ) ) );
		}

		if( empty( $riesgodesarrollo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el riesgo de desarrollo" ) ) );
		}

		if( empty( $numeracion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la númeración" ) ) );
		}

        if( empty( $descripcion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la descripcion" ) ) );
		}

        list($fecha_ini, $fecha_fin) = explode(' - ', $_POST['fecha_ini']);

		if( empty( validar_fecha($fecha_ini, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		if( empty( validar_fecha($fecha_fin, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		$data_proyecto = array("url" => $url_proyecto);
		$consultar_proyecto = $this->Proyectos_model->consultar_by_data($data_proyecto);

		if(empty($consultar_proyecto)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );	
		}

		if( strtotime($consultar_proyecto[0]->fecha_inicio) > strtotime($fecha_ini)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Debe ingresar una fecha mayor o igual a " . $consultar_proyecto[0]->fecha_inicio) ) );	
		}

		if( strtotime($consultar_proyecto[0]->fecha_finalizacion) < strtotime($fecha_fin)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Debe ingresar una fecha menor o igual a " . $consultar_proyecto[0]->fecha_finalizacion) ) );	
		}
		
		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );	
		}
		
		$consultar_prioridad = $this->Historias_model->consultar_by_prioridad_by_id($prioridad);

		if(empty($consultar_prioridad)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La prioridad no existe" ) ) );	
		}

		$consultar_riesgo_desarrollo = $this->Historias_model->consultar_riesgo_desarrollo_by_id($riesgodesarrollo);

		if(empty($consultar_riesgo_desarrollo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El riesgo desarrollo no existe" ) ) );	
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$data_miembro_proyecto = array("idusuario" => $responsable, "idproyecto" => $consultar_proyecto[0]->idproyecto);
		$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

		if(empty($consultar_miembro)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El miembro no se encuentra registrado" ) ) );
		}

		$data_miembro_proyecto = array("idusuario" => $responsable, "idproyecto" => $consultar_proyecto[0]->idproyecto);
		$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

		if( ! empty($consultar_miembro_profesor)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El profesor no puede crear como responsable de una historia" ) ) );
		}

		if(empty($consultar_miembro)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El miembro no se encuentra registrad1o" ) ) );
		}

		if($consultar_modulo[0]->estado == MODULO_FINALIZADO){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo se encuentra finalizado" ) ) );
		}

		if($consultar_modulo[0]->estado == MODULO_CREADO){
			$data = array("estado"=> MODULO_PENDIENTE);
			$modificar_modulo = $this->Modulos_model->modificar($consultar_modulo[0]->idmodulo, $data);	
		}

		$tiempo_estimado = printTime($fecha_ini . " 00:00:00", $fecha_fin . " 23:59:59");
	
        $data = array("titulo"=>$titulo, "fecha_ini"=>$fecha_ini, "fecha_fin"=>$fecha_fin, "objetivo"=>$objetivo, "idprioridad"=>$prioridad,"idriesgo_desarrollo"=>$riesgodesarrollo, "tiempo_estimado" => $tiempo_estimado, "numeracion" => $numeracion,"descripcion"=>$descripcion, "idusuario" => $responsable, "idmodulo" => $consultar_modulo[0]->idmodulo, "idusuario_modifica" => $this->session->UID);	
		$historias_crear = $this->Historias_model->ingresar($data);
		
		$this->db->trans_begin();
		if( empty($historias_crear)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia de usuario no se ingreso a la base de datos" ) ));
		}

		if(isset($_FILES['file'])){

            $archivos_guardados = $this->upload_files("./uploads/", $_FILES['file']);        

            for ($i=0; $i < count($archivos_guardados); $i++) { 
            
                $data_archivo = array("ruta"=>$archivos_guardados[$i][0], "client_name" => $archivos_guardados[$i][1], "idhistoria" => $historias_crear, "peso" => $archivos_guardados[$i][2]);
                $crear_archivo = $this->Historias_model->ingresar_archivo($data_archivo);
                
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

        $this->db->trans_commit();
        $data = array("titulo" => $titulo, "fecha_fin" => date('Y-m-d', strtotime($fecha_fin)), "idhistoria" => $historias_crear);
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La historia de usuario fue ingresada correctamente", "data" => $data ) );

	}

	//-----------------------------------------------------------------------------
	/**
	 * Listar fases filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Historias_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia de usuario no existe" ) ) );
		}

		$consultar_modulo = $this->Modulos_model->consultar_by_id($data->idmodulo);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$data_proyecto_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);
			if(empty($consultar_miembro)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$data_archivo = array("idhistoria" => $id);
		$consultar_archivo = $this->Historias_model->consultar_archivo_historia_by_data($data_archivo);
		
		$output = array();
		$output["id"] = $data->idhistoria;
		$output["titulo"] = $data->titulo;
		
		$output["fecha_ini"] = date('Y/m/d', strtotime($data->fecha_ini));
        $output["fecha_fin"] = date('Y/m/d', strtotime($data->fecha_fin));
		$output["objetivo"] = $data->objetivo;	
		$output["idprioridad"] = $data->idprioridad;
        $output["riesgodesarrollo"] = $data->idriesgo_desarrollo;
		$output["tiempoestimado"] = $data->tiempo_estimado;
		$output["numeracion"] = $data->numeracion;
        $output["descripcion"] = $data->descripcion;
        $output["estado"] = $data->estado;
		$output["archivos"] = $consultar_archivo;
		
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * actualización de fases
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
		$titulo = ucwords($this->input->post("titulo", TRUE));
        $fecha_ini = $this->input->post("fecha_ini", TRUE);
		$objetivo = $this->input->post("objetivo", TRUE);
        $prioridad = $this->input->post("prioridad", TRUE);
        $riesgodesarrollo = $this->input->post("riesgodesarrollo", TRUE);
		$numeracion = $this->input->post("numeracion", TRUE);
        $descripcion = $this->input->post("descripcion", TRUE);
        $url_proyecto = $this->input->post("url_proyecto", TRUE);
		$url_modulo = $this->input->post("url_modulo", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $url_proyecto ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la url" ) ) );
		}

		if( empty( $url_modulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la url" ) ) );
		}

		if( empty( $titulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el titulo" ) ) );
		}

        if( empty( $fecha_ini ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la fecha" ) ) );
		}

		if( empty( $objetivo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el objetivo" ) ) );
		}

        if( empty( $prioridad ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el prioridad" ) ) );
		}

		if( empty( $riesgodesarrollo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el riesgo de desarrollo" ) ) );
		}

        if( empty( $descripcion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la descripcion" ) ) );
		}
		
		if( ! is_numeric( $numeracion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la numeración" ) ) );
		}

        list($fecha_ini, $fecha_fin) = explode(' - ', $_POST['fecha_ini']);

		if( empty( validar_fecha($fecha_ini, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		if( empty( validar_fecha($fecha_fin, 'Y/m/d' ))) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese una fecha válida" ) ) );
		}

		$data_proyecto = array("url" => $url_proyecto);
		$consultar_proyecto = $this->Proyectos_model->consultar_by_data($data_proyecto);

		if(empty($consultar_proyecto)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );	
		}

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );	
		}
		
		$consultar_historia = $this->Historias_model->consultar_by_id($id);

		if(empty($consultar_historia)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia no existe" ) ) );	
		}

		if($consultar_historia->estado != HISTORIA_INCOMPLETA && $consultar_historia->estado != HISTORIA_PENDIENTE){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un estado válido" ) ) );
		}

		$consultar_prioridad = $this->Historias_model->consultar_by_prioridad_by_id($prioridad);

		if(empty($consultar_prioridad)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La prioridad no existe" ) ) );	
		}

		$consultar_riesgo_desarrollo = $this->Historias_model->consultar_riesgo_desarrollo_by_id($riesgodesarrollo);

		if(empty($consultar_riesgo_desarrollo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El riesgo desarrollo no existe" ) ) );	
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($consultar_modulo[0]->estado == MODULO_FINALIZADO){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo se encuentra finalizado" ) ) );
		}
	
		$tiempo_estimado = printTime($fecha_ini . " 00:00:00", $fecha_fin . " 23:59:59");
        $data = array( "titulo"=>$titulo, "fecha_ini"=>$fecha_ini, "fecha_fin"=>$fecha_fin, "objetivo"=>$objetivo, "idprioridad"=>$prioridad,"idriesgo_desarrollo"=>$riesgodesarrollo, "tiempo_estimado"=>$tiempo_estimado,"descripcion"=>$descripcion, "numeracion" => $numeracion);	
		$historias_crear = $this->Historias_model->modificar($id, $data);
		
		if( empty($historias_crear)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia de usuario no se modifico a la base de datos" ) ));
		}

		$color = "#f39c12";

    	if($consultar_historia->estado == HISTORIA_PENDIENTE){		
			$color = "#f39c12";
    	}else if($consultar_historia->estado == HISTORIA_APROBADA){
			$color = "#00a65a";
    	}else if($consultar_historia->estado == HISTORIA_ENTREGADA){
			$color = "#00a65a";
    	}else if($consultar_historia->estado == HISTORIA_INCOMPLETA){
			$color = "#f39c12";
    	}else if($consultar_historia->estado == HISTORIA_FINALIZADA){
			$color = "#00a65a";
    	}

    	if($consultar_historia->fecha_fin < date("Y-m-d") && ($consultar_historia->estado != HISTORIA_FINALIZADA &&  $consultar_historia->estado != HISTORIA_ENTREGADA) ){
			$color = "#dd4b39";
    	}

		$data = array("color" => $color, "fecha_fin" => date('Y-m-d', strtotime($fecha_fin)));
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El historia fue modificada correctamente", "data" => $data ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * eliminar de historia
	 * @return json
	 */
	public function eliminar(){
		$id = $this->input->post("id", TRUE);
		$url_modulo = $this->input->post("url_modulo", TRUE);
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $url_modulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_historia = $this->Historias_model->consultar_by_id($id);

		if( empty($consultar_historia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}
		
		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo[0]->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$data_proyeco_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyeco_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$presupuesto_eliminada = $this->Presupuestos_model->eliminar($id);

		if( empty($presupuesto_eliminada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El presupuesto no se elimino correctamente" ) ));
		}		

		$historia_eliminada = $this->Historias_model->eliminar($id);

		if( empty($historia_eliminada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia no se elimino correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La historia fue eliminada correctamente" ) );
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

    public function descargas($ruta){        

		if( empty( $ruta ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El nombre no existe" ) ) );
		}

		$data_archivo = array("ruta" => $ruta);
		$consultar_archivo = $this->Historias_model->consultar_archivo_historia_by_data($data_archivo);

		if( empty( $consultar_archivo ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El archivo no existe" ) ) );
		}

	    $data = file_get_contents('./uploads/'.$ruta);
	    force_download($consultar_archivo[0]->client_name,$data);
	}

	//-----------------------------------------------------------------------------
	/**
	 * Carga de imagenes
	 * @return json
	 */
	public function upload_file() {

		$id = $this->input->post("idhistoria", true);

		if( empty( $id ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$consultar_historia = $this->Historias_model->consultar_by_id($id);

    	if( empty($consultar_historia)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador válido")));
		}
		
		$consultar_modulo = $this->Modulos_model->consultar_by_id($consultar_historia->idmodulo);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$data_proyecto_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);
			if(empty($consultar_miembro)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
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

	        $data = array("ruta"=>$upload_data["orig_name"], "client_name" => $upload_data["client_name"], "idhistoria" => $id, "peso" => $upload_data["file_size"]);
        	$data_modificar = $this->Historias_model->ingresar_archivo($data);
        	
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
	
		$eliminar_foto = $this->Historias_model->eliminar_archivo_historia($nombre);

		if( empty( $eliminar_foto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El archivo no se elimino" ) ) );
		}

		$path = "./uploads/" . $nombre;

		if (file_exists($path)) {
			unlink($path);
    	}

    	echo json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Se elimino correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * presupuesto de historia
	 * @return json
	 */
	public function entregar(){
		$id = $this->input->post("id", TRUE);
		$url_modulo = $this->input->post("url_modulo", TRUE);
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $url_modulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_historia = $this->Historias_model->consultar_by_id($id);

		if( empty($consultar_historia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		if($consultar_historia->estado != HISTORIA_INCOMPLETA && $consultar_historia->estado != HISTORIA_PENDIENTE){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un estado válido" ) ) );
		}

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}
		
		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo[0]->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$data_proyeco_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyeco_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}

			$data_miembro_proyecto = array("idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			foreach($consultar_miembro_profesor as $miembro_profesor){
				$data_vista = array('idusuario' => $miembro_profesor->idusuario, 'idhistoria' => $id);
				$modificar_vista = $this->Vistas_model->ingresar($data_vista);
			}
		}

		$data = array("estado" => HISTORIA_FINALIZADA, "idusuario_modifica" => $this->session->UID);
		$historia_eliminada = $this->Historias_model->modificar($id, $data);

		if( empty($historia_eliminada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia no se entrego correctamente" ) ));
		}

		

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La historia fue entregada correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * entregar de historia
	 * @return json
	 */
	public function cambiar_estado(){
		$id = $this->input->post("id", TRUE);
		$idestado = $this->input->post("idestado", TRUE);
		$observacion = $this->input->post("observacion", TRUE);
		$url_modulo = $this->input->post("url_modulo", TRUE);
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $idestado ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado" ) ) );
		}

		if( empty( $idestado ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado" ) ) );
		}

		if( empty( $observacion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la observación" ) ) );
		}

		if( empty( $url_modulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la url del modulo" ) ) );
		}

		if($idestado != HISTORIA_INCOMPLETA){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un estado válido" ) ) );
		}

		$consultar_historia = $this->Historias_model->consultar_by_id($id);

		if( empty($consultar_historia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		if($consultar_historia->estado != HISTORIA_FINALIZADA){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El historia no esta en modo de entrega" ) ) );
		}

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}
		
		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo[0]->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$data_proyeco_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyeco_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}

			$data_vista = array('estado' => 1);
			$modificar_vista = $this->Vistas_model->modificar_by_historia($id, $data_vista);
		
		} else {

			$data_miembro_proyecto = array("idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_estudiante = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);
			foreach($consultar_miembro_estudiante as $miembro_estudiante){
				$data_vista = array('idusuario' => $miembro_estudiante->idusuario, 'idhistoria' => $id);
				$modificar_vista = $this->Vistas_model->ingresar($data_vista);
			}
		}

		$data = array("estado" => $idestado, "observaciones" => $observacion, "idusuario_modifica" => $this->session->UID);
		$historia_eliminada = $this->Historias_model->modificar($id, $data);

		if( empty($historia_eliminada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia no se entrego correctamente" ) ));
		}

		$reload = false;
		if($consultar_modulo[0]->estado == MODULO_FINALIZADO){
			$data = array("estado"=> MODULO_PENDIENTE);
			$modificar_modulo = $this->Modulos_model->modificar($consultar_modulo[0]->idmodulo, $data);
			$reload = true;
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La historia fue marcada como incompleta", "reload" => $reload ) );
	}

	public function ver_pdf($idhistoria){

		if(empty($idhistoria)){
			redirect("proyectos/listar");
    }

    $consultar_historia = $this->Historias_model->consultar_by_id($idhistoria);
        
    if(empty($consultar_historia)){
			redirect("proyectos/listar");
		}
		
		$consultar_modulo = $this->Modulos_model->consultar_by_id($consultar_historia->idmodulo);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$data_proyecto_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metodología" ) ) );
		}

		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($consultar_proyecto_metodologia[0]->idproyecto);

		if(empty($consultar_proyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				if($consultar_proyecto->proyecto_base == 0){
					redirect("errores/not_found");	
				}
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);
			if(empty($consultar_miembro)) {
				if($consultar_proyecto->proyecto_base == 0){
					redirect("errores/not_found");	
				}
			}
		}

		$dompdf = new Dompdf();

		$fecha_ini = formato_fecha($consultar_historia->fecha_ini);
		$fecha_fin = formato_fecha($consultar_historia->fecha_fin);
		$fecha_ini =  $fecha_ini["dia"] . " de " . $fecha_ini["mes"] . " de " . $fecha_ini["ano"];
		$fecha_fin =  $fecha_fin["dia"] . " de " . $fecha_fin["mes"] . " de " . $fecha_fin["ano"];
		$consultar_prioridad = $this->Historias_model->consultar_by_prioridad_by_id($consultar_historia->idprioridad);
		$consultar_riesgo_desarrollo = $this->Historias_model->consultar_riesgo_desarrollo_by_id($consultar_historia->idriesgo_desarrollo);
		$consultar_usuario = $this->Usuarios_model->consultar_by_id($consultar_historia->idusuario);
		$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_id_modulo($consultar_historia->idmodulo);

		$data = array("idhistoria"=>$consultar_historia->idhistoria, "titulo"=>$consultar_historia->titulo, "fecha_fin"=>$fecha_fin, "fecha_ini"=>$fecha_ini, "objetivo"=>$consultar_historia->objetivo, "riesgodesarrollo"=>$consultar_riesgo_desarrollo->nombre, "tiempo_estimado"=>$consultar_historia->tiempo_estimado, "descripcion"=>$consultar_historia->descripcion, "prioridad"=>$consultar_prioridad->nombre, "responsable"=>$consultar_usuario->nombre . " " . $consultar_usuario->apellido, "evaluador"=>$consultar_miembro_profesor->nombre . " " . $consultar_miembro_profesor->apellido, "observaciones"=>$consultar_historia->observaciones, "numeracion"=>$consultar_historia->numeracion);

        $page = $this->load->view("template/historiausuario/pdf_historia", $data, true);
        
        $dompdf->loadHtml($page);
        $canvas = $dompdf->get_canvas();

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();
        $dompdf->stream("name", array("Attachment"=>0));	

	}

	public function finalizar_modulo(){
		
		$url_modulo = $this->input->post("url_modulo", true);
		$url_metodologia = $this->input->post("url_metodologia", true);
		$url_fase = $this->input->post("url_fase", true);
		$url_proyecto = $this->input->post("url_proyecto", true);

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$estado = array(HISTORIA_INCOMPLETA, HISTORIA_PENDIENTE);
		$consultar_historia = $this->Historias_model->consultar_historia_pendiente_incompleta($estado, $consultar_modulo[0]->idmodulo);

		if( ! empty($consultar_historia)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo tiene historias pendientes" ) ) );
		}

		if( $consultar_modulo[0]->estado != MODULO_CREADO &&  $consultar_modulo[0]->estado != MODULO_PENDIENTE) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se encuentra pendiente" ) ) );
		}

		$data_historias = array("idmodulo" => $consultar_modulo[0]->idmodulo);
		$consultar_historia = $this->Historias_model->consultar_by_data($data_historias);

		if(empty($consultar_historia)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No hay historia de usuario registradas" ) ) );
		}

		$data_proyecto = array("url" => $url_proyecto);
		$consultar_proyecto = $this->Proyectos_model->consultar_by_data($data_proyecto);

		if(empty($consultar_proyecto)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No hay historia de usuario registradas" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$data = array("estado"=> MODULO_FINALIZADO);
		$fase_modificada = $this->Modulos_model->modificar($consultar_modulo[0]->idmodulo, $data);

		$consultar_usuario = $this->Usuarios_model->consultar_by_id($this->session->UID);
		$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_id_modulo($consultar_modulo[0]->idmodulo);

		$mail = phpmailer_init();
		$mail->AddAddress($consultar_miembro_profesor->correo);

        $mail->Subject = "Proyecto: Modulo Finalizado";
	
		$data = array("url" => base_url('historia/listar/'.$url_proyecto.'/'.$url_metodologia.'/'.$url_fase.'/'.$url_modulo), "nombre" => $consultar_miembro_profesor->nombre . " " . $consultar_miembro_profesor->apellido, "nombre_usuario" => $consultar_usuario->nombre . " " . $consultar_usuario->apellido, "nombre_modulo" => $consultar_modulo[0]->nombre);
        $body = $this->load->view("template/correos/modulo_finalizado", $data, true);

        $mail->Body = $body;
        $exito = $mail->Send();

        if(empty($exito)) {
            log_message('debug', 'El correo no se envio correctamente');
        }
        
        echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El modulo finalizo correctamente" ) );
	}

	public function abrir_modulo(){
		
		$url_modulo = $this->input->post("url_modulo", true);
		$url_metodologia = $this->input->post("url_metodologia", true);
		$url_fase = $this->input->post("url_fase", true);
		$url_proyecto = $this->input->post("url_proyecto", true);

		$data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		if( $consultar_modulo[0]->estado != MODULO_FINALIZADO) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se encuentra finalizado" ) ) );
		}

		$data = array("estado"=> MODULO_PENDIENTE);
		$modificar_modulo = $this->Modulos_model->modificar($consultar_modulo[0]->idmodulo, $data);
        
        echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El modulo fue abierto correctamente" ) );
	}

	public function cambiar_vista(){

		$id = $this->input->post("id", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$data_vista = array('estado' => 1);
		$modificar_vista = $this->Vistas_model->modificar_by_historia_y_usuario($id, $this->session->UID, $data_vista);

		die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el responsable" ) ) );
	}

	public function ordenar(){
		$historias = $this->input->post("historias", TRUE);
		
		if( empty( $historias ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		for ($i=1; $i <= count($historias); $i++) {
			$data = array("posicion"=>$i);
			$fase_modificada = $this->Historias_model->modificar($historias[$i - 1], $data);
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La historia fue modificada correctamente" ) );
	}
}