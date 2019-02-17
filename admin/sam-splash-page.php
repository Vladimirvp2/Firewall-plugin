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

/**
* Splash screen item page
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'constants.php');



// create fields
function sam_custom_settings_splash_screen(){

	register_setting('sam-settings-group-splash', SAM_SHOW_SPLASHSCREEN, 'sam_show_splash_screen_callback');
	register_setting('sam-settings-group-splash', SAM_SPLASHSCREEN_TITLE, 'sam_show_splash_screen_title_callback');
	register_setting('sam-settings-group-splash', SAM_SPLASHSCREEN_MESSAGE, 'sam_show_splash_screen_message_callback');
	// advanced settings
	register_setting('sam-settings-group-splash', SAM_ENABLE_CUSTOM_SPLASHSCREEN, 
					'sam_splash_screen_enable_advanced_options_callback');
	register_setting('sam-settings-group-splash', SAM_SPLASHSCREEN_CUSTOM_CSS, 
					'sam_splash_screen_enable_advanced_custom_css_callback');
	register_setting('sam-settings-group-splash', SAM_SPLASHSCREEN_CUSTOM_HTML, 
					'sam_splash_screen_enable_advanced_custom_html_callback');
	
	// add sections
	add_settings_section('sam-gen-splash', '', 'sam_splashscreen_options', 'sam-splash-screen');
	add_settings_section('sam-gen-splash-advanced', '', 'sam_splashscreen_advanced_options', 'sam-splash-screen');
	
	// add fields
	add_settings_field('splash-show', SAM_LG_SPLASH_SHOW, 'show_splash_screen_callback', 'sam-splash-screen', 'sam-gen-splash');
	add_settings_field('splash-title', SAM_LG_SPLASH_TITLE, 'splash_screen_title_callback', 'sam-splash-screen', 'sam-gen-splash');
	add_settings_field('splash-message', SAM_LG_SPLASH_MESSAGE, 'splash_screen_message_callback', 'sam-splash-screen', 'sam-gen-splash');
	// advanced fields
	add_settings_field('splash-advanced-options-custom-css', SAM_LG_SPLASH_CUSTOM_CSS, 'splash_screen_custom_css_callback',
						'sam-splash-screen', 'sam-gen-splash-advanced');
	add_settings_field('splash-show-advanced-options', SAM_LG_SPLASH_USE_CUSTOM_SCREEN, 'splash_screen_show_advanced_options_callback',
						'sam-splash-screen', 'sam-gen-splash-advanced');
	add_settings_field('splash-advanced-options-custom-html', SAM_LG_SPLASH_CUSTOM_HTML_SCREEN, 'splash_screen_custom_screen_html_callback',
						'sam-splash-screen', 'sam-gen-splash-advanced');
	
}


// section title
function sam_splashscreen_options(){
	echo "<h3>" . SAM_LG_SPLASH_GENERAL .  "</h3>";
}

// advanced section title
function sam_splashscreen_advanced_options(){
	echo "<h3>" . SAM_LG_SPLASH_ADVANCED .  "</h3>";
}


// page 
function sam_plugin_create_page_splash_screen(){
	echo "<h2>" . SAM_LG_SPLASH_SCREEN_PAGE_TITLE . "</h2>";
	settings_errors();
	echo "<form method=\"post\" action=\"options.php\"> ";
			settings_fields('sam-settings-group-splash');
			do_settings_sections( 'sam-splash-screen' );
			submit_button();
	echo	  "</form>";
}


// sanitize functions
// show splash screen checkbox
function sam_show_splash_screen_callback( $input ){
	return $input;
}

function sam_show_splash_screen_title_callback( $input ){
	return $input;
}

function sam_show_splash_screen_message_callback( $input ){
	return $input;
}


function sam_splash_screen_enable_advanced_options_callback( $input ){
	return $input;
}


function sam_splash_screen_enable_advanced_custom_css_callback( $input ){
	return $input;
}


function sam_splash_screen_enable_advanced_custom_html_callback( $input ){
	return $input;
}


// plash page fields
function show_splash_screen_callback(){	
	$val = get_option( SAM_SHOW_SPLASHSCREEN ) ;
	$checked = ($val == 1 ? 'checked' : '');
	echo '<input type="checkbox" value="' . 1  .  '" name="' .  SAM_SHOW_SPLASHSCREEN . '" ' . $checked . ' >';
}


function splash_screen_title_callback(){
	$val = esc_attr( get_option(SAM_SPLASHSCREEN_TITLE, SAM_DEFAULT_SPLASHSCREEN_TITLE) );
	echo '<input type="text" name="' . SAM_SPLASHSCREEN_TITLE . '" value="' . $val . '" size="48">';

}

function splash_screen_message_callback(){
	$val = esc_attr( get_option(SAM_SPLASHSCREEN_MESSAGE, SAM_DEFAULT_SPLASHSCREEN_MESSAGE) );
	echo '<textarea rows="2" cols="48" style="resize: none;" name="' . SAM_SPLASHSCREEN_MESSAGE . '" >' . $val . "</textarea>";
}


// advanced fields



function splash_screen_custom_css_callback(){
	$val = esc_attr( get_option( SAM_SPLASHSCREEN_CUSTOM_CSS ) );
	echo '<textarea rows="7" cols="48" style="resize: none;" name="' . SAM_SPLASHSCREEN_CUSTOM_CSS . '" >' . $val . "</textarea>";
}


function splash_screen_show_advanced_options_callback(){
	$val = get_option( SAM_ENABLE_CUSTOM_SPLASHSCREEN ) ;
	$checked = ($val == 1 ? 'checked' : '');
	echo '<input type="checkbox" value="' . 1  .  '" name="' .  SAM_ENABLE_CUSTOM_SPLASHSCREEN . '" ' . $checked . ' >&nbsp' . SAM_LG_SPLASH_CUSTOM_TEXT;
}


function splash_screen_custom_screen_html_callback(){
	$val =  get_option( SAM_SPLASHSCREEN_CUSTOM_HTML  );
	echo SAM_LG_BODY_HTML_CONTENT . '<br>' . '<textarea rows="7" cols="48" style="resize: none;" name="' . SAM_SPLASHSCREEN_CUSTOM_HTML . '" >' . $val . "</textarea>";
}

?>