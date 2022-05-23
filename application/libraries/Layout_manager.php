<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Layout_manager Class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Layout / Template
 * @version     4.0 / Ene - 2018
 *
 */

class Layout_manager{

        /**
         * CI reference var
         *
         * The CI object
         *
         * @var Object
         */
		protected $CI;

        /**
         * VC Version Control
         *
         * Version Control to prevent resource caching
         *
         * @var Object
         */
        protected $_vc;

        /**
         * Config
         *
         * An array of actual configuration
         *
         * @var Array
         */
		protected $_config;

        /**
         * Template View Path
         *
         * Path to the views template folder
         *
         * @var String
         */
		protected $_template_view_path;

        /**
         * Template Errors Path
         *
         * Path to the errors template folder
         *
         * @var String
         */
        protected $_template_errors_path;

        /**
         * Template Layouts Path
         *
         * Path to the template layouts folder
         *
         * @var String
         */
        protected $_template_layouts_path;        

        /**
         * Template Assets Folder Path
         *
         * Path to the resources in template folder
         *
         * @var String
         */
        protected $_template_assets_path;

        /**
         * Template Assets Folder URL
         *
         * URL to the resources in template folder
         *
         * @var String
         */
		protected $_template_assets_url;
		
        /**
         * Public Folder
         *
         * Full path to the public resources folder
         *
         * @var String
         */
        protected $_public_assets_path;

        /**
         * Public URL
         *
         * the URL to the public resources folder
         *
         * @var String
         */
        protected $_public_assets_url;

        /**
         * CSS
         *
         * A collection of CSS items to load
         *
         * @var Array
         */
		protected $_css;

        /**
         * JS
         *
         * A collection of JS items to load
         *
         * @var Array
         */
        protected $_js;

        /**
         * JS Variables
         *
         * A collection of JS variables to parse
         *
         * @var Array
         */
        protected $_jsvars;

        /**
         * Title
         *
         * Title of page in head tag
         *
         * @var object
         */
		protected $_title;

        /**
         * Application Name
         *
         * Name of the application
         *
         * @var object
         */
        protected $_app_name;

        /**
         * Icon
         *
         * Icon of page in head tag
         *
         * @var String
         */
		protected $_icon;
		
        /**
         * Head
         *
         * The built head tag
         *
         * @var String
         */
        protected $_head;

        /**
         * Head Layout
         *
         * Path to the head layout file
         *
         * @var String
         */
        protected $_head_layout;        

        /**
         * Body
         *
         * The built body
         *
         * @var String
         */
        protected $_body;

        /**
         * Body Layout
         *
         * A relative path to the body layout in the template folder
         *
         * @var String
         */
        protected $_body_layout;

        /**
         * Scripts
         *
         * The built scripts
         *
         * @var String
         */
        protected $_scripts;

        /**
         * Scripts Layout
         *
         * A relative path to the scripts layout in the template folder
         *
         * @var String
         */
        protected $_scripts_layout;

        /**
         * Header
         *
         * A relative path to the header loaded in body
         *
         * @var String
         */
        protected $_header_layout;

        /**
         * Header
         *
         * A relative path to the header loaded in body
         *
         * @var String
         */
        protected $_aside_layout;

        /**
         * Header
         *
         * A relative path to the header loaded in body
         *
         * @var String
         */
        protected $_panel_layout;

        /**
         * Footer
         *
         * A relative path to the footer loaded in body
         *
         * @var object
         */
        protected $_footer_layout;

        /**
         * Page
         *
         * A relative path to the page to load
         *
         * @var String
         */
        protected $_page;

		// --------------------------------------------------------------------

		/**
		 * Class constructor
		 * 
		 * Loads Layout_manager config defaults and initializes class components
		 *
         * @param   array $config Initialization parameters
		 * @return  void
		 */
		public function __construct( $config = array() )
        {
            defined('DS')   OR define('DS', DIRECTORY_SEPARATOR);
            //creates de codeignater object instance
        	$this->CI =& get_instance();
            //sets the default configuration and loads custom if parameter is set
            $this->set_defaults( $config );
        }

        // --------------------------------------------------------------------

