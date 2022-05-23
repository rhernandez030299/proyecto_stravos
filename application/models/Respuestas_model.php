<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Respuestas_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar respuesta filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('respuesta', array('idrespuesta' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla respuesta
     * @param  array() $data   almacena el campo 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('respuesta', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla respuesta
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idrespuesta', $id);
        return $this->db->update('respuesta', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los respuesta
     * @return Mixed información de los respuesta
     */
    public function consultar(){
        $this->db->from('respuesta');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar respuesta dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla respuesta table
     * @return boolean         retorna Información del tipo de respuesta
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('respuesta', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla respuestaes
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('respuestaes', array('idrespuestaes' => $id));           
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar respuesta dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla respuesta table
     * @return boolean         retorna Información del tipo de respuesta
     */
    public function consultar_by_data_conteo($data){
        $result = $this->db->get_where('respuesta', $data);
        return $result->num_rows();
    }

}