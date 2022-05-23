<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * NOTE: Please use trailing slashes for every template related variable
 */


/**
 *
 * Name of the application
 * Displayed on the title of the page
 * 
 */
$config['app_name'] = "Proyecto";


/**
 *
 * The templates version control. 
 * Prevents caching of files after updates
 * update when changes to css/js/img are made
 *
 */
$config['template_vc'] = 1; 


/**
 *
 * The template view path loaded by default. 
 * If you wanna use another template change here or 
 * by the set_template_view function
 * Relative to view path.
 *
 */
$config['template_views_path'] = 'template/';


/**
 *
 * The errors path relative to the views folder
 * Change here for default layout or custom by using the
 * set_template_errors function
 *
 */
$config['template_errors_path'] = 'template/errors/';


/**
 *
 * The template layouts path loaded by default. 
 * If you wanna use another template change here or 
 * by the set_template_layouts function 
 * Relative to view path.
 * 
 */
$config['template_layouts_path'] = 'template/layouts/';


/**
 *
 * The template resources folder path loaded by default.
 * If you wanna use another template change here or 
 * by the  function
 * Relative to base path.
 * 
 */
$config['template_assets_path'] = 'assets/template/';


/**
 *
 * The public resources folder path loaded by default.
 * If you wanna use another template change here.
 * Relative to base path.
 *
 */
$config['public_assets_path'] = 'assets/public/';


/**
 *
 * Icon by default are under root of the application. 
 * Put full path if you want other folder or web icon
 * Relative to base path
 * 
 */
$config['icon'] = 'favicon.ico';


/**
 *
 * The CSS by default, can be full or partial name.
 * You must include location of file relative to your css folder on the public assets
 * or your template assets folder.
 * Library will try to locate the css files first on public and then on templates folder.
 * if the file is on both folders, the template css will OVERRIDE the public one
 * The order you load the css in the array is the order loaded on layout 
 * Further css adds will go after this ones in the same order 
 * To add custom css for a page use the add_css function
 *
 */
$config['css'] = array(
		'font-awesome/font-awesome.min',
		'admintemplate/datatables.bundle',
 		'admintemplate/plugins.bundle',
 		'admintemplate/prismjs.bundle',
 		'admintemplate/style.bundle',
 		'admintemplate/base/dark',
 		'admintemplate/menu/dark',
 		'admintemplate/brand/light',
 		'admintemplate/aside/light',
		'jquery-toast/jquery.toast',
		'general.css',
		'dropzone/dropzone',
	);


/**
 *
 * The JS by default, can be full or partial name.
 * You must include location of file relative to your js folder on the template assets
 * or your templates js folder
 * Library will try to locate the js files on public and then on templates folder
 * if the file's in both folders, the templates js will OVERRIDE the public ones
 * The order you load the js in the array is the order loaded on layout
 * To add custom js for a page use the add_js function
 *
 */
$config['js'] = array(
	'admintemplate/plugins.bundle',
	'admintemplate/prismjs.bundle',
	'admintemplate/scripts.bundle',
	'admintemplate/paginations',
	'jquery/jquery.min',
	'moment/moment.min',
	'moment/moment-with-locales.min',
	'jquery-validate/jquery.validate.min',
	'jquery-validate/jquery.validate.defaults',
	'jquery-validate/localization/messages_es',
	'bootstrap-4/bootstrap.min',
	'datatables/datatables.bundle',
	'jquery-toast/jquery.toast',
	'select2/select2.full.min',
	'dropzone/dropzone',
	'constants',
	'scripts'
);


/**
 *
 * The head layout file path relative to the template layouts path
 * Change here for default layout or by using the
 * set_head_layout function
 *
 */
$config['head_layout'] = 'head';


/**
 *
 * The body layout path relative to the template path
 * Change here for default layout or custom by using the
 * set_body_layout function
 *
 */
$config['body_layout'] = 'body';


/**
 *
 * The header path relative to the template path
 * Change here for default or custom by using the
 * set_header function.
 * header can be empty since not all pages use it
 *
 */
$config['header_layout'] = 'header';


/**
 *
 * The header path relative to the template path
 * Change here for default or custom by using the
 * set_header function.
 * header can be empty since not all pages use it
 *
 */
$config['aside_layout'] = 'aside';

/**
 *
 * The header path relative to the template path
 * Change here for default or custom by using the
 * set_header function.
 * header can be empty since not all pages use it
 *
 */
$config['panel_layout'] = 'panel';


/**
 *
 * The footer path relative to the template path
 * Change here for default or custom by using the
 * set_footer function.
 * footer can be empty since not all pages use it
 *
 */
$config['footer_layout'] = 'footer';


/**
 *
 * The scripts layout path relative to the template view path
 * Change here for default layout or custom by using the
 * set_scripts_layout function
 *
 */
$config['scripts_layout'] = 'scripts';