        /**
         * Set Defaults
         * 
         * Resets config to default
         *         
         * @return  void
         */
        public function set_defaults( $config = array() )
        {
            //loads the layout manager config file
            $this->CI->config->load('layout_manager',TRUE);            
            $this->_config = $this->CI->config->item( 'layout_manager' );

            if ( ! empty( $config ) && is_array( $config ) )
            {
                $this->_config = array_merge( $this->_config , $config );
            }

            if ( empty( $this->_config ) || ! is_array( $this->_config ) )
            {
                log_message('error', 'Configuración no definida.');
                return show_error( 'Error al cargar la configuración del layout, verifique que el archivo de configuración y/o parámetros sean correctos', 0, 'Error de configuración' );
            }
            
            if( $this->dir_exists( VIEWPATH . str_replace( "/", DS, $this->_config['template_views_path'] ) ) ){
                $this->_template_view_path = $this->_config['template_views_path'];
            }

            if( $this->dir_exists( VIEWPATH . str_replace( "/", DS, $this->_config['template_layouts_path'] ) ) ){
                $this->_template_layouts_path = $this->_config['template_layouts_path'];
            }
            
            if( $this->dir_exists( VIEWPATH . str_replace( "/", DS, $this->_config['template_errors_path'] ) ) ){
                $this->_template_errors_path = $this->_config['template_errors_path'];
            }

            if( $this->dir_exists( FCPATH . str_replace( "/", DS, $this->_config['template_assets_path'] ) ) ){
                $this->_template_assets_path = $this->_config['template_assets_path'];
                $this->_template_assets_url = $this->_config['template_assets_url'] = $this->CI->config->item( 'base_url' ) . $this->_config['template_assets_path'];
            }

            if( $this->dir_exists( FCPATH . str_replace( "/", DS, $this->_config['public_assets_path'] ) ) ){
                $this->_public_assets_path = $this->_config['public_assets_path'];
                $this->_public_assets_url = $this->_config['public_assets_url'] = $this->CI->config->item( 'base_url' ) . $this->_config['public_assets_path'];
            }

            $this->_app_name                = $this->_title                             = $this->_config['app_name'];
            $this->_vc                      = $this->_config['template_vc'];
            $this->_icon                    = $this->_config['icon'];
            $this->_js                      = $this->_config['js'];
            $this->_jsvars                  = array();
            $this->_css                     = $this->_config['css'];
            
            $this->set_head( $this->_config['head_layout'] );
            $this->set_body( $this->_config['body_layout'] );
            $this->set_scripts( $this->_config['scripts_layout'] );
            $this->set_header( $this->_config['header_layout'] );
            $this->set_aside( $this->_config['aside_layout'] );
            $this->set_panel( $this->_config['panel_layout'] );
            $this->set_footer( $this->_config['footer_layout'] );
            $this->set_page();
        }

        // --------------------------------------------------------------------

        /**
         * Directory Exists
         * 
         * Verifies if the directory exists
         *
         * @param   String $dir Directory to verify
         * @return  Bool
         */
        function dir_exists( $dir )
        {
            $dir = str_replace( "/", DS, $dir );

            if( ! is_dir( $dir ) ){
                log_message('error', 'Directorio {'. $dir .'} No existe');
                return show_error( 'Error al cargar la ruta del directorio: ' . $dir, 0, 'Error de configuración' );    
            }

            return $dir;
        }

        // --------------------------------------------------------------------

        /**
         * Layout Exists
         * 
         * Verifies if the file is present on template layout or view path
         *
         * @param   String $file Filename to verify
         * @return  mixed String with route or false
         */
        private function layout_exists( $file = FALSE )
        {
            if( ! empty( $file ) && is_string( $file ) )
            {
                $file = str_replace( "/", DS, $file );

                if( strpos( $file, '.php' ) === FALSE )
                {
                    $file .= '.php';
                }                

                if( file_exists( $file ) ){
                    return $file;
                }
                else if( file_exists( VIEWPATH . $this->_template_layouts_path . $file ) )
                {
                    return $this->_template_layouts_path . $file;
                }
                else if( file_exists( VIEWPATH . $this->_template_view_path . $file ) )
                {
                    return $this->_template_view_path . $file;
                }
                else
                {
                    return FALSE;
                }
            }
            
            return FALSE;
        }

        // --------------------------------------------------------------------

