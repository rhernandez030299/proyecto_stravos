<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar los proyecto registrados
     * @return array           con el resultado de los proyecto
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "select p.* from proyecto AS p INNER JOIN miembro_profesor AS mp ON mp.idproyecto = p.idproyecto ";

        $condicion = "WHERE ";
        if($this->session->USER_ROL != ROL_ADMIN){
            
            $query .= $condicion . " mp.idusuario = " . $this->session->UID . " ";
            $condicion = "AND ";
        }

        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= $condicion . ' (url LIKE "%'.$_POST["search"]["value"].'%" ';
                $query .= 'or nombre LIKE "%'.$_POST["search"]["value"].'%") ';
            }
        }

        $query .= "GROUP BY p.idproyecto ";

        if(isset($_POST["order"]))
        {   
            $order = "idproyecto";
            
            if($_POST['order']['0']['column']==0){
                $order = "url";
            }
            if($_POST['order']['0']['column']==1){
                $order = "nombre";
            }
            if($_POST['order']['0']['column']==2){
                $order = "porcentaje_cumplimiento";
            }
            if($_POST['order']['0']['column']==3){
                $order = "fecha_inicio";
            }
            if($_POST['order']['0']['column']==4){
                $order = "fecha_finalizacion";
            }

            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY idproyecto ASC ';
        }

        if(isset($_POST["length"])){
            if($_POST["length"] != -1)
            {
                $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'] . " ";
            }
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

        $consulta = "select p.* from proyecto AS p INNER JOIN miembro_profesor AS mp ON mp.idproyecto = p.idproyecto ";

        $condicion = "WHERE ";
        if($this->session->USER_ROL != ROL_ADMIN){
            
            $consulta .= $condicion . " mp.idusuario = " . $this->session->UID . " ";
            $condicion = "AND ";
        }

        if( ! empty($busqueda)){
            $consulta .= $condicion . " (nombre LIKE '%".$busqueda."%' or url LIKE '%".$busqueda."%') ";
        }

        $consulta .= "GROUP BY p.idproyecto ";

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
        $result = $this->db->get_where('proyecto', array('idproyecto' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('proyecto', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_proyecto_metodologia( $data ){
        $insert = $this->db->insert('proyecto_metodologia', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_miembro_profesor( $data ){
        $insert = $this->db->insert('miembro_profesor', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_miembro( $data ){
        $insert = $this->db->insert('miembro', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla archivos proyecto
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_archivo( $data ){
        $insert = $this->db->insert('archivo_proyecto', $data);
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
        $this->db->where('idproyecto', $id);
        return $this->db->update('proyecto', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los proyecto
     * @return Mixed información de los proyecto
     */
    public function consultar(){
        $this->db->from('proyecto');
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
        $result = $this->db->get_where('proyecto', $data);
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_proyecto_metodologia_by_data($data){
        $result = $this->db->get_where('proyecto_metodologia', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_miembro_profesor_by_data($data){
        $result = $this->db->get_where('miembro_profesor', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_miembro_profesor_by_historia($idhistoria, $idusario){

        $query = 'SELECT * FROM historia AS h
            INNER JOIN modulo AS m on m.idmodulo = h.idmodulo
            INNER JOIN fase AS f on f.idfase = m.idfase
            INNER JOIN proyecto_metodologia AS pm on pm.idproyecto_metodologia = f.idproyecto_metodologia
            INNER JOIN miembro_profesor AS mp on mp.idproyecto = p.idproyecto
            WHERE h.idhistoria = ' . $idhistoria . ' 
            AND mp.idusuario = ' . $idusario;

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_miembro_by_data($data){
        $result = $this->db->get_where('miembro', $data);
        return $result->result();
    }

    public function consultar_miembro_by_historia($idhistoria, $idusario){

        $query = 'SELECT * FROM historia AS h
            INNER JOIN modulo AS m on m.idmodulo = h.idmodulo
            INNER JOIN fase AS f on f.idfase = m.idfase
            INNER JOIN proyecto_metodologia AS pm on pm.idproyecto_metodologia = f.idproyecto_metodologia
            INNER JOIN miembro AS mi on mi.idproyecto = pm.idproyecto
            WHERE h.idhistoria = ' . $idhistoria . ' 
            AND mi.idusuario = ' . $idusario;

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar proyecto arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla proyecto table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_archivo_proyecto_by_data($data){
        $result = $this->db->get_where('archivo_proyecto', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla proyecto
     * @param  integer $idproyecto     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($idproyecto){
        return $this->db->delete('proyecto' , array('idproyecto' => $idproyecto));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla proyecto metodologia
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_proyecto_metodologia($id){
        return $this->db->delete('proyecto_metodologia', array('idproyecto' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla miembro
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_miembro($id){
        return $this->db->delete('miembro', array('idproyecto' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla miembro profesor
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_miembro_profesor($id, $idusuario){
        return $this->db->delete('miembro_profesor', array('idproyecto' => $id, "idusuario !=" => $idusuario ,"usuario_creacion" => 0));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla miembro
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_archivo_proyecto($ruta, $id){
        return $this->db->delete('archivo_proyecto', array('ruta' => $ruta, 'idproyecto' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla miembro
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_miembro_in($in, $id){
        $this->db->where_not_in('idusuario', $in);
        return $this->db->delete('miembro', array('idproyecto' => $id));           
    }

    public function consultar_proyecto_con_miembro($start, $length, $search = false,  $estado = true){

        $query = "";
        $condicion = "AND ";
        if($this->session->USER_ROL == ROL_ESTUDIANTE){
            $query .= "SELECT p.* FROM proyecto AS p 
                            INNER JOIN miembro AS mp ON mp.idproyecto = p.idproyecto 
                            WHERE (mp.idusuario = " . $this->session->UID . " or p.proyecto_base = 1)";
        }

        if($this->session->USER_ROL == ROL_PROFESOR){
            $query .= "SELECT p.* FROM proyecto AS p 
                            INNER JOIN miembro_profesor AS mp ON mp.idproyecto = p.idproyecto
                            WHERE mp.idusuario = " . $this->session->UID. " ";
        }

        if($this->session->USER_ROL == ROL_ADMIN){
            $query .= "SELECT p.* FROM proyecto AS p 
                            INNER JOIN miembro_profesor AS mp ON mp.idproyecto = p.idproyecto ";
            $condicion = "WHERE ";
        }

        if(!empty($search)){
            $query .= $condicion . ' (p.nombre LIKE "%'.$search.'%" ';
            $query .= 'or p.subtitulo LIKE "%'.$search.'%" ';
            $query .= 'or p.descripcion LIKE "%'.$search.'%") ';
            $condicion = "AND ";
        }

        if( $estado == "true" ) {
            $query .= $condicion . ' (p.fecha_finalizacion >= "'.date('Y-m-d').'" or p.proyecto_base = 1) ';
            $condicion = "AND ";
        }

        if( $estado == "false" ) {
            $query .= $condicion . ' p.fecha_finalizacion < "'.date('Y-m-d').'" ';
            $condicion = "AND ";
        }
        
        $query .= "GROUP BY p.idproyecto ";
        $query .= "ORDER BY p.proyecto_base DESC ";

        if(!empty($length)){
            $query .= 'LIMIT ' . $start . ', ' . $length . " ";
        }

        $result = $this->db->query($query);
        return $result->result();
    }

    
    public function consultar_conteo_proyecto_con_miembro($search = false, $estado = true){

        $query = "";
        $condicion = "AND ";
        if($this->session->USER_ROL == ROL_ESTUDIANTE){
            $query .= "SELECT count(*) FROM proyecto AS p 
                            INNER JOIN miembro AS mp ON mp.idproyecto = p.idproyecto 
                            WHERE mp.idusuario = " . $this->session->UID . " ";       
        }

        if($this->session->USER_ROL == ROL_PROFESOR){
            $query .= "SELECT count(*) FROM proyecto AS p 
                            INNER JOIN miembro_profesor AS mp ON mp.idproyecto = p.idproyecto
                            WHERE mp.idusuario = " . $this->session->UID. " ";
        }

        if($this->session->USER_ROL == ROL_ADMIN){
            $query .= "SELECT count(*) FROM proyecto AS p 
                            INNER JOIN miembro_profesor AS mp ON mp.idproyecto = p.idproyecto ";
            $condicion = "WHERE ";
        }

        if(!empty($search)){
            $query .= $condicion . ' (p.nombre LIKE "%'.$search.'%" ';
            $query .= 'or p.subtitulo LIKE "%'.$search.'%" ';
            $query .= 'or p.descripcion LIKE "%'.$search.'%") ';
            $condicion = "AND ";
        }

        if( $estado == "true" ) {
            $query .= $condicion . ' p.fecha_finalizacion >= "'.date('Y-m-d').'" ';
            $condicion = "AND ";
        }

        if( $estado == "false" ) {
            $query .= $condicion . ' p.fecha_finalizacion < "'.date('Y-m-d').'" ';
            $condicion = "AND ";
        }

        $query .= "GROUP BY p.idproyecto ";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function consultar_proyecto_metodologia_by_id_proyecto($idproyecto) {

        $query = "SELECT m.* FROM proyecto_metodologia AS pm INNER JOIN metodologia AS m ON pm.idmetodologia = m.idmetodologia ";
        if( ! empty($idproyecto)) {
            $query .= "WHERE pm.idproyecto = " . $idproyecto;
        }
        $result = $this->db->query($query);
        return $result->result();
    }

    public function consultar_miembro_by_id_proyecto($idproyecto) {

        $query = "SELECT m.*, u.nombre, u.apellido, u.correo, u.ruta_imagen FROM miembro AS m INNER JOIN usuario AS u ON m.idusuario = u.idusuario ";
        if( ! empty($idproyecto)) {
            $query .= "WHERE m.idproyecto = " . $idproyecto;
        }
        $result = $this->db->query($query);
        return $result->result();
    }

    public function consultar_miembro_profesor_by_id_proyecto($idproyecto) {

        $query = "SELECT m.*, u.nombre, u.apellido, u.correo, u.ruta_imagen FROM miembro_profesor AS m INNER JOIN usuario AS u ON m.idusuario = u.idusuario ";
        if( ! empty($idproyecto)) {
            $query .= "WHERE m.idproyecto = " . $idproyecto;
        }
        $result = $this->db->query($query);
        return $result->result();
    }

    public function consultar_historias_by_proyecto($idestado, $idproyecto){

        $query = 'SELECT count(*) AS contador_historias
                        FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto
                        INNER JOIN modulo AS mo on mo.idfase = f.idfase
                        INNER JOIN historia AS h ON h.idmodulo = mo.idmodulo ';
        
        if( ! empty($idproyecto)) {
            $query .= 'WHERE p.idproyecto = ' . $idproyecto . ' ';
        }

        if( ! empty($idestado)) {
            $query .= 'AND h.estado = "' . $idestado . '" ';
        }

        $result = $this->db->query($query);
        return $result->row();
    }

    public function consultar_modulos_by_proyecto($idestado, $idproyecto){

        $query = "SELECT count(*) AS contador_modulos
                        FROM proyecto AS p
                        INNER JOIN proyecto_metodologia AS pm ON p.idproyecto = pm.idproyecto
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia  
                        INNER JOIN modulo AS m ON m.idfase = f.idfase ";
        if( ! empty($idproyecto)) {
            $query .=   'WHERE p.idproyecto =  ' . $idproyecto;
        }

        if( ! empty($idestado)) {
            $query .=  ' AND m.estado = "' . $idestado . '" ';
        }
     

        $result = $this->db->query($query);
        return $result->row();
    }

    public function consultar_miembro_profesor_id_modulo($idmodulo){

        $query = 'SELECT u.*
                        FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto
                        INNER JOIN modulo AS mo on mo.idfase = f.idfase
                        INNER JOIN miembro_profesor mp ON mp.idproyecto = p.idproyecto
                        INNER JOIN usuario AS u ON u.idusuario = mp.idusuario
                        WHERE usuario_creacion = 1 ';
        
        if( ! empty($idmodulo)) {
            $query .= 'AND mo.idmodulo = "' . $idmodulo . '" ';
        }

        $result = $this->db->query($query);
        return $result->row();
    }


    public function consultar_todo_historias_by_proyecto($idestado, $idproyecto, $idusuario, $idfase = false){

        $query = 'SELECT count(*) AS contador_historia
                        FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto
                        INNER JOIN modulo AS mo on mo.idfase = f.idfase
                        INNER JOIN historia AS h ON h.idmodulo = mo.idmodulo ';
        
        if( ! empty($idproyecto)) {
            $query .= 'WHERE p.idproyecto = ' . $idproyecto . ' ';
        }

        if( ! empty($idfase) && is_numeric($idfase) ) {
            $query .= 'AND f.idfase = ' . $idfase . ' ';
        }
       
        $query .= 'AND h.estado = ' . $idestado . ' ';
        

         if( ! empty($idusuario)) {
            $query .= 'AND h.idusuario = ' . $idusuario . '';
        }


        $result = $this->db->query($query);
        return $result->row();
    }

    public function consultar_miembro_profesor_id_historia($idhistoria){

        $query = 'SELECT u.*
                        FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto
                        INNER JOIN modulo AS mo on mo.idfase = f.idfase
                        INNER JOIN miembro_profesor mp ON mp.idproyecto = p.idproyecto
                        INNER JOIN historia h ON h.idmodulo = mo.idmodulo
                        INNER JOIN usuario AS u ON u.idusuario = mp.idusuario
                        WHERE usuario_creacion = 1 ';
        
        if( ! empty($idhistoria)) {
            $query .= 'AND h.idhistoria = "' . $idhistoria . '" ';
        }

        if( ! empty($idhistoria)) {
            $query .= 'AND u.idusuario = "' . $this->session->UID . '" ';
        }

        $result = $this->db->query($query);
        return $result->row();
    }

    public function consultar_miembro_id_historia($idhistoria){

        $query = 'SELECT count(*)
                        FROM proyecto_metodologia AS pm 
                        INNER JOIN fase AS f ON f.idproyecto_metodologia = pm.idproyecto_metodologia 
                        INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                        INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto
                        INNER JOIN modulo AS mo on mo.idfase = f.idfase
                        INNER JOIN miembro AS mp ON mp.idproyecto = p.idproyecto 
                        INNER JOIN historia h ON h.idmodulo = mo.idmodulo
                        INNER JOIN usuario AS u ON u.idusuario = mp.idusuario
                        ';
        $condicion = "WHERE ";
        if( ! empty($idhistoria)) {
            $query .= $condicion . ' h.idhistoria = "' . $idhistoria . '" ';
            $condicion = 'AND ';
        }

        if( ! empty($idhistoria)) {
            $query .= $condicion .' u.idusuario = "' . $this->session->UID . '" ';
        }

        $result = $this->db->query($query);
        return $result->row();
    }
}