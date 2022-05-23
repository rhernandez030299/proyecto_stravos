<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm');         
        $this->load->model("Usuarios_model");
		$this->load->model("Roles_model");
		$this->load->model("Proyectos_model");
		$this->load->model("Historias_model");
		$this->load->helper('email');
    }

    //-----------------------------------------------------------------------------
    public function index(){
    	//redirect('usuarios/login');
		redirect('usuarios/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista de login
	 * @return void
	 */
	public function login(){

		if( ! empty( $this->session->USER_ROL ) ){
			$consultar_rol = $this->Roles_model->consultar_by_id($this->session->USER_ROL);
			if( ! empty($consultar_rol)){
				redirect($consultar_rol->ruta);
			}
		}
		
		$this->lm->add_css('adminlte/iCheck/square/blue.css');
		$this->lm->add_css('login');
		$this->lm->add_js('login');
		$this->lm->add_js('adminlte/icheck.min');

		$this->lm->set_aside('login/login_aside');
		$this->lm->set_header('login/login_header');
		$this->lm->set_body('login/login_body');
		$this->lm->set_footer('login/login_footer');
		$this->lm->set_title('Login');
		$this->lm->set_page('web/login');
		
		$this->lm->render();
	}

    //-----------------------------------------------------------------------------
    /**
     * Validación de ingreso de usuario
     * @return json
     */
    public function ingresar(){
		$usuario = $this->input->post( 'usuario' , TRUE );
		$password = $this->input->post( 'password', TRUE );		

		if( empty( $usuario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el usuario" ) ) );
		}

		if( empty( $password ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la contraseña" ) ) );
		}
		
		$user = $this->Usuarios_model->consultar_by_nombre_usuario( $usuario );
		
		if( empty( $user ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no se encuentra en en la base de datos. Por favor verifique sus datos." ) ) );
		}
		
		if( $user->estado == USUARIO_INACTIVO ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario se encuentra inactivo." ) ) );
		}

		if( ! password_verify( $password , $user->clave ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La contraseña no coincide para el usuario especificado. Por favor verifique sus datos." ) ) );	
		}
		
		$rol = $this->Usuarios_model->consultar_rol_by_id($user->idusuario);

		$this->session->set_userdata('USER_LOGGED',TRUE);
		$this->session->set_userdata('UID',$user->idusuario);
		$this->session->set_userdata('USER_ROL',$user->idrol);
		$this->session->set_userdata('USER_ROL_NAME',$rol->nombre);
		$this->session->set_userdata('USER_NAME',$user->nombre);
		$this->session->set_userdata('USER_EMAIL',$user->correo);
		$this->session->set_userdata('USER_LAST_NAME',$user->apellido);
		$this->session->set_userdata('USER_LOGIN',$user->nombre_usuario);
		$this->session->set_userdata('RUTA_IMAGEN',$user->ruta_imagen);
		
		$consultar_rol = $this->Roles_model->consultar_by_id($this->session->USER_ROL);

		if( ! empty($consultar_rol)) {
			die(json_encode( array( 'res'=>EXIT_SUCCESS, 'url' => base_url($consultar_rol->ruta)  ) ) );
		}

		echo json_encode( array( 'res'=>EXIT_SUCCESS, 'url' => base_url('productos') ) );
		
	}
	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de los usuarios
	 * @return array      	   Datos de los usuarios	
	 */
	public function recorrer_datos( $data ){
		 	$datos =  array();
            $filtered_rows = count($data);
            foreach($data as $row)
            {
            	$rol = $this->Roles_model->consultar_by_id($row->idrol);

                $estado_button = "inactivo";
                $title_button = "Activar";
				$icon = "far fa-eye";
				$classStatus = "warning";
                if($row->estado==USUARIO_ACTIVO){
                    $estado_button = "activo";
                    $icon = "far fa-eye-slash";
					$title_button = "Inactivar";
					$classStatus = "success";
				}
				
				$created_at = formato_fecha( date("Y-m-d", strtotime($row->created_at)) );
				$created_at = $created_at["dia"] . " de " . $created_at["mes"] . " de " . $created_at["ano"];
				
                $sub_array = array();
                $sub_array[] = $rol->nombre;
                $sub_array[] = $row->nombre_usuario;                
                $sub_array[] = $row->nombre . " " . $row->apellido;
				$sub_array[] = $row->correo;
				$sub_array[] = $created_at;
				$sub_array[] = "<span class='label font-weight-bold label-lg label-light-".$classStatus." label-inline'>".ucfirst($estado_button)."</span>";

                $function = [
	                [
	                	"method" 		=> "actualizar", 
	                	"name"			=> "update", 
	            		"id"			=> $row->idusuario, 
						"classes" 		=> "btn btn-success update", 
						"icon" 			=> "flaticon-edit", 
	            		"title" 		=> "Modificar",
        				"attributes" 	=> "",
	            		"link" 			=> FALSE
            		],
            		[
            			"method" 		=> "eliminar", 
	                	"name"			=> "delete", 
	            		"id"			=> $row->idusuario, 
						"classes" 		=> "btn btn-danger delete glyphicon glyphicon-trash", 
						"icon" 			=> "flaticon2-trash", 
	            		"title" 		=> "Eliminar", 
        				"attributes" 	=> "",
	            		"link" 			=> FALSE
                	],
            		[
            			"method" 		=> "cambiar_estado", 
	                	"name"			=> $estado_button, 
	            		"id"			=> $row->idusuario, 
						"classes" 		=> "btn btn-warning ".$estado_button."", 
						"icon" 			=> $icon, 
	            		"title" 		=> $title_button, 
        				"attributes" 	=> "",
	            		"link" 			=> FALSE
                	],
            		[
            			"method" 		=> "cambiar_contrasena", 
	                	"name"			=> "change_password", 
	            		"id"			=> $row->idusuario, 
	            		"classes" 		=> "btn btn-primary change_password glyphicon glyphicon-lock", 
						"title" 		=> "Cambiar contraseña",
						"icon"          => 'flaticon-lock',
        				"attributes" 	=> "", 
	            		"link" 			=> FALSE
                	]
                ];

                $botones = $this->btn->get_buttons($function);

                $sub_array[] = $botones;

                $sub_array["estado"] = $row->estado;
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
                "recordsFiltered"   =>  $this->Usuarios_model->consultar_conteo_by_filtro($busqueda),
                "data"              =>  $datos
            );
            return $output;
	}

	//-----------------------------------------------------------------------------
	
	/**
	 * Lista de usuarios
	 * @return json
	 */
	public function listar_usuarios(){
		$data = $this->Usuarios_model->consultar_by_filtros();
		$output = $this->recorrer_datos($data);
		echo json_encode($output);
	}
	//-----------------------------------------------------------------------------
	/**
	 * Listar usuarios filtradas por el identificador
	 * @return json
	 */
	public function listar_id(){
		$id = $this->input->post("id", TRUE );
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$data = $this->Usuarios_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ) );
		}
		
		$output = array();
		$output["id"] = $data->idusuario;
        $output["user"] = $data->nombre_usuario;
        $output["rol_id"] = $data->idrol;
        $output["estado"] = $data->estado;
        $output["nombre"] = $data->nombre;
		$output["correo"] = $data->correo;
		$output["apellido"] = $data->apellido;
		$output["ruta_imagen"] = $data->ruta_imagen;
		
		echo json_encode($output);
	}
	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de usuarios
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("usuario_id_update", TRUE);
		$usuario = $this->input->post("user_update", TRUE);
		$rol_id = $this->input->post("rol_id_update", TRUE);
		$password = $this->password_hash($this->input->post("password" , TRUE));
		$nombre = $this->input->post("nombre_update", TRUE);
		$apellido = $this->input->post("apellido_update", TRUE);
		$correo = $this->input->post("correo_update", TRUE);

		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( empty( $rol_id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el rol del usuario") ) );
		}

		if( empty( $usuario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el usuario" ) ) );
		}

		if( empty( $apellido ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el apellido" ) ) );
		}

		if( empty( $correo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el correo electronico" ) ) );
		}

		$user = $this->Usuarios_model->consultar_by_nombre_usuario( $usuario );

		if(!empty($user)){
	        if($user->idusuario!=$id){
	            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este nombre de acceso ya existe" ) ) );
	        }
	    }

	    $data = array("correo" => $correo);
		$consultar_correo = $this->Usuarios_model->consultar_by_data( $data );

		if(!empty($consultar_correo)){
	        if($consultar_correo[0]->idusuario!=$id){
	            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este correo electronico ya existe" ) ) );
	        }
	    }

	    if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		$data = array("nombre"=> ucfirst($nombre),"apellido"=>ucfirst($apellido),"nombre_usuario"=>$usuario,"idrol"=>$rol_id,"clave"=>$password,"estado"=>USUARIO_ACTIVO, "correo" => $correo);

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

        	$data = array_merge($data, array("ruta_imagen" => $ruta_imagen));
		}
        	
		$data = $this->Usuarios_model->modificar($id,$data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario se modifico correctamente" ) );
	}
	//-----------------------------------------------------------------------------	
	/**
	 * Cambiar contraseña de usuarios
	 * @return json
	 */
	public function cambiar_contrasena(){
		$id = $this->input->post("id", TRUE);
		$password = $this->password_hash($this->input->post("password", TRUE));
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if(empty($password)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la contraseña" ) ) );
		}

		$data = array("clave"=>$password);
		$result = $this->Usuarios_model->cambiar_contrasenia_by_id($id,$data);
		
		if(empty($result)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La contraseña no se modifico de la base de datos" ) ) );
		}
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La contraseña fue modificada correctamente" ) );

	}
	//-----------------------------------------------------------------------------	
	/**
	 * Cambiar contraseña de usuario generalmente
	 * @return json
	 */
	public function cambiar_contrasena_general(){

		if(empty($this->session->UID)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Inicia sesión" ) ) );
		}

		$password_actual = $this->input->post("password_actual", TRUE);
		$password_nueva = $this->input->post("password", TRUE);

		if(empty($password_actual)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la contraseña actual" ) ) );
		}

		if(empty($password_nueva)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la nueva contraseña" ) ) );
		}

		$usuario = $this->Usuarios_model->consultar_by_id($this->session->UID);

		if(empty($usuario)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe" ) ) );
		}		

		if( ! password_verify( $password_actual , $usuario->clave ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La contraseña no coincide" ) ) );	
		}
		
		$password = $this->password_hash($password_nueva);

		$data = array("clave"=>$password);
		$result = $this->Usuarios_model->cambiar_contrasenia_by_id($this->session->UID,$data);
		
		if(empty($result)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La contraseña no se modifico de la base de datos" ) ) );
		}
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La contraseña fue modificada correctamente" ) );

	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de usuarios
	 * @return json
	 */
	public function crear(){
		$usuario = $this->input->post("user", TRUE);
		$rol_id = $this->input->post("rol_id", TRUE);
		$password = $this->password_hash($this->input->post("password" , TRUE));
		$nombre = $this->input->post("nombre", TRUE);
		$apellido = $this->input->post("apellido", TRUE);
		$correo = $this->input->post("correo", TRUE);

		if( empty( $usuario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el usuario" ) ) );
		}

		$user = $this->Usuarios_model->consultar_by_nombre_usuario( $usuario );
	
		if( ! empty($user)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este usuario ya se encuentra registrado") ) );
		}

		$data = array("correo" => $correo);
		$consultar_correo = $this->Usuarios_model->consultar_by_data( $data );
	
		if( ! empty($consultar_correo)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este correo ya se encuentra registrado") ) );
		}

		if( empty( $rol_id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el rol del usuario") ) );
		}

		if( empty( $password ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la contraseña" ) ) );
		}

		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $apellido ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el apellido" ) ) );
		}

		if( empty( $correo ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el correo electronico" ) ) );
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


		$data = array("nombre"=> ucfirst($nombre),"apellido"=>ucfirst($apellido),"nombre_usuario"=>$usuario,"idrol"=>$rol_id,"clave"=>$password,"estado"=>USUARIO_ACTIVO, "correo" => $correo, "ruta_imagen" => $ruta_imagen);	
		$usuario_almacenado = $this->Usuarios_model->ingresar($data);

		if( empty($usuario_almacenado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no se ingreso a la base de datos" ) ));
		}
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario fue ingresado correctamente" ) );
	}
	//-----------------------------------------------------------------------------	
	/**
	 * Eliminación de usuarios
	 * @return json
	 */
	public function eliminar(){
		$id = $this->input->post("id", TRUE);

		if(empty($id)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe") ) );
		}

		$consultar_usuario = $this->Usuarios_model->consultar_by_id($id);

		if( empty($consultar_usuario)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no existe en la base de datos") ) );
		}

		$data_miembro = array("idusuario" => $id);
		$consultar_miembro = $this->Proyectos_model->consultar_miembro_by_data($data_miembro);

		if( ! empty($consultar_miembro)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "no es posible eliminar este usuario, tiene proyectos asigandos") ) );
		}

		$data_historia = array("idusuario" => $id);
		$consultar_historia = $this->Historias_model->consultar_by_data($data_historia);

		if( ! empty($consultar_historia)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "no es posible eliminar este usuario, tiene historias creadas") ) );
		}

		$usuario_eliminado = $this->Usuarios_model->eliminar($id);

		if( empty($usuario_eliminado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no se elimino de la base de datos") ) );
		}
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario fue eliminado correctamente") );
	}
	//-----------------------------------------------------------------------------
	/**
	 * Cambio de estado en la tabla usuarios
	 * @return json
	 */
	public function cambiar_estado(){
    	$id = $this->input->post("id",TRUE);
    	$estado = $this->input->post("estado",TRUE);

    	if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		if( $estado != USUARIO_ACTIVO && $estado != USUARIO_INACTIVO ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el estado" ) ) );
		}

		if($estado==USUARIO_ACTIVO){
            $estado = USUARIO_INACTIVO;
        }else{
            $estado = USUARIO_ACTIVO;
        }

		$data = array("estado" => $estado);

		$data = $this->Usuarios_model->cambiar_estado_by_id($id,$data);

		if(empty($data)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no tuvo cambios" ) ) );
		}

		if ($estado==USUARIO_ACTIVO){
			echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario fue activado correctamente" ) );
		}else{
			echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario fue inactivado correctamente" ) );	
		}
    }
	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de usuarios
	 * @return void
	 */
	public function listar(){
		
		$this->lm->add_js('usuarios.js');		
		$this->lm->set_title('Usuarios');
		$this->lm->set_page('usuarios/listar');		

		$roles = $this->Roles_model->consultar();

        $function = [
            [
            	"method" 		=> "crear", 
            	"name"			=> "add_button", 
        		"id"			=> "add_button", 
        		"classes" 		=> "btn btn-primary btn-flat", 
        		"title" 		=> "Nuevo", 
				"text"			=> "Nuevo",
				"icon"			=> "fa fa-plus",
        		"link" 			=> FALSE
    		]
        ];

        $botones = $this->btn->get_buttons($function);

		$data = array("roles"=>$roles, "botones" => $botones);
		$this->lm->render($data);
	}
	/**
	 * Cerrar sesión
	 * @return void
	 */
    public function logout(){
		$this->session->sess_destroy();
		redirect('usuarios/login');
	}

	/**
	 * Convertir la contraseña
	 * @param  string $pass contraseña del usuario
	 * @return string       contraseña convertida
	 */
    protected function password_hash( $pass ){
		return password_hash($pass, PASSWORD_BCRYPT,array('cost'=>9));
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista de registro
	 * @return void
	 */
	public function registro(){
		$this->lm->add_js('registro');
		$this->lm->add_css('registrar');
		$this->lm->add_js('adminlte/icheck.min');

		$this->lm->set_header('login_header');
		$this->lm->set_body('login_body');
		$this->lm->set_footer('login_footer');
		$this->lm->set_title('Registro');
		$this->lm->set_page('web/registro');

		$this->lm->render();
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de usuarios
	 * @return json
	 */
	public function crearestudiante(){

		$nombre = $this ->input->post("nombre", TRUE);
		$apellido = $this ->input->post("apellido", TRUE);
		$usuario = $this ->input->post("user", TRUE);
		$email = $this ->input->post("email", TRUE);
		$password = $this->password_hash($this->input->post("password" , TRUE));

		if( empty( $password ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese la contraseña" ) ) );
		}
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese su nombre" ) ) );
		}

		if( empty( $apellido ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese su apellido" ) ) );
		}

		if( empty( $usuario ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese su nombre de usuario" ) ) );
		}

		if( empty( $email ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese su correo electrónico" ) ) );
		}

		$user = $this->Usuarios_model->consultar_by_nombre_usuario( $usuario );

		if( ! empty( $user )){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Este usuario ya se encuentra registrado") ) );
		}

		if ( ! valid_email($email)){
            die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingresa un correo electrónico válido" ) ) );
        }

		$data_usuario = array("correo" => $email);
		$consultar_usuario = $this->Usuarios_model->consultar_by_data( $data_usuario );

		if( ! empty( $consultar_usuario )){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El correo electrónico ya se encuentra registrado") ) );
		}

		$data = array("nombre"=>$nombre, "apellido"=>$apellido, "nombre_usuario"=>$usuario,"correo"=>$email,"idrol"=>ROL_ESTUDIANTE,"clave"=>$password,"estado"=>USUARIO_INACTIVO);
		$usuario_almacenado = $this->Usuarios_model->ingresar($data);

		if( empty($usuario_almacenado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no se ingreso a la base de datos" ) ));
		}
		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario fue ingresado correctamente" ) );
	}

	//------------------------------------------------------------------------------
	/** 
	 * VISTA DE PERFIL
	 *  @return void

	 */
	public function perfil($id = FALSE){

		if(empty($id)){
			redirect("errores/not_found");	
			return;
		}

		$this->lm->set_title('Perfil');
		$this->lm->add_css('perfil');
		$this->lm->set_page('usuarios/perfil');
		$this->lm->add_js('perfil');
		
		$consultar_usuario = $this->Usuarios_model->consultar_by_id($id);

		if(empty($consultar_usuario)){
			redirect("errores/not_found");	
			return;
		}

		$estado = "Inactivo";
		if($consultar_usuario->estado == USUARIO_ACTIVO){
			$estado = "Activo";
		}

		$consultar_rol = $this->Roles_model->consultar_by_id($consultar_usuario->idrol);
	
		$data = array("nombre"=>$consultar_usuario->nombre, "apellido"=>$consultar_usuario->apellido, "user"=>$consultar_usuario->nombre_usuario, "email"=>$consultar_usuario->correo, "nombre_rol" => $consultar_rol->nombre, "ruta_imagen" => $consultar_usuario->ruta_imagen, "id" => $id, "estado" => $estado);

		$this->lm->render($data);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de usuarios
	 * @return json
	 */
	public function modificar_perfil(){
		
		$nombre = $this->input->post("nombre-profile-update", TRUE);
		$apellido = $this->input->post("apellido-profile-update", TRUE);
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		if( empty( $apellido ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el apellido" ) ) );
		}


		$data = array("nombre"=> ucfirst($nombre),"apellido"=>ucfirst($apellido));

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

        	$data = array_merge($data, array("ruta_imagen" => $ruta_imagen));
        	$this->session->set_userdata('RUTA_IMAGEN',$ruta_imagen);
		}
        	

		$this->session->set_userdata('USER_NAME',$nombre);
		$this->session->set_userdata('USER_LAST_NAME',$apellido);
	
		$data = $this->Usuarios_model->modificar($this->session->UID, $data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El usuario no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "El usuario se modifico correctamente" ) );
	}

}