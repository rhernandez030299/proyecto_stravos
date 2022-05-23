<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fases_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function consultar_proyecto_metodologia_by_id_proyecto($url_proyecto, $url_metodologia){

        $query = 'SELECT f.*, p.idproyecto, p.nombre AS nombre_proyecto, p.url AS url_proyecto, m.url AS url_categoria, m.nombre AS nombre_categoria FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto ';
        
        if( ! empty($url_proyecto)) {
            $query .= 'WHERE p.url = "' . $url_proyecto . '" ';
        }

        if( ! empty($url_metodologia)) {
            $query .= 'AND m.url = "' . $url_metodologia . '" ';
        }

        $query .= 'ORDER BY f.posicion ASC ';

        $result = $this->db->query($query);
        return $result->result();
    }

    public function listar_proyecto_metodologia_by_id_proyecto($idproyecto){

        $query = 'SELECT f.* FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto ';
        
        if( ! empty($idproyecto)) {
            $query .= 'WHERE p.idproyecto = "' . $idproyecto . '" ';
        }

        $query .= "ORDER BY posicion asc";

        $result = $this->db->query($query);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla fases
     * @param  array() $data   
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('fase', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla fases
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idfase', $id);
        return $this->db->update('fase', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar fases filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del fases o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('fase', array('idfase' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla usuario
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('fase', array('idfase' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar fase dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla grupo table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('fase', $data);
        return $result->result();
    }
}