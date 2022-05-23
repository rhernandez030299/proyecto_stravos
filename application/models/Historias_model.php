<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Historias_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }


    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla historias
     * @param  array() $data   
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar( $data ){
        $insert = $this->db->insert('historia', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Modificación de datos en la tabla historias
     * @param  integer $id     identificador de la fila a modificar
     * @param  array() $data   
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function modificar($id,$data){
        $this->db->where('idhistoria', $id);
        return $this->db->update('historia', $data);
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar historias filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del historias o falso
     */
    public function consultar_by_id($id){
        $result = $this->db->get_where('historia', array('idhistoria' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }

    //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla usuario
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar($id){
        return $this->db->delete('historia', array('idhistoria' => $id));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar historia dependiendo la data enviada     
     * @param  array() $data   almacena los campos de la tabla grupo table
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_by_data($data){
        $this->db->order_by("posicion", "asc");
        $result = $this->db->get_where('historia', $data);
        return $result->result();
    }

     //-----------------------------------------------------------------------------
    /**
     * Eliminacion de datos en la tabla miembro
     * @param  integer $id     identificador de la fila a eliminar
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function eliminar_archivo_historia($ruta){
        return $this->db->delete('archivo_historia', array('ruta' => $ruta));           
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar prioridad filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del prioridad o falso
     */
    public function consultar_by_prioridad_by_id($id){
        $result = $this->db->get_where('prioridad', array('idprioridad' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar prioridad filtrada por el identificador de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del prioridad o falso
     */
    public function consultar_riesgo_desarrollo_by_id($id){
        $result = $this->db->get_where('riesgo_desarrollo', array('idriesgo_desarrollo' => $id), 1);
        return ($result->num_rows()>0) ? $result->row() : false;
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar prioridad filtrada por la data de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del prioridad o falso
     */
    public function consultar_by_prioridad_by_data($data){
        $result = $this->db->get_where('prioridad', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Consultar prioridad filtrada por la data de la tabla
     * @param  integer $id     identificador de la fila para consultar
     * @return Mixed           información del prioridad o falso
     */
    public function consultar_riesgo_desarrollo_by_data($data){
        $result = $this->db->get_where('riesgo_desarrollo', $data);
        return $result->result();
    }

    //-----------------------------------------------------------------------------
    /**
     * Inserción de datos en la tabla archivos historia
     * @param  array() $data   almacena el campo usuario, contraseña, rol y datos_id
     * @return boolean         retorna verdadero si es exitoso y falso si no lo es
     */
    public function ingresar_archivo( $data ){
        $insert = $this->db->insert('archivo_historia', $data);
        return ( ! empty($insert) ) ? $this->db->insert_id() : FALSE;
    }

     //-----------------------------------------------------------------------------
    /**
     * Consultar historia modelo dependiendo la data enviada     
     * @param  array() $data   
     * @return boolean         retorna Información del permiso arbol o falso
     */
    public function consultar_archivo_historia_by_data($data){
        $result = $this->db->get_where('archivo_historia', $data);
        return $result->result();
    }

    public function consultar_historia_pendiente_incompleta($in_estado, $idmodulo){
        $this->db->where_in('estado', $in_estado);
        $this->db->where('idmodulo', $idmodulo);
        $this->db->from('historia');
        $result = $this->db->get();
        return $result->result();
    }

    public function consultar_notificaciones(){

        $query = 'SELECT v.idvista, p.nombre, h.idhistoria, h.titulo, h.descripcion, h.idusuario, h.updated_at, u.nombre as u_nombre, u.apellido as u_apellido, h.observaciones, p.url as url_proyecto, m.url as url_metodologia, f.url as url_fase, mo.url as url_modulo
                from vista as v
                INNER JOIN historia AS h ON h.idhistoria = v.idhistoria
                INNER JOIN usuario AS u ON u.idusuario = v.idusuario
                INNER JOIN modulo AS mo on h.idmodulo = mo.idmodulo
                INNER JOIN fase AS f ON mo.idfase = f.idfase
                INNER JOIN proyecto_metodologia AS pm ON f.idproyecto_metodologia = pm.idproyecto_metodologia
                INNER JOIN metodologia AS m ON m.idmetodologia = pm.idmetodologia
                INNER JOIN proyecto AS p ON p.idproyecto = pm.idproyecto ';
        
        $query .= "WHERE v.idusuario = " . $this->session->UID . " ";
        
        $condicion = 'AND ';
        $query .= $condicion . ' v.estado = 0 ';
        $query .= $condicion . ' p.fecha_finalizacion >=  "' . date('Y-m-d') . '"';
        $query .= ' GROUP BY v.idhistoria';
        $query .= ' ORDER BY v.created_at asc';

        $result = $this->db->query($query);
        return $result->result();
    }
}