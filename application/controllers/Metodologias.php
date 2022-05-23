<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Metodologias extends CI_Controller {

	public function __construct(){
        parent::__construct();   
        $this->load->library('Layout_manager',NULL,'lm'); 
        $this->load->model("Metodologias_model");        
    }

    //-----------------------------------------------------------------------------
    public function index(){
		redirect('metodologias/listar');
	}

	//-----------------------------------------------------------------------------
	/**
	 * Vista del listado de metodologia
	 * @return void
	 */
	public function listar(){
		$this->lm->add_js('jquery-tree/jstree.min.js');	
		$this->lm->add_css('jquery-tree/style.css');
		$this->lm->add_js('metodologias.js');
		$this->lm->set_title('Metologia');
		$this->lm->set_page('metodologias/listar');		

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
		$consultar_metodologias = $this->Metodologias_model->consultar(true);
		$data = array("consultar_metodologias"=>$consultar_metodologias, "botones"=>$botones);
		$this->lm->render($data);
	}

	//-----------------------------------------------------------------------------
	/**	
	 * Recorrer todos los datos y almacenar en un array para enviarlos
	 * @param  array $data     Almacena los datos de las metodologias
	 * @return array      	   Datos de las metodologias
	 */
	public function recorrer_datos( $data ){
		$datos =  array();
		$filtered_rows = count($data);
		foreach($data as $row)
		{
			$sub_array = array();
			$sub_array[] = $row->idmetodologia;
			$sub_array[] = $row->nombre;
			$sub_array[] = $row->url;
				
			$function = [
				[
					"method" 		=> "actualizar", 
					"name"			=> "update", 
					"id"			=> $row->idmetodologia, 
					"classes" 		=> "btn btn-success update",
					"icon" 			=> "flaticon-edit",
					"title" 		=> "Modificar",
					"attributes" 	=> "",
					"link" 			=> FALSE
				]
			];

			$botones = $this->btn->get_buttons($function);

			$sub_array[] = $botones;
			$sub_array[] = $row->idmetodologia;
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
				"recordsFiltered"   =>  $this->Metodologias_model->consultar_conteo_by_filtro($busqueda),
				"data"              =>  $datos
		);
		return $output;
	}

	//-----------------------------------------------------------------------------
	/**
	 * Lista de metodologias
	 * @return json
	 */
	public function listar_metodologias(){
		$data = $this->Metodologias_model->consultar_by_filtros();
		$output = $this->recorrer_datos($data);
		echo json_encode($output);
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

		$data = $this->Metodologias_model->consultar_by_id($id);

		if( empty($data) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología no existe" ) ) );
		}

		$data_archivo = array("idmetodologia" => $id);
		$consultar_archivo = $this->Metodologias_model->consultar_archivo_metodologia_by_data($data_archivo);

		$output = array();
		$output["id"] = $data->idmetodologia;
    $output["nombre"] = $data->nombre;
		$output["url"] = $data->url;
		$output["archivos"] = $consultar_archivo;
		
		echo json_encode($output);
	}

	//-----------------------------------------------------------------------------	
	/**
	 * Actualización de metodologias
	 * @return json
	 */
	public function actualizar(){
		$id = $this->input->post("id", TRUE);
		$nombre = ucwords($this->input->post("nombre", TRUE));
		
		if( empty( $id ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el identificador" ) ) );
		}

		$consultar_permiso = $this->Metodologias_model->consultar_by_id($id);


		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}

		$data = array("nombre"=>$nombre);
		$data = $this->Metodologias_model->modificar($id,$data);

		if( empty($data)) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología no tiene cambios" ) ) );
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La metodología se modifico correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * creación de metodologias
	 * @return json
	 */
	public function crear(){
        $nombre = ucwords($this->input->post("nombre", TRUE));
		
		if( empty( $nombre ) ){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese el nombre" ) ) );
		}
		
		$data = array("nombre"=>$nombre,"url"=>rand(1000, 10000));
		$idingresado = $this->Metodologias_model->ingresar($data);

		if( empty($idingresado)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología no se ingreso a la base de datos" ) ));
		}

		if(isset($_FILES['file'])){

            $archivos_guardados = $this->upload_files("./uploads/", $_FILES['file']);        

            for ($i=0; $i < count($archivos_guardados); $i++) { 
            
                $data_archivo = array("ruta"=>$archivos_guardados[$i][0], "client_name" => $archivos_guardados[$i][1], "idmetodologia" => $idingresado, "peso" => $archivos_guardados[$i][2]);
                $crear_archivo = $this->Metodologias_model->ingresar_archivo($data_archivo);
                
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


		$url = generar_url($idingresado .  "-" . strtolower($nombre));
		$data = array("url"=>$url);
		$data = $this->Metodologias_model->modificar($idingresado, $data);

		if( empty($data)) {
			$this->db->trans_rollback();
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "La metodología no se ingreso correctamente" ) ));
		}

		echo json_encode( array( 'res' => EXIT_SUCCESS, 'msg' => "La metodología fue ingresada correctamente" ) );
	}

	//-----------------------------------------------------------------------------
	/**
	 * Carga de imagenes
	 * @return json
	 */
	public function upload_file() {

		$id = $this->input->post("idmetodologia", true);

		if( empty( $id ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		$consultar_metodologia = $this->Metodologias_model->consultar_by_id($id);

    	if( empty($consultar_metodologia)){
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Por favor ingrese un identificador válido")));
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

	        $data = array("ruta"=>$upload_data["orig_name"], "client_name" => $upload_data["client_name"], "idmetodologia" => $id, "peso" => $upload_data["file_size"]);
        	$data_modificar = $this->Metodologias_model->ingresar_archivo($data);
        	
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

		$id = $this->input->post("idmetodologia", true);
		$nombre = $this->input->post("nombre", true);

		if( empty( $id ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El identificador no existe" ) ) );
		}

		if( empty( $nombre ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El nombre no existe" ) ) );
		}
	
		$eliminar_foto = $this->Metodologias_model->eliminar_archivo_metodologia($nombre, $id);

		if( empty( $eliminar_foto ) ) {
			die( json_encode( array( 'res' => EXIT_ERROR, 'msg' => "El archivo no se elimino" ) ) );
		}

		$path = "./uploads/" . $nombre;

		if (file_exists($path)) {
			unlink($path);
    	}

    	echo json_encode( array( 'res' => EXIT_ERROR, 'msg' => "Se elimino correctamente" ) );
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


}