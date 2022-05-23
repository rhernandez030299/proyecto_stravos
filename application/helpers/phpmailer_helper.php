<?php

// ------------------------------------------------------------------------

/**
 * phpmailer_init
 *
 * Inicia phpmailer con la configuración predeterminada y devuelve el objeto phpmailer
 * 
 * @return  object  El objeto phpmailer
 */
if( ! function_exists('phpmailer_init') ){

    function phpmailer_init( $custom_config = FALSE ){
        set_include_path( get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/phpmailer' );
        include APPPATH . 'third_party/phpmailer/PHPMailerAutoload.php';
        $CI =& get_instance();
        $CI->config->load( 'mailer' , TRUE );
        $config = $CI->config->item('mailer');
        if( ! empty( $custom_config ) )
        {
            $config = array_merge($config, $custom_config);
        }
        $p = new PHPMailer;
        if( ! empty($config) && ! empty( $config['SMTP'] ) )
        {
            $p->isSMTP();               // Set mailer to use SMTP
            $p->SMTPDebug   = $config['SMTPDebug']; // Enable verbose debug output
            $p->SMTPAuth    = $config['SMTPAuth'];  // Enable SMTP authentication
            $p->SMTPSecure  = $config['SMTPSecure'];// Enable TLS encryption, `ssl` also accepted
            $p->Host        = $config['host'];    // Specify main and backup SMTP servers
            $p->Port        = $config['port'];    // TCP port to connect to
            $p->Username    = $config['username'];  // SMTP username
            $p->Password    = $config['password'];  // SMTP password
            $p->FromName    = $config['fromname'];
            
        }
        $p->Encoding = "base64";        
        $p->CharSet = 'UTF-8';
        $p->isHTML( TRUE );
        return $p;
    }

}

// ------------------------------------------------------------------------

/**
 * phpmailer_multiple_init
 *
 * Inicia phpmailer con la configuración predeterminada y devuelve el objeto phpmailer
 * 
 * @return  object  El objeto phpmailer
 */
if( ! function_exists('phpmailer_multiple_init') ){

    function phpmailer_multiple_init( ){
        set_include_path( get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/phpmailer' );
        include APPPATH . 'third_party/phpmailer/PHPMailerAutoload.php';
        $CI =& get_instance();
        $CI->config->load( 'mailer_multiple' , TRUE );
        $config = $CI->config->item('mailer_multiple');        
        $p = new PHPMailer;
        if( ! empty($config) && ! empty( $config['SMTP'] ) )        
        {            
            $p->isSMTP();               // Set mailer to use SMTP
            $p->SMTPDebug   = $config['SMTPDebug']; // Enable verbose debug output
            $p->SMTPAuth    = $config['SMTPAuth'];  // Enable SMTP authentication
            $p->SMTPSecure  = $config['SMTPSecure'];// Enable TLS encryption, `ssl` also accepted
            $p->Host        = $config['host'];    // Specify main and backup SMTP servers
            $p->Port        = $config['port'];    // TCP port to connect to            
            
            $account = $config["accounts"][mt_rand(0, count($config["accounts"]) - 1)];

            $p->Username    = $account['username'];  // SMTP username
            $p->Password    = $account['password'];  // SMTP password
        }
        $p->Encoding = "base64";
        $p->CharSet = 'UTF-8';
        $p->isHTML( TRUE );
        return $p;
    }

}