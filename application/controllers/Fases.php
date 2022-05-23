<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fases extends CI_Controller {

	public function __construct(){
    parent::__construct();   
    $this->load->library('Layout_manager',NULL,'lm'); 
    $this->load->model("Proyectos_model");
		$this->load->model("Metodologias_model");
		$this->load->model("Presupuestos_model");
		$this->load->model("Modulos_model");
		
    $this->load->model("Fases_model");
  }

	public function obtener_fases_by_proyecto(){

		$idproyecto = $this->input->post("idproyecto", true);

		if( empty( $idproyecto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe1" ) ) );
		}

		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($idproyecto);

		if(empty($consultar_proyecto)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

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

		$consultar_fase = $this->Fases_model->listar_proyecto_metodologia_by_id_proyecto($idproyecto);

		if(empty($consultar_fase)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La fase fue consultada correctamente", "consultar_fase" => $consultar_fase ) );
	}

	public function obtener_fases(){
		
		$url_proyecto = $this->input->post("url_proyecto", true);

		if( empty( $url_proyecto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		$url_metodologia = $this->input->post("url_metodologia", true);

		if( empty( $url_metodologia ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		$consultar_fase = $this->Fases_model->consultar_proyecto_metodologia_by_id_proyecto($url_proyecto, $url_metodologia);
		
		if(empty($consultar_fase)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

		$consultar_proyecto = $this->Proyectos_model->consultar_by_id($consultar_fase[0]->idproyecto);

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


		$datos =  array();
		foreach($consultar_fase as $row)
		{

			$presupuesto = $this->Presupuestos_model->consultar_presupuesto(FALSE, FALSE, $row->idfase);
			$sub_array = array();
			$created_at = formato_fecha(date('Y-m-d', strtotime($row->created_at)));
			$sub_array[] = $row->nombre;
			$sub_array[] = $created_at["dia"] . " de " . $created_at["mes"] . " de " . $created_at["ano"];
			$sub_array[] = $row->idfase;
			$sub_array[] = $row->url;

			$sub_array["presupuesto"] = "$ " . number_format($presupuesto, 2, ",", ".");
			
			$function = [];
			if( $puedeCrear ){
				$function[] = [
					"method" 		=> "actualizar", 
					"name"			=> "update", 
					"id"			=> $row->idfase, 
					"classes" 		=> "btn btn-success update",
					"icon" 			=> "flaticon-edit",
					"title" 		=> "Modificar",
					"attributes" 	=> "",
					"link" 			=> FALSE
				];
				$function[] = [
					"method" 		=> "eliminar", 
					"name"			=> "delete", 
					"id"			=> $row->idfase, 
					"classes" 		=> "btn btn-danger delete", 
					"icon"			=> "fas fa-trash-alt",
					"title" 		=> "Eliminar", 
					"attributes" 	=> "",
					"link" 			=> FALSE
				];
				$function[] = [
					"method" 		=> "clonar", 
					"name"			=> "clonar", 
					"id"			=> $row->idfase, 
					"classes" 		=> "btn btn-primary clonar", 
					"icon"			=> "flaticon2-copy",
					"title" 		=> "Clonar", 
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
    $nombre = $this->input->post("nombre", TRUE);
		$url_proyecto = $this->input->post("url_proyecto", true);
		$url_metodologia = $this->input->post("url_metodologia", true);

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}
		
		if( empty( $url_proyecto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
		}

		if( empty( $url_metodologia ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto no existe" ) ) );
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

		$data_proyecto_metodologia = array("idproyecto" => $consultar_proyecto[0]->idproyecto, "idmetodologia" => $consultar_metodologia[0]->idmetodologia);
		$consultar_proyecto_metodologia = $this->Proyectos_model->consultar_proyecto_metodologia_by_data($data_proyecto_metodologia);

		if(empty($consultar_proyecto_metodologia)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El proyecto metología no existe" ) ) );
		}
		
		$data = array("nombre"=>$nombre, "idproyecto_metodologia" => $consultar_proyecto_metodologia[0]->idproyecto_metodologia);
		$idingresado = $this->Fases_model->ingresar($data);

		if( empty($idingresado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no se ingreso a la base de datos" ) ));
		}

		$url = generar_url($idingresado .  "-" . strtolower($nombre));

		$data = array("url"=>$url);
		$data = $this->Fases_model->modificar($idingresado, $data);

		if( empty($data)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no se ingreso correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La fase fue ingresada correctamente" ) );
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

		$data = $this->Fases_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología no existe" ) ) );
		}

		$data_proyecto_metodologia = array("idproyecto_metodologia" => $data->idproyecto_metodologia);
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
		$output["id"] = $data->idfase;
        $output["nombre"] = $data->nombre;
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

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}
		
		$consultar_fase = $this->Fases_model->consultar_by_id($id);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

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

		$data = array("nombre"=>$nombre);
		$fase_modificada = $this->Fases_model->modificar($id, $data);

		if( empty($fase_modificada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no se modifico correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La fase fue modificada correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * clonar de fase
	 * @return json
	 */
	public function clonar(){
		$id = $this->input->post("id", TRUE);
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}
		
		$consultar_fase = $this->Fases_model->consultar_by_id($id);

		if( empty($consultar_fase) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no existe" ) ) );
		}

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


		$nombre = $consultar_fase->nombre . " copy";
		$data = array("nombre"=>$nombre, "idproyecto_metodologia" => $consultar_fase->idproyecto_metodologia);
		$idingresado = $this->Fases_model->ingresar($data);

		$this->db->trans_begin();
		if( empty($idingresado)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no se ingreso a la base de datos" ) ));
		}

		$url = generar_url($idingresado .  "-" . strtolower($nombre));

		$data = array("url"=>$url);
		$data = $this->Fases_model->modificar($idingresado, $data);

		if( empty($data)){
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no se modifico a la base de datos" ) ));
		}

		$data_modulo = array("idfase" => $id);
		$consultar_modulos = $this->Modulos_model->consultar_by_data($data_modulo);

		foreach($consultar_modulos as $modulo){

			$data = array("nombre"=>$modulo->nombre, "descripcion" => $modulo->descripcion, "idfase" => $idingresado);
			$idingresado_modulo = $this->Modulos_model->ingresar($data);

			if( empty($idingresado_modulo)){
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se ingreso a la base de datos" ) ));
			}

			$url = generar_url($idingresado_modulo .  "-" . strtolower($modulo->nombre));
			$data = array("url"=>$url);
			$data = $this->Modulos_model->modificar($idingresado_modulo, $data);

			if( empty($data)) {
				$this->db->trans_rollback();
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no se ingreso correctamente" ) ));
			}

		}

		$this->db->trans_commit();
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La clonación fue realizada correctamente" ) );
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
		
		$consultar_fase = $this->Fases_model->consultar_by_id($id);

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

		$fase_eliminada = $this->Fases_model->eliminar($id);

		if( empty($fase_eliminada)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La fase no se eliminp correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La fase fue eliminada correctamente" ) );
	}

	public function list($url_proyecto, $url_metodologia){

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
				"title" 		=> "Agregar Fase", 
				"text"			=> "Agregar Fase",
				"icon"			=> "",
				"link" 			=> FALSE
			];
		}

    $botones = $this->btn->get_buttons($function);
		
		$this->lm->set_title('Fases');
		$this->lm->set_page('fases/listar');
		$this->lm->add_js('fases');
		$this->lm->add_js('jquery-UI/jquery-ui.js');
		$this->lm->add_jsvars(array("url_metodologia" => $url_metodologia, "url_proyecto" => $url_proyecto));
		$this->lm->add_css('fases');

		$data = array("nombre"=>$consultar_proyecto[0]->nombre, "nombre_metodologia"=>$consultar_metodologia[0]->nombre, "botones" => $botones);
		$this->lm->render($data);
	}

	public function ordenar(){
		$fases = $this->input->post("fases", TRUE);
		
		if( empty( $fases ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		for ($i=1; $i <= count($fases); $i++) {
			$data = array("posicion"=>$i);
			$fase_modificada = $this->Fases_model->modificar($fases[$i - 1], $data);
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La fase fue modificada correctamente" ) );
	}
}