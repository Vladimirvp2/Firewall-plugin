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
* Access denied item page
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/


include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'constants.php');


// create fields
function sam_custom_settings_access_denied_screen(){
	// section 1
	register_setting('sam-settings-access-denied-group', SAM_ACCESS_DENIED_TITLE);
	register_setting('sam-settings-access-denied-group', SAM_ACCESS_DENIED_MESSAGE);
	// section 2
	register_setting('sam-settings-access-denied-group', SAM_SHOW_CUSTOM_DENIED_WINDOW);
	register_setting('sam-settings-access-denied-group', SAM_CUSTOM_DENIED_WINDOW_CSS);
	register_setting('sam-settings-access-denied-group', SAM_CUSTOM_DENIED_WINDOW_HTML);

	// add sections
	add_settings_section('sam-section-access-denied', '', 'sam_access_denied_title', 'sam-access-denied-screen');
	add_settings_section('sam-section-access-denied-custom', '', 'sam_access_denied_custom_window_title', 'sam-access-denied-screen');
	
	// add fields section 1
	add_settings_field('access-denied-title', SAM_LG_ACCESS_DENIED_GENERAL_WINDOW_TITLE, 'access_denied_title_callback', 'sam-access-denied-screen', 'sam-section-access-denied');
	add_settings_field('access-denied-message', SAM_LG_ACCESS_DENIED_GENERAL_MESSAGE, 'access_denied_message_callback',
						'sam-access-denied-screen', 'sam-section-access-denied');

	// add fields to section 2						
	add_settings_field('access-denied-custom-access-denied-window-css', SAM_LG_ACCESS_DENIED_ADVANCED_CSS_STYLES, 'access_denied_custom_window_css_callback',
						'sam-access-denied-screen', 'sam-section-access-denied-custom');
						
	add_settings_field('access-denied-show-custom-access-denied-window', SAM_LG_ACCESS_DENIED_ADVANCED_SHOW_WINDOW, 'access_denied_custom_window_callback',
						'sam-access-denied-screen', 'sam-section-access-denied-custom');						
						
	add_settings_field('access-denied-custom-access-denied-window-html', SAM_LG_ACCESS_DENIED_ADVANCED_HTML, 'access_denied_custom_window_html_callback',
						'sam-access-denied-screen', 'sam-section-access-denied-custom');
	
}


// section title
function sam_access_denied_title(){
	echo "<h3>" . SAM_LG_ACCESS_DENIED_GENERAL . "</h3>";
}

function sam_access_denied_custom_window_title(){
	echo "<h3>" . SAM_LG_ACCESS_DENIED_ADVANCED . "</h3>";
}


// page
function sam_plugin_create_page_access_denied_screen(){

	echo "<h2>" . SAM_LG_ACCESS_DENIED_PAGE_TITLE . "</h2>";
	settings_errors();
	echo "<form method=\"post\" action=\"options.php\"> ";
			settings_fields('sam-settings-access-denied-group');
			do_settings_sections( 'sam-access-denied-screen' );
			submit_button();
	echo	  "</form>";

}



// fields
// section 1
function access_denied_title_callback(){
	$val = esc_attr( get_option(SAM_ACCESS_DENIED_TITLE, SAM_DEFAULT_ACCESS_DENIED_TITLE) );
	echo '<input type="text" name="' . SAM_ACCESS_DENIED_TITLE . '" value="' . $val . '" size="48" >';
}


function access_denied_message_callback(){
	$val = esc_attr( get_option(SAM_ACCESS_DENIED_MESSAGE, SAM_DEFAULT_ACCESS_DENIED_MESSAGE) );
	echo '<textarea rows="2" cols="48" style="resize: none;" name="' . SAM_ACCESS_DENIED_MESSAGE . '" >' . $val . "</textarea>";
}

// section 2
function access_denied_custom_window_callback(){
	$val = get_option( SAM_SHOW_CUSTOM_DENIED_WINDOW ) ;
	$checked = ($val == 1 ? 'checked' : '');
	echo '<input type="checkbox" value="' . 1  .  '" name="' . SAM_SHOW_CUSTOM_DENIED_WINDOW . '" ' . $checked . ' >&nbsp ' . SAM_LG_ACCESS_DENIED_ADVANCED_HELP_TEXT;
}


function access_denied_custom_window_css_callback(){
	$val = esc_attr( get_option( SAM_CUSTOM_DENIED_WINDOW_CSS ) );
	echo '<textarea rows="7" cols="48" style="resize: none;" name="' . SAM_CUSTOM_DENIED_WINDOW_CSS . '" >' . $val . "</textarea>";
}


function access_denied_custom_window_html_callback(){
	$val =  get_option( SAM_CUSTOM_DENIED_WINDOW_HTML  );
	echo SAM_LG_ACCESS_DENIED_ADVANCED_BODY_CONTENT . '<br>' . '<textarea rows="7" cols="48" style="resize: none;" name="' . SAM_CUSTOM_DENIED_WINDOW_HTML . '" >' . $val . "</textarea>";
}


?>