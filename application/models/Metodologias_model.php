<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Metodologias_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

   
   //-----------------------------------------------------------------------------
    /**
     * Consultar los metodologia registradas
     * @return array           
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "SELECT m.* FROM metodologia m ";
        
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'where m.nombre LIKE "%'.$_POST["search"]["value"].'%" ';               
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "m.idmetodologia";
            if($_POST['order']['0']['column']==0){
                $order = "m.idmetodologia";
            }
            if($_POST['order']['0']['column']==1){
                $order = "m.nombre";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY m.idmetodologia DESC ';
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
     * Consultar el conteo de los proyecto con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select * from metodologia ";
        if( ! empty($busqueda)){
            $consulta .= "where (nombre LIKE '%".$busqueda."%') ";
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('metodologia', array('idmetodologia' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('metodologia', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla proyecto
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idmetodologia', $id);
        return $this->db->update('metodologia', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los proyecto
     * @return Mixed información de los proyecto
     */
    public function consultar(){
        
        $this->db->from('metodologia');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('metodologia', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla archivos proyecto
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_archivo( $data ){
        $insert = $this->db->insert('archivo_metodologia', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_archivo_metodologia_by_data($data){
        $result = $this->db->get_where('archivo_metodologia', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla miembro
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_archivo_metodologia($ruta, $id){
        return $this->db->delete('archivo_metodologia', array('ruta' => $ruta, 'idmetodologia' => $id));           
    }
    
}