        /**
         * Add CSS
         * 
         * Adds a CSS file or an array of files to the CSS array
         *
         * @param   mixed $css Array of filenames or single string filename
         * @return  void
         */
        public function add_css( $css = array() )
        {
            if( ! empty( $css ) ){
                if( is_string( $css ) )
                {
                    $css = array( $css );
                }
                $this->_config['css'] = $this->_css = array_merge( $this->_css , $css );                
            }
        }

        // --------------------------------------------------------------------

        /**
         * Add JS
         * 
         * Adds a JS file or an array of files to the JS array
         *
         * @param   mixed $js Array of filenames or single string filename
         * @return  void
         */
        public function add_js( $js = array() )
        {
            if( ! empty( $js ) ){
                if( is_string( $js ) )
                {
                    $js = array( $js );
                }
                $this->_config['js'] = $this->_js = array_merge( $this->_js , $js );
            }            
        }

        // --------------------------------------------------------------------

        /**
         * Add JS vars
         * 
         * Adds variables to a js var
         *
         * @param   Array $vars Array with js variables to be parsed
         * @return  void
         */
        public function add_jsvars( $vars = array() )
        {
            if( is_array( $vars ) ){
                $this->_jsvars = array_merge( $this->_jsvars , $vars );
                return;
            }
        }

        // --------------------------------------------------------------------

        /**
         * Set Template View
         * 
         * Sets the template view path
         *
         * @param   string $path 
         * @return  void
         */
        public function set_template_views( $path = FALSE )
        {
            if( $this->dir_exists( VIEWPATH . str_replace( "/", DS, $path ) ) ){
                $this->_template_view_path = $this->_config['template_views_path'];
            }
        }

        // --------------------------------------------------------------------

        /**
         * Set Template Layouts
         * 
         * Sets the template layouts path
         *
         * @param   string $path 
         * @return  void
         */
        public function set_template_layouts( $path = FALSE )
        {
            if( $this->dir_exists( VIEWPATH . str_replace( "/", DS, $path ) ) ){
                $this->_template_layouts_path = $this->_config['template_layouts_path'];
            }            
        }

        // --------------------------------------------------------------------

        /**
         * Set Template Errors
         * 
         * Sets the template errors path
         *
         * @param   string $path 
         * @return  void
         */
        public function set_template_errors( $path = FALSE )
        {
            if( $this->dir_exists( VIEWPATH . str_replace( "/", DS, $path ) ) ){
                $this->_template_errors_path = $this->_config['template_errors_path'];
            }            
        }

        // --------------------------------------------------------------------

        /**
         * Set Title
         * 
         * Sets title of page in head tag
         *
         * @param   string $title The title of page
         * @return  void
         */
        public function set_title( $title = '' )
        {
        	if( ! empty( $title ) && is_string( $title ) )
        	{
        		$this->_config['title'] = $this->_title = $title . ' | ' . $this->_app_name ;
        	}
        }

        // --------------------------------------------------------------------

        /**
         * Set Icon
         * 
         * Sets icon of page in head tag
         *
         * @param   string $icon The icon name for default location or full url for web icon
         * @return  void
         */
        public function set_icon( $icon = FALSE )
        {
        	if( ! empty( $icon ) && is_string( $icon ) )
        	{
                if( filter_var( $this->_icon, FILTER_FLAG_SCHEME_REQUIRED, FILTER_FLAG_HOST_REQUIRED ) === FALSE  )
                {
                    $icon = str_replace( "/", DS, $icon );

                    if( file_exists( FCPATH . $icon ) )
                    {
                        $icon = $this->CI->config->item( 'base_url' ) . $icon;                        
                    }
                }

                $this->_config['icon'] = $this->_icon = $icon;
        	}
        }

        // --------------------------------------------------------------------

        /**
         * Set Head
         * 
         * Sets the head layout file
         *
         * @param   string $file New head layout file path
         * @return  void
         */
        public function set_head( $file = FALSE )
        {   
            $this->_config['head_layout'] = $this->_head_layout = $this->layout_exists( $file );
        }

        // --------------------------------------------------------------------

        /**
         * Set Scripts
         * 
         * Sets the scripts layout file
         *
         * @param   string $file New scripts layout path
         * @return  void
         */
        public function set_scripts( $file = FALSE )
        {
            $this->_config['scripts_layout'] = $this->_scripts_layout = $this->layout_exists( $file );            
        }

        // --------------------------------------------------------------------

