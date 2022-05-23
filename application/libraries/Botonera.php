<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Botonera Class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @version     1.0 / Mayo - 2019
 */

class Botonera{

	private $ci;
	private $buttons = "";
	private $text = "";
	private $attributes = "";
	private $ruta = "";
	private $name = "";
	private $id = "";
	private $classes = "";
	private $title = "";
	private $icon = "";

	public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function get_buttons( $functions ) {
    	$this->buttons = "";
    	
    	for ($i=0; $i < count( $functions ); $i++) { 

    		$consultar_permisos = $this->ci->Roles_model->has_permission( $this->ci->session->USER_ROL, $this->ci->router->class . '/' . $functions[$i]["method"] );
    		
    		if( ! empty( $consultar_permisos ) ) {

    			$this->text			= ( isset( $functions[$i]["text"] ) ) 		? $functions[$i]["text"] 		: "";
    			$this->attributes 	= ( isset( $functions[$i]["attributes"] ) ) ? $functions[$i]["attributes"] 	: "";
    			$this->ruta 		= ( isset( $functions[$i]["ruta"] ) ) 		? $functions[$i]["ruta"] 		: "";
    			$this->name 		= ( isset( $functions[$i]["name"] ) ) 		? $functions[$i]["name"] 		: "";
    			$this->id 			= ( isset( $functions[$i]["id"] ) ) 		? $functions[$i]["id"] 			: "";
    			$this->classes 		= ( isset( $functions[$i]["classes"] ) ) 	? $functions[$i]["classes"] 	: "";
				$this->title 		= ( isset( $functions[$i]["title"] ) ) 		? $functions[$i]["title"] 		: "";
				$this->icon 		= ( isset( $functions[$i]["icon"] ) ) 		? $functions[$i]["icon"] 		: "";

    			if( empty( $functions[$i]["link"] ) ) {
					$this->buttons .= '<button type="button" name="' . $this->name . '" id="' . $this->id . '" class="' . $this->classes . ' btn btn-shadow font-weight-bold mr-2" title="' . $this->title . '" data-tooltip="tooltip" ' . $this->attributes . '><i class="' . $this->icon . '"></i>' . $this->text . '</button> ';
            	}else{
					$this->buttons .= '<a href="' . $this->ruta . '" title="' . $this->title . '" data-tooltip="tooltip" name="' . $this->name . '" id="' . $this->id . '" class="btn btn-shadow font-weight-bold mr-2 ' . $this->classes . '" ' . $this->attributes . '>
					<i class="' . $this->icon . '"></i>
					' . $this->text . '</a> ';
            	}
            }
    	}
    	return $this->buttons;
    }
}
