<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vistas_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla vista
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        return $this->db->insert('vista', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla vista
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idvista', $id);
        return $this->db->update('vista', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla vista
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar_by_usuario($idusuario,$data){
        $this->db->where('idusuario', $idusuario);
        return $this->db->update('vista', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla vista
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar_by_historia($idhistoria,$data){
        $this->db->where('idhistoria', $idhistoria);
        return $this->db->update('vista', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla vista
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar_by_historia_y_usuario($idhistoria, $idusuario, $data){
        $this->db->where('idhistoria', $idhistoria);
        $this->db->where('idusuario', $idusuario);
        return $this->db->update('vista', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar vista arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla vista table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        
        $result = $this->db->get_where('vista', $data);
        return $result->result();
    }

    
}