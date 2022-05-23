<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rutas extends CI_Controller {

	public function __construct(){
        parent::__construct();     
        $this->load->library("ControllerList", NULL, "cl");
        $this->load->model("Permisos_model");      
    }

    public function index(){

        foreach ($this->cl->getControllers() as $controller) {

            if ( isset( $controller["clase"] ) ) {
                $parent_id = 0;
                $data_permisos = array("ruta"=>$controller["clase"]);
                $consultar_permisos = $this->Permisos_model->consultar_by_data($data_permisos);

                if( empty( $consultar_permisos) ) {
                    $data = array( "ruta" => strtolower($controller["clase"]),  "estado" => PERMISOS_ARBOL_PRIVADO, "padre" => $parent_id );
                    $permiso_almacenado = $this->Permisos_model->ingresar($data);
                    $parent_id = $permiso_almacenado;

                }else{
                    $parent_id = $consultar_permisos[0]->idpermiso_arbol;
                }

                for ($i=0; $i < count($controller["method"]); $i++) { 

                    $data_permisos = array("ruta"=>strtolower($controller["clase"] . "/" . $controller["method"][$i]));
                    $consultar_permisos = $this->Permisos_model->consultar_by_data($data_permisos);

                    if( empty( $consultar_permisos) ) {
                        $data = array( "ruta" => strtolower($controller["clase"] . "/" . $controller["method"][$i]),  "estado" => PERMISOS_ARBOL_PRIVADO, "padre" => $parent_id );
                        $permiso_almacenado = $this->Permisos_model->ingresar($data);
                    }
                } 
            }
        }
    }
}