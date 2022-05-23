<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Modulos_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }


    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla modulos
     * @param  array() $data   
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('modulo', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla modulos
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idmodulo', $id);
        return $this->db->update('modulo', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar modulos filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del modulos o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('modulo', array('idmodulo' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla usuario
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('modulo', array('idmodulo' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar modulo dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla grupo table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $this->db->order_by("posicion", "asc");
        $result = $this->db->get_where('modulo', $data);        
        
        return $result->result();
    }
}