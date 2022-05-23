<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 *
 * Send messages using SMTP
 *
 */
$config['SMTP'] = true;

/**
 *
 * SMTP class debug output mode
 * 0 No output
 * 1 Commands
 * 2 Data and commands
 * 3 As 2 plus connection status
 * 4 Low-level data output
 *
 */
$config['SMTPDebug'] = 0;

/**
 *
 * Whether to use SMTP authentication
 * If enabled need to set Username and Password 
 *
 */
$config['SMTPAuth'] = true;

/**
 *
 * What kind of encryption to use on the SMTP connection
 * Options: '', 'ssl' or 'tls'
 *
 */
$config['SMTPSecure'] = 'ssl';

/**
 *
 * STMP host
 *
 */
$config['host'] = 'smtp.gmail.com';

/**
 *
 * The default SMTP server port
 *
 */
$config['port'] = 465;

/**
 *
 * SMTP username
 *
 */
$config['username'] = 'soporteproyeco2505@gmail.com';


/**
 *
 * STMP password
 *
 */
$config['password'] = "951753Proyecto";



/**
 *
 * Sets if message is html or plain text 
 *
 */
$config['isHTML'] = true;

/**
 *
 * SMTP nombre de usuario
 *
 */
$config['fromname'] = 'Proyecto';
