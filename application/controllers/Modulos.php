<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modulos extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Proyectos_model");
        $this->load->model("Metodologias_model");
        $this->load->model("Fases_model");
        $this->load->model("Usuarios_model");
				$this->load->model("Presupuestos_model");
        $this->load->model("Modulos_model");   
        $this->load->helper(array('phpmailer_helper'));     
    }

	public function obtener_modulos(){
		
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

		if(empty($consultar_fase)) {
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
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				$puedeCrear = false;
				if($consultar_proyecto->proyecto_base == 0){
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
				}
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$data_miembro_proyecto = array("idusuario" => $this->session->UID, "idproyecto" => $consultar_proyecto_metodologia[0]->idproyecto);
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				$puedeCrear = false;
				if($consultar_proyecto->proyecto_base == 0){
					die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
				}
			}
		}

		$data_modulos = array("idfase" => $consultar_fase[0]->idfase);
		$consultar_modulos = $this->Modulos_model->consultar_by_data($data_modulos);

		$datos =  array();
		foreach($consultar_modulos as $row)
		{

			$presupuesto = $this->Presupuestos_model->consultar_presupuesto(FALSE, $row->idmodulo);
			$sub_array = array();
			$created_at = formato_fecha(date('Y-m-d', strtotime($row->created_at)));
			$sub_array[] = $row->nombre;
			$sub_array[] = $created_at["dia"] . " de " . $created_at["mes"] . " de " . $created_at["ano"];
			$sub_array[] = $row->idmodulo;
			$sub_array[] = $row->descripcion;
			$sub_array[] = $row->url;

			$estado = "En proceso";
			$estadoColor = "warning";
    	if($row->estado == MODULO_CREADO){
				$estado = "Creado";
				$estadoColor = "primary";
      }else if($row->estado == MODULO_PENDIENTE){
				$estado = "Pendiente";
				$estadoColor = "warning";
      }else if($row->estado == MODULO_FINALIZADO){
				$estado = "Finalizado";
				$estadoColor = "success";
      }

			$sub_array["estado"] = $estado;
			$sub_array["estadoColor"] = $estadoColor;
			$sub_array["presupuesto"] = "$ " . number_format($presupuesto, 2, ",", ".");

			$function = [];
			if( $puedeCrear ){
				$function[] = [
					"method" 		=> "actualizar", 
					"name"			=> "update", 
					"id"			=> $row->idmodulo, 
					"classes" 		=> "btn btn-success update",
					"icon" 			=> "flaticon-edit",
					"title" 		=> "Modificar",
					"attributes" 	=> "",
					"link" 			=> FALSE
				];

				$function[] = [
					"method" 		=> "eliminar", 
					"name"			=> "delete", 
					"id"			=> $row->idmodulo, 
					"classes" 		=> "btn btn-danger delete", 
					"icon"			=> "fas fa-trash-alt",
					"title" 		=> "Eliminar", 
					"attributes" 	=> "",
					"link" 			=> FALSE
				];
			}

			$botones = $this->btn->get_buttons($function);
			$sub_array[] = $botones;
			
		  $datos[] = $sub_array;
    }

    echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "FASES REGISTRADAS", "data" => $datos ) );
	}
	
	//-----------------------------------------------------------------------------
	/**
	 * creación de fases
	 * @return json
	 */
	public function crear(){
		$descripcion = $this->input->post("descripcion", TRUE);
        $nombre = $this->input->post("nombre", TRUE);
		$url_proyecto = $this->input->post("url_proyecto", true);
		$url_metodologia = $this->input->post("url_metodologia", true);
		$url_fase = $this->input->post("url_fase", true);

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}
		
		if( empty( $url_proyecto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		if( empty( $url_metodologia ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología no existe" ) ) );
		}

		if( empty( $url_fase ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$data_proyecto = array("url" => $url_proyecto);
		$consultar_proyecto = $this->Proyectos_model->consultar_by_data($data_proyecto);

		if(empty($consultar_proyecto)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );	
		}

		$data_metodologia = array("url" => $url_metodologia);
		$consultar_metodologia = $this->Metodologias_model->consultar_by_data($data_metodologia);

		if(empty($consultar_metodologia)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}


		$data_fase = array("url" => $url_fase);
		$consultar_fase = $this->Fases_model->consultar_by_data($data_fase);

		if(empty($consultar_fase)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
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
		
		$data = array("nombre"=>$nombre, "descripcion" => $descripcion, "idfase" => $consultar_fase[0]->idfase);
		$idingresado = $this->Modulos_model->ingresar($data);

		if( empty($idingresado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se ingreso a la base de datos" ) ));
		}

		$url = generar_url($idingresado .  "-" . strtolower($nombre));
		$data = array("url"=>$url);
		$data = $this->Modulos_model->modificar($idingresado, $data);

		if( empty($data)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se ingreso correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El modulo fue ingresado correctamente" ) );
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

		$data = $this->Modulos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modilo no existe" ) ) );
		}

		$consultar_fase = $this->Fases_model->consultar_by_id($data->idfase);

		if(empty($consultar_fase)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
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
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}
		
		$output = array();
		$output["id"] = $data->idmodulo;
        $output["nombre"] = $data->nombre;
        $output["descripcion"] = $data->descripcion;
        $output["url"] = $data->url;
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------
	/**
	 * actualización de fases
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
        $nombre = $this->input->post("nombre", TRUE);
        $descripcion = $this->input->post("descripcion", TRUE);

        if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $descripcion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la descripción" ) ) );
		}

		$consultar_modulo = $this->Modulos_model->consultar_by_id($id);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}

		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo->idfase);

		$data_proyecto_metodologia = array("idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		if( empty($consultar_proyecto_metodologia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No existe el proyecto metología" ) ) );
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

		$data = array("nombre"=>$nombre, "descripcion" => $descripcion);
		$fase_modificada = $this->Modulos_model->modificar($id, $data);

		if( empty($fase_modificada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se modifico correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El modulo fue modificado correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * eliminar de fase
	 * @return json
	 */
	public function eliminar(){
		$id = $this->input->post("id", TRUE);
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_modulo = $this->Modulos_model->consultar_by_id($id);

		if( empty($consultar_modulo) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );
		}
		
		$consultar_fase = $this->Fases_model->consultar_by_id($consultar_modulo->idfase);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
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
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción" ) ) );
			}
		}

		$modulo_eliminada = $this->Modulos_model->eliminar($id);

		if( empty($modulo_eliminada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se elimino correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El modulo fue eliminado correctamente" ) );
	}

	public function list($url_proyecto, $url_metodologia, $url_fase){

		if(empty($url_proyecto) || empty($url_metodologia) || empty($url_fase)){
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
		
		$function = [];
		if( $puedeCrear ) {
			$function[0] = [
				"method" 		=> "crear", 
				"name"			=> "add_button", 
				"id"			=> "add_button", 
				"classes" 		=> "btn btn-light-primary btn-flat", 
				"title" 		=> "Agregar modulo", 
				"text"			=> "Agregar modulo",
				"icon"			=> "",
				"link" 			=> FALSE
			];
		}
		
		$botones = $this->btn->get_buttons($function);
		
		$this->lm->set_title('Modulos');
		$this->lm->set_page('modulos/listar');
		$this->lm->add_js('modulos');
		$this->lm->add_js('jquery-UI/jquery-ui.js');
		$this->lm->add_jsvars(array("url_metodologia" => $url_metodologia, "url_proyecto" => $url_proyecto, "url_fase" => $url_fase));
		$this->lm->add_css('modulos');

		$data = array("nombre"=>$consultar_proyecto[0]->nombre, "nombre_metodologia"=>$consultar_metodologia[0]->nombre, "nombre_fase"=>$consultar_fase[0]->nombre, "url_metodologia" => $url_metodologia, "url_proyecto" => $url_proyecto, "url_fase" => $url_fase, "botones" => $botones);
		$this->lm->render($data);
	}

	public function ordenar(){
		$modulos = $this->input->post("modulos", TRUE);
		
		if( empty( $modulos ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		for ($i=1; $i <= count($modulos); $i++) {
			$data = array("posicion"=>$i);
			$fase_modificada = $this->Modulos_model->modificar($modulos[$i - 1], $data);
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La modulo fue modificada correctamente" ) );
	}
}