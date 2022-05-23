<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar rol filtradas por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información de las rol o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('rol', array('idrol' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los datos de la tabla rol
     * @return array()         información de las rol
     */
    public function consultar(){
        $result = $this->db->get('rol');
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar los rol registrados
     * @return array           con el resultado de los rol
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "SELECT r.* FROM rol r ";
        
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= 'where r.nombre LIKE "%'.$_POST["search"]["value"].'%" ';               
            }
        }

        if(isset($_POST["order"]))
        {   
            $order = "r.idrol";
            if($_POST['order']['0']['column']==0){
                $order = "r.idrol";
            }
            if($_POST['order']['0']['column']==1){
                $order = "r.nombre";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY r.idrol DESC ';
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
     * Inserción de datos en la tabla rol
     * @param  array() $data   almacena el campo nombre del rol
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        return $this->db->insert('rol', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla rol
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo nombre
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idrol', $id);
        return $this->db->update('rol', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla permiso_rol
     * @param  array() $data   almacena los datos del id_permiso y id_rol
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_permisos( $data ){
        return $this->db->insert('permiso_rol', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla permisos menu
     * @param  array() $data   almacena los datos del id_permiso y id_rol
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_permisos_menu( $data ){
        return $this->db->insert('permiso_menu', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla permisos rol
     * @param  integer $id     identificador del rol a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_permisos($id){
        return $this->db->delete('permiso_rol', array('idrol' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla permisos menu
     * @param  integer $id     identificador del rol a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_permisos_menu($id){
        return $this->db->delete('permiso_menu', array('idrol' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar permiso_rol filtradas por la data de la tabla
     * @param  array $data     identificador de la fila para consultar
     * @return Mixed           información de las rol o falso
     */
    public function consultar_permisos_by_data($data){
        $result = $this->db->get_where('permiso_rol', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar permisos menu filtradas por la data de la tabla
     * @param  array $data     identificador de la fila para consultar
     * @return Mixed           información de las rol o falso
     */
    public function consultar_menu_by_data($data){
        $result = $this->db->get_where('permiso_menu', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar permisos arbol filtrada por el id del rol y la ruta
     * @param  integer $rol     Identificador del rol
     * @param  string  $ruta    Identificador de la ruta
     * @return Mixed           información de las rol o falso
     */
    public function has_permission( $rol, $ruta ) {
        $result = "SELECT * FROM permiso_arbol AS pa 
                INNER JOIN permiso_rol AS pr ON pa.idpermiso_arbol = pr.idpermiso_arbol
                INNER JOIN rol AS r ON pr.idrol = r.idrol
                WHERE r.idrol = {$rol}
                AND pa.ruta = '{$ruta}'";
        $result = $this->db->query($result);
        return $result->row();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar permisos menu filtrada por el id del rol y menu 
     * @param  integer  $rol        Identificador del rol
     * @param  integer  $menu_id    Identificador del menú
     * @return Mixed           información de las rol o falso
     */
    public function has_permission_menu( $rol, $menu_id ) {
        $result = "SELECT * FROM permiso_menu
                WHERE idrol = {$rol}
                AND idmenu = {$menu_id}";
        $result = $this->db->query($result);
        return $result->row();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar permisos menu filtrada por el id del rol y padre
     * @param  integer $rol         Identificador del rol
     * @param  integer  $padre      Identificador del padre
     * @return Mixed           información de las rol o falso
     */
    public function has_permission_menu_by_parent( $rol, $padre ) {
        $result = "SELECT * FROM menu AS m 
                        INNER JOIN permiso_menu AS pm ON pm.idmenu = m.idmenu
                        INNER JOIN rol AS r ON r.idrol = pm.idrol
                        WHERE m.padre = {$padre}
                        AND r.idrol = {$rol} ";
        $result = $this->db->query($result);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar permisos menu filtrada por el id del rol y padre
     * @param  integer $rol         Identificador del rol
     * @param  integer  $padre      Identificador del padre
     * @return Mixed           información de las rol o falso
     */
    public function has_permission_arbol_by_parent( $rol, $padre ) {
        $result = "SELECT * FROM permiso_arbol AS pa 
                        INNER JOIN permiso_rol AS pr ON pa.id = pr.fk_permiso_arbol_id
                        INNER JOIN rol AS r ON pr.idrol = r.id
                        WHERE r.id = {$rol}
                        AND pa.padre = '{$padre}'";
        $result = $this->db->query($result);
        return $result->result();
    }

    public function consultar_formulario_activo_by_usuario( $idusuario ) {

        $consulta = 'SELECT f.* FROM formulario AS f
                        INNER JOIN formulario_participante AS fp ON fp.idformulario = f.idformulario
                        WHERE (f.fecha_inicio <= "' . date("Y-m-d"). '" AND f.fecha_final >= "' . date("Y-m-d"). '")
                        AND f.estado IN(1, 2)
                        AND fp.estado = 0
                        AND fp.idusuario = ' . $idusuario;

        $query = $this->db->query($consulta);            
        return ($query->num_rows()>0) ? $query->row() : false;
    }
}