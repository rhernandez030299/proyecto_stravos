<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->helper('email');
    }

	//-----------------------------------------------------------------------------
	/**
	 * Vista de rutas privada de errores
	 * @return void
	 */
	public function index(){
		$this->load->view('template/web/index');
	}
	
	//-----------------------------------------------------------------------------
	/**
	 * Vista de rutas privada de errores
	 * @return void
	 */
	public function contactenos(){
			
		$nombre = $this->input->post("nombre", TRUE);
        $correo = $this->input->post("correo", TRUE);
        $mensaje = $this->input->post("mensaje", TRUE);
      
        if( empty( $nombre ) ){
            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
        }

        if( empty( $correo ) ){
            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el correo electrónico" ) ) );
        }

        if( empty( $mensaje ) ){
            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingresa el mensaje" ) ) );
        }

        if ( ! valid_email($correo )){
            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingresa un correo electrónico válido" ) ) );
        }

        $this->load->helper(array('phpmailer_helper'));
        $mail = phpmailer_init();
        
        $mail->AddAddress($correo);
        $mail->addBCC("rhernandez8@udi.edu.co", "abecerra5@udi.edu.co", "carias2@udi.edu.co");
    
        $mail->Subject = "Proyecto Contactenos ";

        $data_resultados = array("nombre"=>$nombre, "correo" => $correo, "mensaje" => $mensaje);
        $body = $this->load->view("template/correos/contactenos",$data_resultados,true);

        $mail->Body = $body;
        $exito = $mail->Send();

        if(empty($exito)) {
            log_message('debug', 'El correo no se envio correctamente');
        }

        echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "Recibimos su mensaje y pronto le responderémos. ¡Gracias!" ) );

	}
	
}
?>