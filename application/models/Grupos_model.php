<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar los grupo registrados
     * @return array           con el resultado de los grupo
     */
    public function consultar_by_filtros( $idusuario = FALSE ){
        $query = '';
        $output = array();
        $query .= "select * from grupo ";

        $condicion = "WHERE";
        if( ! empty($idusuario)){
            $query .= $condicion . " idusuario_creacion = " . $idusuario . " ";
            $condicion = "AND";
        }

        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= $condicion . ' nombre LIKE "%'.$_POST["search"]["value"].'%" ';
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "idgrupo";
            
            if($_POST['order']['0']['column']==0){
                $order = "idgrupo";
            }
            if($_POST['order']['0']['column']==1){
                $order = "nombre";
            }
   
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY idgrupo ASC ';
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
     * Consultar el conteo de los grupo con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro( $busqueda, $idusuario = FALSE ){

        $consulta = "SELECT * FROM grupo ";
        $condicion = "WHERE";
        if( ! empty($idusuario)){
            $consulta .= $condicion . " idusuario_creacion = " . $idusuario . " ";
            $condicion = "AND";
        }

        if( ! empty($busqueda)){
            $consulta .= $condicion . " nombre LIKE '%".$busqueda."%' ";
        }



        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar grupo filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('grupo', array('idgrupo' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla grupo
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('grupo', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla grupo
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idgrupo', $id);
        return $this->db->update('grupo', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los grupo
     * @return Mixed información de los grupo
     */
    public function consultar($order = false){
        if( ! empty($order)){
            $this->db->order_by('idgrupo',"asc");
        }
        $this->db->from('grupo');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar grupo arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla grupo table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('grupo', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar el conteo de los grupo con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_grupo_usuario($idgrupo){

        $consulta = "select count(*) as conteo from grupo_usuario ";
        if( ! empty($idgrupo)){
            $consulta .= "where idgrupo = " . $idgrupo;
        }

        $query = $this->db->query($consulta);            
        return $query->row();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar grupo arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla grupo table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_grupo_by_data($data){
        $result = $this->db->get_where('grupo_usuario', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla grupo
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_grupo_usuario( $data ){
        $insert = $this->db->insert('grupo_usuario', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla grupo usuario
     * @param  integer $idgrupo     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_grupo_usuario($idgrupo){
        return $this->db->delete('grupo_usuario', array('idgrupo' => $idgrupo));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla grupo
     * @param  integer $idgrupo     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($idgrupo){
        return $this->db->delete('grupo', array('idgrupo' => $idgrupo));           
    }
}