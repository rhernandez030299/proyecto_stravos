/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
*/
var $SHOW_DEBUG_BACKTRACE = true;


/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
*/
var $FILE_READ_MODE  = 0644;
var $FILE_WRITE_MODE = 0666;
var $DIR_READ_MODE   = 0755;
var $DIR_WRITE_MODE  = 0755;


/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
*/
var $FOPEN_READ                           = 'rb';
var $FOPEN_READ_WRITE                     = 'r+b';
var $FOPEN_WRITE_CREATE_DESTRUCTIVE       = 'wb';
var $FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE = 'w+b';
var $FOPEN_WRITE_CREATE                   = 'ab';
var $FOPEN_READ_WRITE_CREATE              = 'a+b';
var $FOPEN_WRITE_CREATE_STRICT            = 'xb';
var $FOPEN_READ_WRITE_CREATE_STRICT       = 'x+b';


/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
*/
var $EXIT_ERROR          = 0; // error
var $EXIT_SUCCESS        = 1; // no errors
var $EXIT_CONFIG         = 3; // configuration error
var $EXIT_UNKNOWN_FILE   = 4; // file not found
var $EXIT_UNKNOWN_CLASS  = 5; // unknown class
var $EXIT_UNKNOWN_METHOD = 6; // unknown class member
var $EXIT_USER_INPUT     = 7; // invalid user input
var $EXIT_DATABASE       = 8; // database error
var $EXIT__AUTO_MIN      = 9; // lowest automatically-assigned error code
var $EXIT__AUTO_MAX      = 125; // highest automatically-assigned error code


/*
|--------------------------------------------------------------------------
| Custom Constants
|--------------------------------------------------------------------------
|
*/

/*
|--------------------------------------------------------------------------
| Estados Usuario
|--------------------------------------------------------------------------
|
*/
var $USUARIO_ACTIVO 	= 1;
var $USUARIO_INACTIVO	= 0;
