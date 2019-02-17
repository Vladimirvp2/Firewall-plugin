<?php

/*
    This file is part of Site Access Manager plugin.

    Site Access Manager plugin is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Site Access Manager plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Site Access Manager plugin.  If not, see <http://www.gnu.org/licenses/>.
*/


/*
Plugin Name: Site Access Manager
Description: Plugin to protect WEb site from attacts, limit or deny access for chosen IPs, set configurable splash screen, gain request statistics
Author: Vladimir Pishida
Author email: vladimir.pishida@gmail.com
Version: 1.0
*/


include_once('config.php');
include_once('access-controller.php');
include_once('constants.php');

define('SAM_BASENAME', plugins_url().'/'.str_replace(basename(__FILE__), "", plugin_basename(__FILE__)));


// activate during activation
function sam_plugin_activate() {

    // Create DB structure if necessary
	$fw = getAccessManagerObject();
	$fw->startSession();
	$fw->createBasicDataStructure();
	$fw->endSession();
	
}

register_activation_hook( __FILE__, 'sam_plugin_activate' );


function sam_plugin_uninstall(){
    // Create DB structure if necessary
	$fw = getAccessManagerObject();
	$fw->startSession();
	$fw->removeBasicStructure();
	$fw->endSession();
}

register_uninstall_hook( __FILE__, 'sam_plugin_uninstall' );




add_action('wp_head' , 'start_l');

add_action('get_footer' , 'end_l');

function start_l(){
	$GLOBALS['S_TIME'] = microtime(true);

	wp_register_style( 'sam_splash_screen_css', SAM_BASENAME . 'css/sam_splash_screen.css' );
	wp_enqueue_style( 'sam_splash_screen_css' );
}


function end_l(){
	$GLOBALS['E_TIME'] = microtime(true);
	
	//echo $GLOBALS['S_TIME'] . "<br>";
	//echo $GLOBALS['E_TIME'] . "<br>";
	echo $GLOBALS['E_TIME'] - $GLOBALS['S_TIME'];

}


//include styles and scripts
add_action('admin_enqueue_scripts', 'sam_enqueue_scripts');

function sam_enqueue_scripts() {

  
	// styles
	wp_register_style( 'sam_admin_css', plugins_url( 'site-access-manager/css/sam_admin.css' ) );
	wp_enqueue_style( 'sam_admin_css' ); 
		
	// scripts
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);	
	
	wp_register_script( 'sam_chart_min_js', plugins_url( 'site-access-manager/js/third-part/Chart.min.js' ) );
	wp_enqueue_script( 'sam_chart_min_js' );	
	
	wp_register_script( 'sam_admin_js', plugins_url( 'site-access-manager/js/sam_admin.js' ) );
	wp_enqueue_script( 'sam_admin_js' );	
	
	wp_enqueue_script( 'thickbox' );
    wp_enqueue_style( 'thickbox' );
	
	wp_register_style( 'w3_css', plugins_url( 'site-access-manager/css/third-part/w3.css' ) );
	wp_enqueue_style( 'w3_css' ); 		
}


add_action( 'plugins_loaded', 'myplugin_load_textdomain' );

function myplugin_load_textdomain() {
	load_plugin_textdomain( SAM_LG_DOMAIN, false, plugins_url( 'site-access-manager' . '/languages' ) ); 

	require_once('language-constants.php');
}



//load custom styles for splash screen
function print_inline_script() {
	// check if advanced options are turned on
	if ( get_option( SAM_ENABLE_ADVANCED_SPLASHSCREEN_OPTIONS ) ){
		$cssVal = get_option( SAM_SPLASHSCREEN_CUSTOM_CSS );
		echo "<style type=\"text/css\">"
			. $cssVal .
		"</style>";
	
	}
}

add_action( 'wp_head', 'print_inline_script' );



// redirect page while loading
add_action('get_header', 'fake_page_redirect');

function fake_page_redirect(){

	//return;
    global $wp;

    //retrieve the query vars and store as variable $template 
    $template = $wp->query_vars;
	
	// check if splash screen is turned on
	if ( get_option( SAM_SHOW_SPLASHSCREEN ) ){
		// check if custom splash screen is turned on
		if ( get_option( SAM_ENABLE_CUSTOM_SPLASHSCREEN ) ){
			require_once('splash-screen/custom-splash-screen.php');
			
			die();
			exit;
		}
		// show default splash screen
		else {
			require_once('splash-screen/default-splash-screen.php');
			
			die();
			exit;
		}
	}
		
	$ip = '123.34.56.78';
	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	//$ip = $fwObj->getUserIP();
	// if access not allowed, show access denied window
	if ( ! $fwObj->accessAllowed( $ip ) ){
		$fwObj->endSession();
		// check if custom access denied screen is turned on
		if ( get_option( SAM_SHOW_CUSTOM_DENIED_WINDOW ) ){
			require_once('splash-screen/custom-access-denied-splash-screen.php');
			
			die();
			exit;
		}
		// show default splash screen
		else {
			require_once('splash-screen/default-access-denied-splash-screen.php');
			
			die();
			exit;
		}		
	}
	
	$fwObj->setNewRequest( $ip );

	$fwObj->endSession();

}



function sam_add_admin_page(){

	add_menu_page('Firewall', __('Site Access Manager', SAM_LG_DOMAIN), 'manage_options',
	'sam-admin', 'sam_plugin_create_page_general', SAM_BASENAME  .  '/img/wall_green.png', 110);	
	
	add_submenu_page('sam-admin', 'SAM Splashscreen', __( 'Splash screen', SAM_LG_DOMAIN), 'manage_options',
	'sam-splash-screen', 'sam_plugin_create_page_splash_screen');		
	
	
	add_submenu_page('sam-admin', 'SAM Splashscreen access denied',  __('Access denied screen', SAM_LG_DOMAIN), 'manage_options',
	'sam-access-denied-screen', 'sam_plugin_create_page_access_denied_screen');		
	
	add_submenu_page('sam-admin', 'SAM banlist', __('Ban list', SAM_LG_DOMAIN), 'manage_options',
	'sam-banlist-screen', 'sam_plugin_create_page_banlist');		
		
		
	add_submenu_page('sam-admin', 'SAM Blacklist', __('Black list', SAM_LG_DOMAIN), 'manage_options',
	'sam-admin-blacklist', 'sam_plugin_create_page_blacklist');
	
	add_submenu_page('sam-admin', 'SAM Statistics', __('Statistics', SAM_LG_DOMAIN), 'manage_options',
	'sam-admin-statistics', 'sam_plugin_create_page_statistics');	

	add_action('admin_init', 'sam_custom_settings_general');
	add_action('admin_init', 'sam_custom_settings_splash_screen');
	add_action('admin_init', 'sam_custom_settings_access_denied_screen');
	add_action('admin_init', 'sam_custom_settings_ban');
	add_action('admin_init', 'sam_custom_settings_blacklist');
	add_action('admin_init', 'sam_custom_settings_statistics');
}

add_action('admin_menu', 'sam_add_admin_page');



require_once('admin/sam-ban-page.php');
require_once('admin/sam-splash-page.php');
require_once('admin/sam-blacklist-page.php');
require_once('admin/sam-general-page.php');
require_once('admin/sam-access-denied-page.php');
require_once('admin/sam-statistics-page.php');


require_once('ajax-calls.php');




?>