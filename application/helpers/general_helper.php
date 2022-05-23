<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * FORMATO FECHA
 *
 * FORMATEA UNA FECHA NUMERAL A FORMATO TEXTUAL
 *
 * @param	mixed	La fecha	 
 * @return	string 	Si se especifica $merge = TRUE
 * @return	array 	Si no se especifica $merge o $merge = FALSE
 */

if(! function_exists('formato_fecha'))
{
	function formato_fecha($date, $merge = FALSE, $personalizada  = FALSE){
		$date = explode('-', $date);

		$year = $date[0];
		$nmonth = $date[1];
		$month = $date[1];
		$short = "";
		$day = $date[2];

		if($month == '01'){$month = "Enero";	$short = "Ene";}
		if($month == '02'){$month = "Febrero";	$short = "Feb";}
		if($month == '03'){$month = "Marzo";	$short = "Mar";}
		if($month == '04'){$month = "Abril";	$short = "Abr";}
		if($month == '05'){$month = "Mayo";		$short = "May";}
		if($month == '06'){$month = "Junio";	$short = "Jun";}
		if($month == '07'){$month = "Julio";	$short = "Jul";}
		if($month == '08'){$month = "Agosto";	$short = "Ago";}
		if($month == '09'){$month = "Septiembre";$short = "Sep";}
		if($month == '10'){$month = "Octubre";	$short = "Oct";}
		if($month == '11'){$month = "Noviembre";$short = "Nov";}
		if($month == '12'){$month = "Diciembre";$short = "Dic";}

		if($merge)return "{$month} {$day} de {$year}";
		if($personalizada)return "{$month} {$day} de {$year}";
		return array('ano'=>$year,'nmes'=>$nmonth,'mes'=>$month,'smes'=>$short,'dia'=>$day);
	}
}

// ------------------------------------------------------------------------


/**
 * formato_timestamp
 *
 * FORMATEA UNA FECHA NUMERAL A FORMATO TEXTUAL
 *
 * @param	mixed	La fecha
 * @param	bolean	Si la fecha se devuelve como un string legible o un array
 * @param	string	El formato de la fecha, por defecto el timestamp
 * @return	string 	Si se especifica $merge = TRUE
 * @return	array 	Si no se especifica $merge o $merge = FALSE
 */


if( ! function_exists('formato_timestamp') )
{
	function formato_timestamp($date, $merge = FALSE, $format = 'j-M-y h.i.s.u A'){

		$date = DateTime::createFromFormat($format,$date);
		$hour = $date->format('H:i:s');
		$date = $date->format('Y-m-d');
		if( ! $hour )$hour = "";

		$date = explode('-', $date);

		$year = $date[0];
		$nmonth = $date[1];
		$month = $date[1];
		$short = "";
		$day = $date[2];


		if($month == '01'){$month = "Enero";	$short = "Ene";}
		if($month == '02'){$month = "Febrero";	$short = "Feb";}
		if($month == '03'){$month = "Marzo";	$short = "Mar";}
		if($month == '04'){$month = "Abril";	$short = "Abr";}
		if($month == '05'){$month = "Mayo";		$short = "May";}
		if($month == '06'){$month = "Junio";	$short = "Jun";}
		if($month == '07'){$month = "Julio";	$short = "Jul";}
		if($month == '08'){$month = "Agosto";	$short = "Ago";}
		if($month == '09'){$month = "Septiembre";$short = "Sep";}
		if($month == '10'){$month = "Octubre";	$short = "Oct";}
		if($month == '11'){$month = "Noviembre";$short = "Nov";}
		if($month == '12'){$month = "Diciembre";$short = "Dic";}

		$data = array(
			'ano'=>$year,
			'nmes'=>$nmonth,
			'mes'=>$month,
			'smes'=>$short,
			'dia'=>$day
		);

		if( $hour )$data["hora"] = $hour;

		if( $merge )return $day." de ".$month." de ".$year." ".$hour;
		return $data;
	}
}

// ------------------------------------------------------------------------


/**
 * validar_fecha
 *
 * Valida que el parámetro sea una fecha
 *
 * @param	mixed		La fecha
 * @param	string 		Si se especifica $format el formato para validar la fecha
 * @return	boolean 	TRUE si es válida, FALSE en caso contrario
 */

