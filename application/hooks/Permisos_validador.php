<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos_validador {

	protected $CI;
	protected $ruta_actual;

	public function __construct()
	{		
		$this->CI =& get_instance();
		$this->ruta_actual = strtolower( $this->CI->router->class . '/' . $this->CI->router->method );
	}

	public function validar_sesion()
	{	
		if( ! $this->CI->session->UID) {	
			
			if( ! $this->is_public( $this->ruta_actual ) ) {
				if( $this->CI->input->is_ajax_request() ){
					die( json_encode( array( 'res'=>EXIT_ERROR, 'msg' => "Su sesión se ha cerrado, por favor vuelva a ingresar." ) ) );
				}else{
					redirect("usuarios/logout");
				}
			}
		}else{

			$this->validar_permisos( $this->CI->session->USER_ROL );
		}
	}

	protected function is_public( $ruta ){
		$q = "SELECT estado FROM permiso_arbol WHERE permiso_arbol.ruta = '{$ruta}'";
		$q = $this->CI->db->query($q);
		$q = $q->row();

		if(empty($q)){
			redirect("errores/not_found");	
			return false;
		}

		if($q->estado == PERMISOS_ARBOL_PUBLICO){
			return true;
		}
   
		if($q->estado == PERMISOS_ARBOL_PROTEGIDO){
			
			if( ! empty($this->CI->session->UID)) {	
				return true;
			}
		}
		return false;
	}

	protected function validar_permisos( $rol ){
		
		if( ! $this->is_public( $this->ruta_actual ) ){

			if( ! $this->CI->Roles_model->has_permission( $rol, $this->ruta_actual ) ) {			
				if( $this->CI->input->is_ajax_request() ){
					
					die( json_encode( array( 'res'=>EXIT_ERROR, 'msg' => "No tiene permisos para realizar esta acción." ) ) );
				}else{
					redirect("errores/vista_privada");	
				}
			} else {

				$this->CI->session->set_userdata('IDFORMULARIO', "");

				if($this->ruta_actual != "formularios/ver_formulario"){

					$formulario = $this->CI->Roles_model->consultar_formulario_activo_by_usuario($this->CI->session->UID);

					if( ! empty($formulario)) {
						$this->CI->session->set_userdata('IDFORMULARIO',$formulario->idformulario);
					}
				}
				
			}
		}
	}
}
