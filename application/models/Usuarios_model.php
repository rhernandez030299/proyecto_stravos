<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar los usuario registrados
     * @return array           con el resultado de los usuario
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "select u.* from usuario as u inner join rol as r on u.idrol = r.idrol WHERE idusuario != " .$this->session->UID . " ";
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'AND (u.nombre_usuario LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or u.nombre LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or u.apellido LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or u.correo LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or r.nombre LIKE "%'.$_POST["search"]["value"].'%") ';
            }
        }

        if($this->session->USER_ROL != ROL_ADMIN){        
            $query .= "AND u.idrol = 3 ";         
        }

        if(isset($_POST["order"]))
        {   
            $order = "idusuario";
            if($_POST['order']['0']['column']==0){
                $order = "r.nombre";
            }
            if($_POST['order']['0']['column']==1){
                $order = "u.nombre_usuario";
            }
            if($_POST['order']['0']['column']==2){
                $order = "u.nombre";
            }
            if($_POST['order']['0']['column']==3){
                $order = "u.correo";
            }
            if($_POST['order']['0']['column']==4){
                $order = "u.created_at";
            }
            if($_POST['order']['0']['column']==5){
                $order = "u.estado";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY idusuario DESC ';
        }

        if($_POST["length"] != -1)
        {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $result = $this->db->query($query);
        return $result->result();
    }
    //-----------------------------------------------------------------------------
    /**
     * Consultar el conteo de los usuario con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select u.* from usuario as u inner join rol as r on u.idrol = r.idrol WHERE idusuario != " .$this->session->UID . " ";
        if( ! empty($busqueda)){
            $consulta .= "AND (u.nombre_usuario LIKE '%".$busqueda."%' or r.nombre LIKE '%".$busqueda."%' or u.nombre LIKE '%".$busqueda."%' or u.apellido LIKE '%".$busqueda."%' or u.correo LIKE '%".$busqueda."%')";
        }

        if($this->session->USER_ROL != ROL_ADMIN){        
            $consulta .= "AND u.idrol = 3 ";         
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }

    //-----------------------------------------------------------------------------
    /**
    * Consulta el usuario por nombre de usuario
    * @param  String $usuario El nombre de usuario
    * @return Mixed Información del usuario o FALSE
    */
    public function consultar_by_nombre_usuario( $usuario )
    {
        $query = $this->db->get_where( 'usuario', array( "nombre_usuario" => $usuario ), 1 );             
        return ( $query->num_rows() > 0 ) ? $query->row() : FALSE;
    }
 
    //-----------------------------------------------------------------------------
    /**
    * Consulta el nombre del rol dependiendo el identificador del usuario
    * @param  integer $usuario_id El id del usuario
    * @return Mixed Información del usuario o FALSE
    */
    public function consultar_rol_by_id( $usuario_id )
    {
        $consulta = "select r.* from rol as r left join usuario as u on r.idrol = u.idrol where u.idusuario = {$usuario_id}";
        $query = $this->db->query($consulta);          
        return ( $query->num_rows() > 0 ) ? $query->row() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Cambio de contraseña en la tabla usuario
     * @param  integer $id     identificador de la fila a cambiar
     * @param  array() $data   almacena la contraseña 
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function cambiar_contrasenia_by_id($id,$data){
        $this->db->where('idusuario', $id);
        return $this->db->update('usuario', $data);
    }
    //-----------------------------------------------------------------------------
    /**
     * Consultar usuario filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('usuario', array('idusuario' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla usuario
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        return $this->db->insert('usuario', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla usuario
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idusuario', $id);
        return $this->db->update('usuario', $data);
    }
    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla usuario
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('usuario', array('idusuario' => $id));           
    }

      //-----------------------------------------------------------------------------
    /**
     * Modificación de estado en la tabla usuario
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo estado
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function cambiar_estado_by_id($id,$data){
        $this->db->where('idusuario', $id);
        return $this->db->update('usuario', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar usuario filtradas por la data de la tabla
     * @param  array $data     identificador de la fila para consultar
     * @return Mixed           información de las rol o falso
     */
    public function consultar_by_data($data, $in = FALSE, $select = FALSE) {

        if( ! empty($in)){
            $this->db->where_not_in('idusuario', $in);
        }

        if( ! empty($select)){
            $this->db->select('idusuario, nombre, correo');
        }

        $result = $this->db->get_where('usuario', $data);
        return $result->result();
    }


}