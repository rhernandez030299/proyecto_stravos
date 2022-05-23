<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ControllerList Class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @version     1.0 / Mayo - 2019
 */

class ControllerList {

    /**
     * Codeigniter referencia 
     */
    private $CI;

    /**
     * Array que contendrá los nombres y métodos del controlador.
     */
    private $aControllers;

    // Constructor
    function __construct() {
        // Get Codeigniter instance 
        $this->CI = get_instance();

        // Get all controllers 
        $this->setControllers();
    }

    /**
     * Return todas las clases y sus metodos
     * @return array
     */
    public function getControllers() {
        return $this->aControllers;
    }

    /**
     *  Establecer la matriz que contiene el nombre del controlador y los métodos
     */
    public function setControllerMethods($p_sControllerName, $p_aControllerMethods) {
        $this->aControllers[] = array( "clase" => $p_sControllerName, "method"=> $p_aControllerMethods);
    }

    /**
     * Buscar y configurar el controlador y los métodos.
     */
    private function setControllers() {
        // Recorrer el directorio del controlador
        foreach(glob(APPPATH . 'controllers/*') as $controller) {

            // si el valor en el bucle es un bucle de directorio a través de ese directorio
            if(is_dir($controller)) {
                // Obtiene el nombre del directorio
                $dirname = basename($controller, EXT);

                // Recorrer el subdirectorio

                foreach(glob(APPPATH . 'controllers/'.$dirname.'/*') as $subdircontroller) {
                    // Obtener el nombre subdirectorio
                    $subdircontrollername = basename($subdircontroller, EXT);

                    // Load the controller file in memory if it's not load already
                    if(!class_exists($subdircontrollername)) {
                        $this->CI->load->file($subdircontroller);
                    }
                    // Add the controllername to the array with its methods
                    $aMethods = get_class_methods($subdircontrollername);
                    $aUserMethods = array();
                    foreach($aMethods as $method) {
                        if($method != '__construct' && $method != 'get_instance' && $method != $subdircontrollername) {
                            $aUserMethods[] = $method;
                        }
                    }
                    $this->setControllerMethods($subdircontrollername, $aUserMethods);                                      
                }
            }
            else if(pathinfo($controller, PATHINFO_EXTENSION) == "php"){
                // Cargue el archivo del controlador en la memoria si aún no está cargado                
                $controllername = basename($controller, EXT);

                // Cargar la clase en la memoria (si no está ya cargado)
                if(!class_exists($controllername)) {
                    $this->CI->load->file($controller);
                }

                // Añadir controlador y métodos a la matriz.
                $aMethods = get_class_methods($controllername);
                $aUserMethods = array();
                if(is_array($aMethods)){
                    foreach($aMethods as $method) {
                        if($method != '__construct' && $method != 'get_instance' && $method != $controllername) {
                            $aUserMethods[] = $method;
                        }
                    }
                }

                $this->setControllerMethods($controllername, $aUserMethods);                                
            }
        }   
    }
}