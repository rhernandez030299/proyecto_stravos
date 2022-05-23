<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presupuestos extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Presupuestos_model");
        $this->load->model("Modulos_model");
        $this->load->model("Fases_model");
        $this->load->model("Proyectos_model");   
    }

    //-----------------------------------------------------------------------------
    public function consultar_presupuesto(){

        $url_modulo = $this->input->post("url_modulo", TRUE);
        if( empty( $url_modulo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la url" ) ) );
        }
        
        $data_modulo = array("url" => $url_modulo);
		$consultar_modulo = $this->Modulos_model->consultar_by_data($data_modulo);

		if(empty($consultar_modulo)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El modulo no existe" ) ) );	
        }
        
        $consultar_modulo = $consultar_modulo[0];

        $presupuesto = "$ " . number_format($this->Presupuestos_model->consultar_presupuesto(FALSE, $consultar_modulo->idmodulo), 2, ",", ".");
        echo json_encode( array( "presupuesto" => $presupuesto ) );
	}


    //-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de las presupuestos
	 * @return array      	   Datos de las presupuestos
	 */
	public function recorrer_datos( $data, $historia_id = FALSE ){
		$datos =  array();
		$filtered_rows = count($data);
		foreach($data as $row)
		{
			$sub_array = array();
			$sub_array[] = $row->descripcion;
			$sub_array[] = $row->cantidad;
			$sub_array[] = $row->valor_unidad;
            $sub_array[] = $row->total;
            $sub_array[] = $row->nombreCategoria;
				
			$function = [
				[
					"method" 		=> "actualizar", 
					"name"			=> "update", 
					"id"			=> $row->idpresupuesto, 
					"classes" 		=> "btn btn-success updatepresupuesto",
					"icon" 			=> "flaticon-edit",
					"title" 		=> "Modificar",
					"attributes" 	=> "",
					"link" 			=> FALSE
                ],
                [
                    "method" 		=> "eliminar", 
                    "name"			=> "delete", 
                    "id"			=> $row->idpresupuesto, 
                    "classes" 		=> "btn btn-danger deletepresupuesto", 
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
				"recordsFiltered"   =>  $this->Presupuestos_model->consultar_conteo_by_filtro($busqueda, $historia_id),
				"data"              =>  $datos
		);
		return $output;
	}

	//-----------------------------------------------------------------------------
	/**
	 * Lista de presupuestos
	 * @return json
	 */
	public function listar_presupuestos(){
        $historia_id = $this->input->post("historia_id",true);
        if( empty($historia_id)){
            echo json_encode([]);
            return;
        }

        if($this->session->USER_ROL == ROL_PROFESOR){
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_historia($historia_id, $this->session->UID);

			if(empty($consultar_miembro_profesor)) {
				echo json_encode([]);
                return;
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_historia($historia_id, $this->session->UID);
			if(empty($consultar_miembro)) {
				echo json_encode([]);
                return;
			}
		}

		$data = $this->Presupuestos_model->consultar_by_filtros($historia_id);
		$output = $this->recorrer_datos($data, $historia_id);
		echo json_encode($output);
	}

    //-----------------------------------------------------------------------------
	/**
	 * crear presupuesto
	 * @return json
	 */
	public function crear(){
		$id = $this->input->post("id", TRUE);
		$idcategoria = $this->input->post("idcategoria", TRUE);
		$descripcion = $this->input->post("descripcion", TRUE);
		$cantidad = $this->input->post("cantidad", TRUE);
		$valor_unidad = $this->input->post("valor_unidad", TRUE);
		$idresponsable = $this->input->post("idresponsable", TRUE);

        if($this->session->USER_ROL == ROL_PROFESOR){
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_historia($id, $this->session->UID);
			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_historia($id, $this->session->UID);
			if(empty($consultar_miembro)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $idresponsable ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el responsable" ) ) );
		}

		if( empty( $idcategoria ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la categoría" ) ) );
		}
		
		if( empty( $descripcion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la descripción" ) ) );
		}

		if( empty( $cantidad ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la cantidad" ) ) );
		}

		if( empty( $valor_unidad ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el valor de la unidad" ) ) );
		}

		$consultar_historia = $this->Historias_model->consultar_by_id($id);

		if( empty($consultar_historia) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La historia no existe" ) ) );
		}

		$data = array("descripcion"=> $descripcion, "cantidad"=> $cantidad, "valor_unidad"=> $valor_unidad, "total"=> $valor_unidad * $cantidad, "idresponsable"=> $idresponsable, "idhistoria"=> $id, "idcategoria" => $idcategoria);
		$modificar_modulo = $this->Presupuestos_model->ingresar($data);

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El presupuesto se creó correctamente" ) );
	}

    //-----------------------------------------------------------------------------	
	/**
	 * Actualización de metodologias
	 * @return json
	 */
	public function actualizar(){
		$idpresupuesto = $this->input->post("idpresupuesto", TRUE);
		$idcategoria = $this->input->post("idcategoria", TRUE);
		$descripcion = $this->input->post("descripcion", TRUE);
		$cantidad = $this->input->post("cantidad", TRUE);
		$valor_unidad = $this->input->post("valor_unidad", TRUE);
		$idresponsable = $this->input->post("idresponsable", TRUE);

		if( empty( $idpresupuesto ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $idresponsable ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el responsable" ) ) );
		}

		if( empty( $idcategoria ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la categoría" ) ) );
		}
		
		if( empty( $descripcion ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la descripción" ) ) );
		}

		if( empty( $cantidad ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la cantidad" ) ) );
		}

		if( empty( $valor_unidad ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el valor de la unidad" ) ) );
		}

        $consultar_presupuesto = $this->Presupuestos_model->consultar_by_id($idpresupuesto);

		if( empty($consultar_presupuesto) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El presupuesto no existe" ) ) );
		}

        if($this->session->USER_ROL == ROL_PROFESOR){
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_historia($consultar_presupuesto->idhistoria, $this->session->UID);
			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_historia($consultar_presupuesto->idhistoria, $this->session->UID);
			if(empty($consultar_miembro)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		$data = array("idresponsable"=>$idresponsable,"idcategoria"=>$idcategoria,"descripcion"=>$descripcion,"cantidad"=>$cantidad,"valor_unidad"=>$valor_unidad, "total"=> $valor_unidad * $cantidad,);
		$data = $this->Presupuestos_model->modificar($idpresupuesto,$data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El presupuesto no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El presupuesto se modifico correctamente" ) );
	}

    //-----------------------------------------------------------------------------	
	/**
	 * Eliminar el presupuesto
	 * @return json
	 */
	public function eliminar(){
		$id = $this->input->post("id",TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

        $data = $this->Presupuestos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El presupuesto no existe" ) ) );
		}

    if($this->session->USER_ROL == ROL_PROFESOR){
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_historia($data->idhistoria, $this->session->UID);
			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_historia($data->idhistoria, $this->session->UID);
			if(empty($consultar_miembro)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}
		
		$data = $this->Presupuestos_model->eliminar($id);
		
		if(empty($data)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "el presupuesto no se elimino" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_ERROR, 'msg' => "el presupuesto se elimino correctamente! " ) );
	}

    //-----------------------------------------------------------------------------
	/**
	 * Listar metodologias filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Presupuestos_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El presupuesto no existe" ) ) );
		}

		if($this->session->USER_ROL == ROL_PROFESOR){
			$consultar_miembro_profesor = $this->Proyectos_model->consultar_miembro_profesor_by_historia($data->idhistoria, $this->session->UID);

			if(empty($consultar_miembro_profesor)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		if($this->session->USER_ROL == ROL_ESTUDIANTE){
			$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_historia($data->idhistoria, $this->session->UID);
			if(empty($consultar_miembro)) {
				die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "No tiene permisos para validar esta informacion" ) ) );
			}
		}

		$output = array();
		$output["id"] = $data->idpresupuesto;
    $output["descripcion"] = $data->descripcion;
		$output["cantidad"] = $data->cantidad;
		$output["valor_unidad"] = $data->valor_unidad;
		$output["total"] = $data->total;
		$output["idresponsable"] = $data->idresponsable;
		$output["idhistoria"] = $data->idhistoria;
		$output["idcategoria"] = $data->idcategoria;
		
		echo json_encode($output);
	}
}