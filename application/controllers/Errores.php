<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errores extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Roles_model");
        $this->load->model("Permisos_model");
    }

	//-----------------------------------------------------------------------------
	/**
	 * Vista de rutas privada de errores
	 * @return void
	 */
	public function vista_privada(){
		$this->lm->set_title('Vista Privada');

		$this->lm->set_header('header_vacio');
		$this->lm->set_body('body_vacio');
		$this->lm->set_footer('footer_vacio');
		$this->lm->set_page('errors/vista_privada');

		$this->lm->render();
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista de rutas publica de errores
	 * @return void
	 */
	public function vista_publica(){
		$this->lm->set_title('Vista Publica');
		$this->lm->set_header('header_vacio');
		$this->lm->set_body('body_vacio');
		$this->lm->set_footer('footer_vacio');
		$this->lm->set_page('errors/vista_publica');
		
		$this->lm->render();
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista de rutas publica de errores
	 * @return void
	 */
	public function not_found(){
		$this->lm->set_title('Vista Publica');
		$this->lm->set_header('header_vacio');
		$this->lm->set_body('body_vacio');
		$this->lm->set_footer('footer_vacio');
		$this->lm->set_page('errors/error_404');
		
		$this->lm->render();
	}
}

?>