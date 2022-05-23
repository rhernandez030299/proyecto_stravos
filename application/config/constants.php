<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 0); // error
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 1); // no errors
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Estados Usuario
|--------------------------------------------------------------------------
|
| User rol constants
|
*/

defined('USUARIO_ACTIVO')        	OR define('USUARIO_ACTIVO', 1);
defined('USUARIO_INACTIVO')	     	OR define('USUARIO_INACTIVO', 0);

/*
|--------------------------------------------------------------------------
| Roles
|--------------------------------------------------------------------------
|
| User rol constants
|
*/
defined('ROL_ADMIN')        		OR define('ROL_ADMIN', 1); // Application Name
defined('ROL_PROFESOR')        		OR define('ROL_PROFESOR', 2); // Application Name
defined('ROL_ESTUDIANTE')        	OR define('ROL_ESTUDIANTE', 3); // Application Name

/*
|--------------------------------------------------------------------------
| Estados Formulas
|--------------------------------------------------------------------------
|
| Formulas constants
|
*/
defined('PERMISOS_ARBOL_PUBLICO')        	OR define('PERMISOS_ARBOL_PUBLICO', 1);
defined('PERMISOS_ARBOL_PRIVADO')	     	OR define('PERMISOS_ARBOL_PRIVADO', 2);
defined('PERMISOS_ARBOL_PROTEGIDO')	     	OR define('PERMISOS_ARBOL_PROTEGIDO', 3);

/*
|--------------------------------------------------------------------------
| Estados Usuario
|--------------------------------------------------------------------------
|
| User rol constants
|
*/

defined('PROYECTO_ACTIVO')        		OR define('PROYECTO_ACTIVO', 1);
defined('PROYECTO_INACTIVO')	     	OR define('PROYECTO_INACTIVO', 0);


/*
|--------------------------------------------------------------------------
| Estados historia
|--------------------------------------------------------------------------
|
| User rol constants
|
*/

defined('HISTORIA_PENDIENTE')        	OR define('HISTORIA_PENDIENTE', 0);
defined('HISTORIA_APROBADA')	     	OR define('HISTORIA_APROBADA', 1);
defined('HISTORIA_ENTREGADA')	     	OR define('HISTORIA_ENTREGADA', 2);
defined('HISTORIA_INCOMPLETA')	     	OR define('HISTORIA_INCOMPLETA', 3);
defined('HISTORIA_FINALIZADA')	     	OR define('HISTORIA_FINALIZADA', 4);

/*
|--------------------------------------------------------------------------
| Estados modulos
|--------------------------------------------------------------------------
|
| User rol constants
|
*/

defined('MODULO_CREADO')        	OR define('MODULO_CREADO', 0);
defined('MODULO_PENDIENTE')	     	OR define('MODULO_PENDIENTE', 1);
defined('MODULO_FINALIZADO')	    OR define('MODULO_FINALIZADO', 2);

/*
|--------------------------------------------------------------------------
| Estados formularios
|--------------------------------------------------------------------------
|
| User formulario constants
|
*/

defined('FORMULARIO_ACTIVO')	        OR define('FORMULARIO_ACTIVO', 1);
defined('FORMULARIO_PROGRAMADO')      OR define('FORMULARIO_PROGRAMADO', 2);
defined('FORMULARIO_INACTIVO')	     	OR define('FORMULARIO_INACTIVO', 3);