        /**
         * Set Body
         * 
         * Sets the body layout file
         *
         * @param   string $layout New body layout path
         * @return  void
         */
        public function set_body( $file = FALSE )
        {
            $this->_config['body_layout'] = $this->_body_layout = $this->layout_exists( $file );
        }

        // --------------------------------------------------------------------

        /**
         * Set Header
         * 
         * Sets header layout file
         *
         * @param   string $header The header filename
         * @return  void
         */
        public function set_header( $file = FALSE )
        {
            $this->_config['header_layout'] = $this->_header_layout = $this->layout_exists( $file );
        }

        // --------------------------------------------------------------------

        /**
         * Set aside
         * 
         * Sets aside layout file
         *
         * @param   string $aside The aside filename
         * @return  void
         */
        public function set_aside( $file = FALSE )
        {
            $this->_config['aside_layout'] = $this->_aside_layout = $this->layout_exists( $file );
        }

        /**
         * Set panel
         * 
         * Sets panel layout file
         *
         * @param   string $panel The panel filename
         * @return  void
         */
        public function set_panel( $file = FALSE )
        {
            $this->_config['panel_layout'] = $this->_panel_layout = $this->layout_exists( $file );
        }

        // --------------------------------------------------------------------
        /**
         * Set Footer
         * 
         * Sets footer layout file
         *
         * @param   string $footer The footer filename
         * @return  void
         */
        public function set_footer( $file = FALSE )
        {
            $this->_config['footer_layout'] = $this->_footer_layout = $this->layout_exists( $file );            
        }

        // --------------------------------------------------------------------

        /**
         * Set Page
         * 
         * Sets the content page file
         *
         * @param   string $page The page name
         * @return  void
         */
        public function set_page( $page = FALSE )
        {
            $page = $this->layout_exists( $page );

            if( ! $page ){
                $page = $this->_template_errors_path . 'error_404.php';
            }

            $this->_config['page'] = $this->_page = $page; 
        }
		
		 // --------------------------------------------------------------------
        
        /**
         * Build Head
         * 
         * Builds the head tag
         *         
         * @return  string the built head tag
         */
        protected function build_head()
        {
            $vars = new stdClass();

            $vars->title = $this->_title;

            if( file_exists( FCPATH . $this->_icon ) )
            {
                $this->_icon = $this->CI->config->item( 'base_url' ) . $this->_icon;
            }
            
            $vars->icon = $this->_icon;

            $vars->jsvars = $this->_jsvars;

            // Loops through all CSS files loaded, verifies its extension, 
            // determines if its a full url or a relative path and searchs
            // for the file in both public/css and public templates css path
            // (default template path is 'public/templates/default/css/' ).
            // If the files are found on both directories, the templates CSS
            // will OVERRIDE the public ones.
            // Loading order is the same as added on array.
            foreach ($this->_css as $k => $v)
            {
                if( strpos( $v, '.css' ) === FALSE )
                {
                    $v .= '.css';
                }

                if( filter_var( $v, FILTER_FLAG_SCHEME_REQUIRED, FILTER_FLAG_HOST_REQUIRED ) === FALSE  )
                {                    
                    if( file_exists( $this->_template_assets_path . 'css' . DS . str_replace( "/", DS, $v ) ) )
                    {
                        $v = $this->_template_assets_url . 'css/' . $v;
                    }
                    else if( file_exists( $this->_public_assets_path . 'css' . DS . str_replace( "/", DS, $v ) ) )
                    {
                        $v =  $this->_public_assets_url . 'css/' . $v;
                    }
                    else{                        
                        continue;
                    }
                }

                if( $v )
                {
                    $vars->css[$k] = $v . '?' . $this->_vc;
                }
            }

            return $this->CI->load->view( $this->_head_layout, $vars, TRUE );
        }        
        
        // --------------------------------------------------------------------

