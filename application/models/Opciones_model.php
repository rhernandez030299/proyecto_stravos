<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Opciones_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

   
    //-----------------------------------------------------------------------------
    /**
     * Consultar los opcion registradas
     * @return array           
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "SELECT o.* FROM opcion o ";
        
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'where o.nombre LIKE "%'.$_POST["search"]["value"].'%" ';               
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "o.idopcion";
            if($_POST['order']['0']['column']==0){
                $order = "o.idopcion";
            }
            if($_POST['order']['0']['column']==1){
                $order = "o.nombre";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY o.idopcion DESC ';
        }

        if( $_POST["length"] != -1 )
        {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $result = $this->db->query($query);
        return $result->result();
    }
    //-----------------------------------------------------------------------------
    /**
     * Consultar el conteo de los opcion con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select * from opcion ";
        if( ! empty($busqueda)){
            $consulta .= "where (nombre LIKE '%".$busqueda."%') ";
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar opcion filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('opcion', array('idopcion' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla opcion
     * @param  array() $data   almacena el campo 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('opcion', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla opcion
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idopcion', $id);
        return $this->db->update('opcion', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los opcion
     * @return Mixed información de los opcion
     */
    public function consultar(){
        $this->db->from('opcion');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar opcion dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla opcion table
     * @return boolean         retorna Información del tipo de opcion
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('opcion', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla opciones
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('opciones', array('idopciones' => $id));           
    }

}