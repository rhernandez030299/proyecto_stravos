<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menus_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar los menu registrados
     * @return array           con el resultado de los menu
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "select * from menu ";
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'where (ruta LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or nombre LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or padre LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or clase LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or icono LIKE "%'.$_POST["search"]["value"].'%") ';
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "padre";
            
            if($_POST['order']['0']['column']==0){
                $order = "ruta";
            }
            if($_POST['order']['0']['column']==1){
                $order = "nombre";
            }
            if($_POST['order']['0']['column']==2){
                $order = "clase";
            }
            if($_POST['order']['0']['column']==3){
                $order = "icono";
            }
            if($_POST['order']['0']['column']==4){
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
     * Consultar el conteo de los menu con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select * from menu ";
        if( ! empty($busqueda)){
            $consulta .= "where (ruta LIKE '%".$busqueda."%' or clase LIKE '%".$busqueda."%' or icono LIKE '%".$busqueda."%' or padre LIKE '%".$busqueda."%' or nombre LIKE '%".$busqueda."%') ";
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar menu filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('menu', array('idmenu' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla menu
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        return $this->db->insert('menu', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla menu
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idmenu', $id);
        return $this->db->update('menu', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los menu
     * @return Mixed información de los menu
     */
    public function consultar($order = false){
        if( ! empty($order)){
            $this->db->order_by('padre',"asc");
        }
        $this->db->from('menu');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar menu arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla menu table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        
        $result = $this->db->get_where('menu', $data);
        return $result->result();
    }

    
}