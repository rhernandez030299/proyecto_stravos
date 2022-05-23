<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Preguntas_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

   
    //-----------------------------------------------------------------------------
    /**
     * Consultar los pregunta registradas
     * @return array           
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "SELECT p.* FROM pregunta p ";
        
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'where p.nombre LIKE "%'.$_POST["search"]["value"].'%" ';               
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "p.idpregunta";
            if($_POST['order']['0']['column']==0){
                $order = "p.idpregunta";
            }
            if($_POST['order']['0']['column']==1){
                $order = "p.nombre";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY p.idpregunta DESC ';
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
     * Consultar el conteo de los pregunta con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select * from pregunta ";
        if( ! empty($busqueda)){
            $consulta .= "where (nombre LIKE '%".$busqueda."%') ";
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar pregunta filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('pregunta', array('idpregunta' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla pregunta
     * @param  array() $data   almacena el campo 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('pregunta', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla pregunta
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idpregunta', $id);
        return $this->db->update('pregunta', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los pregunta
     * @return Mixed información de los pregunta
     */
    public function consultar(){
        $this->db->from('pregunta');
        $result = $this->db->get();
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los pregunta
     * @return Mixed información de los pregunta
     */
    public function consultar_tipo_pregunta(){
      $this->db->from('tipo_pregunta');
      $result = $this->db->get();
      return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar pregunta dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla pregunta table
     * @return boolean         retorna Información del tipo de pregunta
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('pregunta', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar tipo pregunta dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla pregunta table
     * @return boolean         retorna Información del tipo de pregunta
     */
    public function consultar_tipo_pregunta_by_data($data){
      $result = $this->db->get_where('tipo_pregunta', $data);
      return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla preguntas
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_by_idformulario($idformulario){
        return $this->db->delete('pregunta', array('idformulario' => $idformulario));           
    }
    
}