        /**
         * Build Scripts
         * 
         * Builds the scripts of page
         * 
         * @return  string the built scripts list
         */
        protected function build_scripts()
        {
            $vars = new stdClass();

            $vars->base_url = $this->CI->config->item( 'base_url' );

            // Loops through all CSS files loaded, verifies its extension, 
            // determines if its a full url or a relative path and searchs
            // for the file in both public/css and public templates css path
            // (default template path is 'public/templates/default/css/' ).
            // If the files are found on both directories, the templates CSS
            // will OVERRIDE the public ones.
            // Loading order is the same as added on array.
            foreach ($this->_js as $k => $v)
            {
                if( strpos( $v, '.js' ) === FALSE )
                {
                    $v .= '.js';
                }
                
                if( filter_var( $v, FILTER_FLAG_SCHEME_REQUIRED, FILTER_FLAG_HOST_REQUIRED ) === FALSE  )
                {
                    if( file_exists( $this->_template_assets_path . 'js' . DS . str_replace( "/", DS, $v ) ) )
                    {
                        $v = $this->_template_assets_url . 'js/' . $v;
                    }
                    else if( file_exists( $this->_public_assets_path . 'js' . DS . str_replace( "/", DS, $v ) ) )
                    {
                        $v =  $this->_public_assets_url . 'js/' . $v;
                    }
                    else
                    {                        
                        continue;
                    }
                }
                
                if( $v )
                {
                    $vars->js[$k] = $v . '?' . $this->_vc;
                }
            }

            return $this->CI->load->view( $this->_scripts_layout, $vars, TRUE );
        }

        // --------------------------------------------------------------------

        /**
         * Build Body
         * 
         * Builds the body of page
         * 
         * @param   array $params The params send to the page
         * @return  string the built body
         */
        protected function build_body( $params = array() )
        {
            $vars = new stdClass();

            $vars->header   = $this->_header_layout;
            $vars->aside   = $this->_aside_layout;
            $vars->panel   = $this->_panel_layout;
            $vars->page     = $this->_page;
            $vars->footer   = $this->_footer_layout;

            if( ! empty( $params ) && is_array( $params ) )
            {
                //The array of vars is assigned to the vars object
                foreach ($params as $k => $v)
                {
                    $vars->{$k} = $v;
                }                
            }

            // Adds assets URL
            $vars->tmp_url      = $this->_template_assets_url;
            $vars->pub_url      = $this->_public_assets_url;

            // Add assets path
            $vars->tmp_path     = FCPATH . $this->_template_assets_path;            
            $vars->pub_path     = FCPATH . $this->_public_assets_path;            

            // Add template view path
            $vars->tmp_view     = VIEWPATH . $this->_template_view_path;

            return $this->CI->load->view( $this->_body_layout, $vars, TRUE );
        }

        // --------------------------------------------------------------------

        /**
         * Render
         * 
         * Builds the complete page and renders it
         * 
         * @param   array   $params The params send to the page
         * @param   boolean $return choose to return or output the page
         * @return  output  prints or returns the built page
         */
        public function render( $params = array() , $return = FALSE )
        {       
            $html = '<!DOCTYPE html>' . "\n";
            $html.= '<html lang="en">' . "\n";
            $html.= $this->build_head( ) . "\n";
            $html.= $this->build_body( $params ) . "\n";
            $html.= $this->build_scripts() . "\n";
            $html.= '</html>' . "\n";
            echo $html;
        }

        // --------------------------------------------------------------------

        /**
         * Build Page
         * 
         * Builds a single page or html fragment
         * 
         * @param   string $page Relative path to the page
         * @param   array  $params The params send to the page
         * @param   boolean $return choose to return or output the page
         * @return  string the built page
         */
        public function build_page( $page = FALSE , $params = array(), $return = TRUE )
        {
            $page = $this->layout_exists( $page );

            if( ! $page ){
                $page = $this->_template_errors_path . 'error_404.php';
            }

            // Adds assets URL
            $params['tmp_url'] = $this->_template_assets_url;
            $params['pub_url'] = $this->_public_assets_url;

            // Add assets path
            $params['tmp_path'] = FCPATH . $this->_template_assets_path;            
            $params['pub_path'] = FCPATH . $this->_public_assets_path;            

            // Add template view path
            $params['tmp_view'] = VIEWPATH . $this->_template_view_path;

            if( $return )
            {
                return $this->CI->load->view( $page, $params, TRUE );
            }

            $this->CI->load->view( $page, $params );
        }

        // --------------------------------------------------------------------

        /**
         * Get template view path
         * 
         * Returns template path
         *          
         * @return  string the template path
         */
        public function get_template_view( )
        {
            return $this->_template_view_path;
        }
       
        // --------------------------------------------------------------------

        /**
         * Get Config 
         * 
         * Retrieves the current config
         *         
         * @return  array The config array
         */
        public function get_config()
        {
            return $this->_config;
        }

}