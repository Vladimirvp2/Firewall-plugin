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
* Black list item page
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/


include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'constants.php');


// create fields
function sam_custom_settings_blacklist(){

	register_setting('sam-settings-group-blacklist', SAM_ADD_TO_BLACKLIST, 'sam_add_to_blacklist_callback');
	register_setting('sam-settings-group-blacklist', SAM_BLACKLIST_AREA, 'sam_blacklist_area_callback');

	
	// add sections
	add_settings_section('sam-gen-blacklist', '', 'sam_blacklist_options', 'sam-admin-blacklist');
	
	// add fields
	add_settings_field('add-clean-blacklist', SAM_LG_BLACKLIST_PAGE_CLEAR_BLACKLIST, 'clear_blacklist_callback', 'sam-admin-blacklist', 'sam-gen-blacklist');
	add_settings_field('add-to-blacklist', SAM_LG_BLACKLIST_PAGE_ADD_IP_TO_BLACKLIST, 'add_to_blacklist_callback', 'sam-admin-blacklist', 'sam-gen-blacklist');
	add_settings_field('blacklist-area', SAM_LG_BLACKLIST_PAGE_BLACKLIST, 'blacklist_area_callback', 'sam-admin-blacklist', 'sam-gen-blacklist');
	
}


// section title
function sam_blacklist_options(){

}



// page 
function sam_plugin_create_page_blacklist(){
	echo "<h2>" . SAM_LG_BLACKLIST_PAGE_TITLE . "</h2>";
	settings_errors();
	echo "<form method=\"post\" action=\"options.php\"> ";
			settings_fields('sam-settings-group-blacklist');
			do_settings_sections( 'sam-admin-blacklist' );
	echo	  "</form>";
}


// sanitize functions
// add to blacklist entry
function sam_add_to_blacklist_callback( $input ){
	return $input;
}

function sam_blacklist_area_callback( $input ){
	return $input;
}







// plash page fields
function add_to_blacklist_callback() {	
	$val = esc_attr( get_option(SAM_ADD_TO_BLACKLIST) );
	echo '<input type="text" class="input_ip_to_blacklist" name="' . SAM_ADD_TO_BLACKLIST . '" value="' . $val . '">
			<button type="button" class="add_ip_to_blacklist button-secondary">' . SAM_LG_ADD . '</button>';
}


function clear_blacklist_callback(){
	echo "<button type=\"button\" class=\"clear_blacklist button-secondary\">" . SAM_LG_CLEAR . "</button>";
}


function blacklist_area_callback(){

	$fwObj = getAccessManagerObject();
	$blacklistIPs = $fwObj->getBlackList();
	
	$val = "<thead>
				<tr>
				  <th class=\"ip-header\">" . SAM_LG_TITLE_IP . "</th>
				  <th class=\"command-column\">" . SAM_LG_TITLE_OPERATION . "</th>
				</tr>
			</thead>";
			
	$removeWord = SAM_LG_REMOVE;		
	$ipRowTemplate = "<tr>
						<td class=\"blacklist-ip\">%s</td>
						<td class=\"command-column\"><a class=\"remove-from-blacklist\" href=\"#\">%s</a></td>
					</tr>"; // 2 param - ip, remove
					
	$tableBodyT = "<tbody>%s</tbody>"; // 1 param				
				
	$bodyContent = "";			
	foreach($blacklistIPs as &$ipArr){
		$bodyContent .= sprintf($ipRowTemplate, $ipArr['ip'], $removeWord);
	}
	
	$val .= sprintf($tableBodyT, $bodyContent);
	echo "<table class=\"fixed_headers\" url=\"" . admin_url('admin-ajax.php')  . "\"  name=\"" . 'table-bl-list' . "\"  remove-word=\"" . $removeWord . "\"" . 
		"remove-confirm-message=\"" . SAM_LG_BLACKLIST_PAGE_REMOVE_IP_FROM_BLACKLIST_Q . "\" " . 
		"clear-confirm-message=\"" . SAM_LG_BLACKLIST_PAGE_CLEAR_BLACKLIST_Q . "\" " . 
		"ajax-error-message=\"" . SAM_LG_AJAX_ERROR . "\" " . 
		"ajax-error-bad-ip-message=\"" . SAM_LG_AJAX_ERROR_BAD_IP . "\" " . 
		"ajax-error-double-ip-message=\"" . SAM_LG_AJAX_ERROR_IP_EXISTS_IN_BLACKLIST .  "\">" .
		$val  . "</table>";	
		
	
}


?>