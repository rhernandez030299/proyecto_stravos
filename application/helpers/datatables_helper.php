<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * datatable_response
 *
 * Returns a datatables formatted response
 *
 * @param	Integer	$draw 
 * @param	Integer	$total 	 Total de campos sin filtros
 * @param	array	$columns Array de campos a mostrar
 * @return	array 	$data 	 Resultado de la consulta
 */
if( ! function_exists('datatable_response') )
{
	function datatable_response( $draw , $total, $columns, $data, $actions = FALSE )
	{
		if( ! empty( $data ) ){
			$fData = array();
			foreach ($data as $row) {
				$fRow = array();
				foreach ($row as $field => $value) {
					if( in_array($field, $columns) ){
						array_push($fRow, $value);
					}
					if( ! empty($actions) ){
						array_push($actions[$field], $actions);
					}
				}
				array_push($fData, $fRow);
			}
		}

		return array( "draw" => $draw, "recordsTotal" => $total, "recordsFiltered" => count($data), "data" => $fData );
	}
}