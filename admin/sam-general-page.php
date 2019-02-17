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
* General item page
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/


include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'constants.php');




// create fields
function sam_custom_settings_general(){
	// register fields for storing in DB
	register_setting('sam-settings-general-group', SAM_BAN_PERIOD);
	register_setting('sam-settings-general-group', SAM_BAN_CHECK_PERIOD);
	register_setting('sam-settings-general-group', SAM_BAN_CHECK_NUMBER);
	register_setting('sam-settings-general-group', SAM_ADD_BAN_NUMBER_FOR_ADD_TO_BLACKLIST);
	register_setting('sam-settings-general-group', SAM_EMAIL_ME_IF_ADD_TO_BANLIST);
	register_setting('sam-settings-general-group', SAM_EMAIL_IF_ADD_TO_BANLIST, 'email_if_add_to_banlist_filter');
	
	// add sections
	add_settings_section('sam-gen-options', SAM_LG_GENERAL_PAGE_NAME, 'sam_general_options', 'sam-admin');
	
	// add fields
	add_settings_field('ban-condition-field', SAM_LG_BAN_CONDITIONS, 'ban_conditions_callback', 'sam-admin', 'sam-gen-options');
	add_settings_field('ban-period-field', SAM_LG_BAN_PERIOD, 'ban_period_callback', 'sam-admin', 'sam-gen-options');
	add_settings_field('ban-add-to-blacklist-field', SAM_LG_ADD_TO_BLACKLIST_CONDITION, 'ban_add_to_blacklist_conditions_callback', 'sam-admin', 'sam-gen-options');
	add_settings_field('ban-email-while-add-to-banlist', SAM_LG_EMAIL_IF_ADDED_TO_BANLIST, 'email_while_add_to_banlist_callback', 'sam-admin', 'sam-gen-options');
		
}


// create sections
function sam_general_options(){

}


// filters

function email_if_add_to_banlist_filter($res){
	// if email is correct, save it. If not, save the default one
	if (filter_var($res, FILTER_VALIDATE_EMAIL)) {
		return $res;
	} else {
		return 	get_bloginfo('admin_email');
	}
}




// create fields
function ban_conditions_callback(){
	$val_num = esc_attr( get_option(SAM_BAN_CHECK_NUMBER, DEFAULT_CHECK_NUMBER) );
	$val_period = esc_attr( get_option(SAM_BAN_CHECK_PERIOD, DEFAULT_CHECK_PERIOD) );
	echo SAM_LG_TIME_SECONDS . '<br><input type="text" name="' . SAM_BAN_CHECK_PERIOD . '" value="' . $val_period . '" >&nbsp<br>';
	echo SAM_LG_NUMBER_OF_REQUESTS . '<br><input type="text" name="' . SAM_BAN_CHECK_NUMBER . '" value="' . $val_num . '" >';
}


function ban_period_callback(){
	$val = esc_attr( get_option(SAM_BAN_PERIOD, DEFAULT_BAN_PERIOD) );
	echo SAM_LG_TIME_SECONDS . '<br><input type="text" name="' . SAM_BAN_PERIOD . '" value="' . $val . '">';
}


function ban_add_to_blacklist_conditions_callback(){
	$val = esc_attr( get_option(SAM_ADD_BAN_NUMBER_FOR_ADD_TO_BLACKLIST, DEFAULT_MAX_BAN_NUMBER) );
	echo SAM_LG_NUMBER_OF_BANS . '<br><input type="text" name="' . SAM_ADD_BAN_NUMBER_FOR_ADD_TO_BLACKLIST . '" value="' . $val . '" >';
}


function email_while_add_to_banlist_callback(){
	$val = get_option( SAM_EMAIL_ME_IF_ADD_TO_BANLIST ) ;
	$checked = ($val == 1 ? 'checked' : '');
	echo '<input type="checkbox" value="' . 1  .  '" name="' .  SAM_EMAIL_ME_IF_ADD_TO_BANLIST . '" ' . $checked . ' >';
	
	$val = esc_attr( get_option(SAM_EMAIL_IF_ADD_TO_BANLIST, get_bloginfo('admin_email')) );
	echo '&nbsp&nbsp&nbsp<input type="text" name="' . SAM_EMAIL_IF_ADD_TO_BANLIST . '" value="' . $val . '" placeholder="' . SAM_LG_ENTER_EMAIL  . '">';	
	
}


function sam_plugin_create_page_general(){
	//echo "<h1>General</h1>";
	settings_errors();
	echo "<form method=\"post\" action=\"options.php\"> ";
			settings_fields('sam-settings-general-group');
			do_settings_sections( 'sam-admin' );
			submit_button();
	echo	  "</form>";
}


?>