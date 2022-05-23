<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Presupuestos_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

   
   //-----------------------------------------------------------------------------
    /**
     * Consultar los presupuesto registradas
     * @return array           
     */
    public function consultar_by_filtros($historia_id = false){
        $query = '';
        $output = array();
        $query .= "SELECT p.*, c.nombre as nombreCategoria FROM presupuesto p
                INNER JOIN categoria AS c on c.idcategoria = p.idcategoria 
                where 1 = 1 ";
        
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'and p.descripcion LIKE "%'.$_POST["search"]["value"].'%" ';               
            }
        }

        $query .= 'and p.idhistoria = ' . $historia_id . ' ';
        
        if(isset($_POST["order"]))
        {   
            $order = "p.idpresupuesto";
            if($_POST['order']['0']['column']==0){
                $order = "p.idpresupuesto";
            }
            if($_POST['order']['0']['column']==1){
                $order = "p.nombre";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY p.idpresupuesto DESC ';
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
    public function consultar_conteo_by_filtro($busqueda, $historia_id = false){

        $consulta = "SELECT p.*, c.nombre as nombreCategoria 
            FROM presupuesto p
            INNER JOIN categoria AS c on c.idcategoria = p.idcategoria 
            where 1 = 1 ";
        if( ! empty($busqueda)){
            $consulta .= "and (p.descripcion LIKE '%".$busqueda."%') ";
        }

        $consulta .= 'and p.idhistoria = ' . $historia_id;

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
        $result = $this->db->get_where('presupuesto', array('idpresupuesto' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('presupuesto', $data);
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
        $this->db->where('idpresupuesto', $id);
        return $this->db->update('presupuesto', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los proyecto
     * @return Mixed información de los proyecto
     */
    public function consultar(){
        
        $this->db->from('presupuesto');
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
        $result = $this->db->get_where('presupuesto', $data);
        return $result->result();
    }

    public function consultar_presupuesto($idhistoria, $idmodulo = FALSE, $idfase = FALSE, $idproyecto = FALSE, $idcategoria = FALSE){

        $query = 'SELECT SUM(p.total) as total FROM presupuesto AS p 
        INNER JOIN categoria AS c on c.idcategoria = p.idcategoria
        INNER JOIN historia AS h on h.idhistoria = p.idhistoria
        INNER JOIN modulo AS m on m.idmodulo = h.idmodulo
        INNER JOIN fase AS f on f.idfase = m.idfase
        INNER JOIN proyecto_metodologia AS pm on pm.idproyecto_metodologia = f.idproyecto_metodologia
        WHERE 1 = 1 ';
        
        if( ! empty($idhistoria)) {
            $query .= 'AND p.idhistoria = "' . $idhistoria . '" ';
        }

        if( ! empty($idmodulo)) {
            $query .= 'AND h.idmodulo = "' . $idmodulo . '" ';
        }

        if( ! empty($idfase)) {
            $query .= 'AND m.idfase = "' . $idfase . '" ';
        }

        if( ! empty($idproyecto)) {
            $query .= 'AND pm.idproyecto = "' . $idproyecto . '" ';
        }

        if( ! empty($idcategoria)) {
            $query .= 'AND p.idcategoria = "' . $idcategoria . '" ';
        }
    
        $result = $this->db->query($query);
        return $result->row()->total;
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla presupuesto
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('presupuesto', array('idpresupuesto' => $id));           
    }
}