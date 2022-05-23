<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar los permisos registrados
     * @return array           con el resultado de los permisos
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "select * from permiso_arbol ";
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'where (ruta LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or estado LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or padre LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or alias LIKE "%'.$_POST["search"]["value"].'%") ';
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "padre";
            
            if($_POST['order']['0']['column']==0){
                $order = "ruta";
            }
            if($_POST['order']['0']['column']==1){
                $order = "alias";
            }
            if($_POST['order']['0']['column']==2){
                $order = "estado";
            }
            if($_POST['order']['0']['column']==3){
                $order = "padre";
            }

            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY padre ASC ';
        }

        if(isset($_POST["length"])){
            if($_POST["length"] != -1)
            {
                $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
        }

        $result = $this->db->query($query);
        return $result->result();
    }
    //-----------------------------------------------------------------------------
    /**
     * Consultar el conteo de los permisos con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select * from permiso_arbol ";
        if( ! empty($busqueda)){
            $consulta .= "where (ruta LIKE '%".$busqueda."%' or estado LIKE '%".$busqueda."%' or padre LIKE '%".$busqueda."%' or alias LIKE '%".$busqueda."%') ";
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar permisos filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('permiso_arbol', array('idpermiso_arbol' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla permisos
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('permiso_arbol', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla permisos
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idpermiso_arbol', $id);
        return $this->db->update('permiso_arbol', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los permisos
     * @return Mixed información de los permisos
     */
    public function consultar($order = false){
        if( ! empty($order)){
            $this->db->order_by('padre',"asc");
        }
        $this->db->from('permiso_arbol');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar permisos arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla permisos table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('permiso_arbol', $data);
        return $result->result();
    }

    
}