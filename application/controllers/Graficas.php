<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Graficas extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Usuarios_model");
        $this->load->model("Roles_model");
        $this->load->model("Proyectos_model");

    }

	//-----------------------------------------------------------------------------
	public function index(){

		$consultar_proyecto = $this->Proyectos_model->consultar_proyecto_con_miembro(false, false, false, false);

		$this->lm->add_js('hightcharts/highcharts');
		$this->lm->add_js('hightcharts/modules/exporting');
		$this->lm->add_js('hightcharts/modules/export-data');
		$this->lm->add_js('graficas/estado_historia');
		$this->lm->add_jsvars(array("IDPROYECTO" => $this->input->get('idproyecto')));
		$this->lm->set_title('Graficas - Estado historia');
		$this->lm->set_page('graficas/estado_historia');
		
		$data = array("consultar_proyecto" => $consultar_proyecto);
		$this->lm->render($data);
	}


	public function obtener_graficas(){

		$idproyecto = $this->input->post("idproyecto", TRUE);
		$idfase = $this->input->post("idfase", TRUE);

		$data_miembro_proyecto = array("idproyecto" => $idproyecto);
		$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro_proyecto);
		
		$nombres = [];
		$finalizada = [];
		$incompleta = [];
		$entregada = [];
		$pendiente = [];
		foreach ($consultar_miembro as $miembro) {
			
			$consultar_usuario = $this->Usuarios_model->consultar_by_id($miembro->idusuario);

			array_push($nombres, $consultar_usuario->nombre . " " . $consultar_usuario->apellido);

			$consultar_historia_pendiente = $this->Proyectos_model->consultar_todo_historias_by_proyecto(HISTORIA_PENDIENTE, $miembro->idproyecto, $miembro->idusuario, $idfase);

			$consultar_historia_incompleta = $this->Proyectos_model->consultar_todo_historias_by_proyecto(HISTORIA_INCOMPLETA, $miembro->idproyecto, $miembro->idusuario, $idfase);
			$consultar_historia_finalizada = $this->Proyectos_model->consultar_todo_historias_by_proyecto(HISTORIA_FINALIZADA, $miembro->idproyecto, $miembro->idusuario, $idfase);

			array_push($pendiente, (int) $consultar_historia_pendiente->contador_historia);
			array_push($incompleta, (int) $consultar_historia_incompleta->contador_historia);
			array_push($finalizada, (int) $consultar_historia_finalizada->contador_historia);

		}

		$result[0] = $finalizada;
		$result[1] = $pendiente;
		$result[2] = $incompleta;

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El proyecto se modifico correctamente", "data" => $result, "nombres" => $nombres) );
	}
}

?>