if( ! function_exists('validar_fecha') ){

	function validar_fecha($date, $format = 'Y-m-d'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
}
	
// ------------------------------------------------------------------------


/**
 * hash_equals
 *
 * Fallback para la función hash_equals
 *
 * @param	string		Hash conocido (db)
 * @param	string 		Password hash proporcionado por el usuario
 * @return	boolean 	TRUE si es válida, FALSE en caso contrario
 */

if( ! function_exists('hash_equals') )
{
	function hash_equals( $str1 , $str2 ) 
	{
		if( strlen( $str1 ) != strlen( $str2 ) )
		{
		  	return false;
		}
		else 
		{
			$res = $str1 ^ $str2;
			$ret = 0;
			for( $i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord( $res[$i] );
			return ! $ret;
		}
	}
}

// ------------------------------------------------------------------------


/**
 * sha256_crypt
 *
 * Fallback para la función hash_equals
 *
 * @param	string		Hash conocido (db)
 * @param	string 		Password hash proporcionado por el usuario
 * @return	boolean 	TRUE si es válida, FALSE en caso contrario
 */

if( ! function_exists('sha256_crypt') )
{
	function sha256_crypt( $string , $salt ) 
	{
		$pre = '$5$rounds=5000$';

		$hash = crypt( $string , $pre . $salt . '$' );

		return str_replace( $pre , '' , $hash );		
	}
}

// ------------------------------------------------------------------------


/**
 * secure_ajax_response
 *
 * returns a JSON response of the array values appending a csrf token generation
 *
 * @param	array	Hash conocido (db)
 * @return	JSON 	TRUE si es válida, FALSE en caso contrario
 */

if( ! function_exists('secure_ajax_response') )
{
	function secure_ajax_response( $response = array() )
	{
		$ci =& get_instance();
		if( ! empty($response) && is_array($response) ){
			$response["csrf_token"] = $ci->security->get_csrf_hash();
		}
		return json_encode($response);
	}
}


// ------------------------------------------------------------------------


/**
 * Is JSON
 *
 * verify if a variable is JSON type
 *
 * @param	array	Hash conocido (db)
 * @return	JSON 	TRUE si es válida, FALSE en caso contrario
 */

if( ! function_exists('is_json') )
{
	function is_json( $var = "" )
	{
		if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
		    return is_string($var) && is_array(json_decode($var, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
		}else{
			return is_string($var) && is_array(json_decode($var, true)) ? true : false;	
		}
		
	}
}

/**
* Calcular la fecha
* @param  date $fecha captura la fecha
* @return mixed 
*/
if( ! function_exists('calcular_fecha') ){

	function calcular_fecha($fecha,$accion=false){
		$fecha = substr($fecha, 0, 10);
		$numeroDia = date('d', strtotime($fecha));
		$dia = date('l', strtotime($fecha));
		$mes = date('F', strtotime($fecha));
		$anio = date('Y', strtotime($fecha));
		$dias_ES = array("LUNES", "MARTES", "MIÉRCOLES", "JUEVES", "VIERNES", "SÁBADO", "DOMINGO");
		$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		$nombredia = str_replace($dias_EN, $dias_ES, $dia);
		$meses_ES = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
		$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
		$nombre_fecha = $nombredia."-".$numeroDia."-".substr($nombreMes, 0,3);

		if(empty($accion)){
			return $nombre_fecha;
		}else{
			echo json_encode($nombre_fecha);
		}
	}
}

/**
* Calcular la fecha
* @param  date $fecha captura la fecha
* @return mixed 
*/
if( ! function_exists('set_fecha') ){

	function set_fecha(){
		return date("Y-m-d H:i:s");
	}
}

/**
* Reemplazar caracteres especiales en una cadena
* @param  string $value valor a reemplazar
* @return mixed 
*/
if( ! function_exists('scanear_string') ){

	function scanear_string($string){
		$string = trim($string);
	 
	    $string = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );

	    $string = str_replace(" ", "-", $string);
	    return $string;
	}
}

if( ! function_exists('generar_url') ){

	function generar_url($cadena) {
		$separador = '-';//ejemplo utilizado con guión medio
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';

		//Quitamos todos los posibles acentos
		$url = strtr(utf8_decode($cadena), utf8_decode($originales), $modificadas);

		//Convertimos la cadena a minusculas
		$url = utf8_encode(strtolower($url));

		//Quitamos los saltos de linea y cuanquier caracter especial
		$buscar = array(' ', '&amp;', '\r\n', '\n', '+');
		$url = str_replace ($buscar, $separador, $url);
		$buscar = array('/[^a-z0-9\-&lt;&gt;]/', '/[\-]+/', '/&lt;[^&gt;]*&gt;/');
		$reemplazar = array('', $separador, '');
		$url = preg_replace ($buscar, $reemplazar, $url);
		return $url;
	}


}

if( ! function_exists('printTime') ){

	function printTime($start, $end){
        $start_date = new DateTime($start);
        $since_start = $start_date->diff(new DateTime($end));
     
        if($since_start->y==0){
            if($since_start->m==0){
                if($since_start->d==0){
                   if($since_start->h==0){
                       if($since_start->i==0){
                          if($since_start->s==0){
                             return $since_start->s.' segundos';
                          }else{
                              if($since_start->s==1){
                                 return $since_start->s.' segundo'; 
                              }else{
                                 return $since_start->s.' segundos'; 
                              }
                          }
                       }else{
                          if($since_start->i==1){
                              return $since_start->i.' minuto'; 
                          }else{
                            return $since_start->i.' minutos';
                          }
                       }
                   }else{
                      if($since_start->h==1){
                        return $since_start->h.' hora';
                      }else{
                        return $since_start->h.' horas';
                      }
                   }
                }else{
                    if($since_start->d==1){
                        return $since_start->d.' día';
                    }else{
                        return $since_start->d.' días';
                    }
                }
            }else{
                if($since_start->m==1){
                   return $since_start->m.' mes';
                }else{
                    return $since_start->m.' meses';
                }
            }
        }else{
            if($since_start->y==1){
                return $since_start->y.' año';
            }else{
                return $since_start->y.' años';
            }
        }
	}
	
}