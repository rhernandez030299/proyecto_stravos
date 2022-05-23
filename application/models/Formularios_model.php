<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Formularios_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

   
   //-----------------------------------------------------------------------------
    /**
     * Consultar los formulario registradas
     * @return array           
     */
    public function consultar_by_filtros(){
        $query = '';
        $output = array();
        $query .= "SELECT f.* FROM formulario f ";
        $condicion = "where ";
        
        if(isset($_POST["search"]["value"]))
        {
            if($_POST["search"]["value"]!=""){
                $query .= $condicion. ' f.nombre LIKE "%'.$_POST["search"]["value"].'%" ';  
                $condicion = "and ";
            }
        }

        if( $this->session->USER_ROL != ROL_ADMIN ){
            $query .= $condicion . " idusuario_creacion = " . $this->session->UID . " ";
        }

        if(isset($_POST["order"]))
        {   
            $order = "f.idformulario";
            if($_POST['order']['0']['column']==0){
                $order = "f.idformulario";
            }
            if($_POST['order']['0']['column']==1){
                $order = "f.nombre";
            }
            $query .= 'ORDER BY '.$order.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $query .= 'ORDER BY f.idformulario DESC ';
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
     * Consultar el conteo de los formulario con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_conteo_by_filtro($busqueda){

        $consulta = "select * from formulario ";
        $condicion = "where ";
        if( ! empty($busqueda)){
            $consulta .= $condicion . " (nombre LIKE '%".$busqueda."%') ";
            $condicion = "and ";
        }

        if( $this->session->USER_ROL != ROL_ADMIN ){
            $consulta .= $condicion . " idusuario_creacion = " . $this->session->UID . " ";
        }

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }
 
    //-----------------------------------------------------------------------------
    /**
     * Consultar formulario filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del usuario o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('formulario', array('idformulario' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }
    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla formulario
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('formulario', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla formulario
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_formulario_participante( $data ){
        $insert = $this->db->insert('formulario_participante', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla formulario
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idformulario', $id);
        return $this->db->update('formulario', $data);
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla formulario
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   almacena el campo usuario, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar_formulario_participante($id,$data){
        $this->db->where('idformulario_participante', $id);
        return $this->db->update('formulario_participante', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar todas los formulario
     * @return Mixed información de los formulario
     */
    public function consultar(){
        
        $this->db->from('formulario');
        $result = $this->db->get();
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar formulario arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla formulario table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $result = $this->db->get_where('formulario', $data);
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar formulario arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla formulario table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_participante_by_idformulario($idformulario){

        $consulta = "SELECT u.*, ( 
                        SELECT fp.idformulario FROM formulario_participante AS fp 
                        WHERE fp.idusuario = u.idusuario 
                        AND fp.idformulario = ".$idformulario."
                        LIMIT 1
                    ) AS formulario
                    FROM usuario AS u
                    WHERE u.estado = " . USUARIO_ACTIVO . " ";

        if( $this->session->USER_ROL == ROL_PROFESOR ){
            $consulta .= "AND u.idrol = " . ROL_ESTUDIANTE . " ";
        }else if( $this->session->USER_ROL == ROL_ADMIN ){
            $consulta .= "AND u.idrol IN(" . ROL_PROFESOR .",".ROL_ESTUDIANTE.")";
        }

        $query = $this->db->query($consulta);            
        return $query->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar formulario arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla formulario table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data_formulario_participante($data){
        $result = $this->db->get_where('formulario_participante', $data);
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar formulario arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla formulario table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_conteo_by_data_formulario_participante($data){
        $result = $this->db->get_where('formulario_participante', $data);
        return $result->num_rows();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar el conteo de los formulario con o sin filtro.
     * @param  string  $busqueda Almacena la busqueda del usuario
     * @return integer          El número de filas contadas.
     */
    public function consultar_activo($idformulario){

        $consulta = "SELECT f.* FROM formulario AS f
                        INNER JOIN pregunta AS p On p.idformulario = f.idformulario
                        INNER JOIN respuesta AS r ON r.idpregunta = p.idpregunta 
                        WHERE f.idformulario = " . $idformulario;

        $query = $this->db->query($consulta);            
        return $query->num_rows();
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla formulario
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($idformulario){
        return $this->db->delete('formulario', array('idformulario' => $idformulario));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla formulario
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_formulario_participante($idformulario, $idusuario){
        return $this->db->delete('formulario_participante', array('idformulario' => $idformulario, "idusuario" => $idusuario));           
    }
    

    //-----------------------------------------------------------------------------
    /**
     * Consultar formulario arbol dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla formulario table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_usuario_participante_by_idformulario($idformulario, $estado){

        $consulta = "SELECT u.*, fp.idformulario_participante as idparticipante
                    FROM usuario AS u
                    INNER JOIN formulario_participante AS fp ON u.idusuario = fp.idusuario
                    WHERE fp.idformulario = " . $idformulario . " 
                    AND fp.estado = " . $estado . " ";

        $query = $this->db->query($consulta);            
        return $query->result();
    }

    
}