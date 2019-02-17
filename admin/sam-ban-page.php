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
* Ban list item page
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/


include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'constants.php');




// create fields
function sam_custom_settings_ban(){
	// register fields for storing in DB
	register_setting('sam-settings-ban-group', "fghd");

	// add sections
	add_settings_section('sam-banlist-operations', '', 'sam_banlist_operations_callback', 'sam-banlist-screen');
	
	add_settings_section('sam-banlist-number', '', 'sam_banlist_number_callback', 'sam-banlist-screen');
	
	// add fields	
	add_settings_field('clear-banlist', SAM_LG_BAN_PAGE_CLEAR_BANLIST, 'clear_banlist_callback', 'sam-banlist-screen', 'sam-banlist-operations');	
	add_settings_field('add-to-banlist', SAM_LG_BAN_PAGE_ADD_IP_TO_BANLIST, 'add_to_banlist_callback', 'sam-banlist-screen', 'sam-banlist-operations');	
	add_settings_field('ban-list', SAM_LG_BAN_PAGE_BANLIST, 'banlist_callback', 'sam-banlist-screen', 'sam-banlist-operations');
	
	add_settings_field('ban-list-number-clear', SAM_LG_BAN_PAGE_CLEAR_NUMBER_OF_BANS_LIST, 'banlist_number_clear_callback', 'sam-banlist-screen', 'sam-banlist-number');	
	add_settings_field('ban-list-number', SAM_LG_BAN_PAGE_NUMBER_OF_BANS_LIST, 'banlist_number_callback', 'sam-banlist-screen', 'sam-banlist-number');
		
}


function sam_banlist_operations_callback(){
	echo "<h3>" . SAM_LG_BAN_PAGE_BANLIST_SECTION . "</h3>";
}

function sam_banlist_number_callback(){
	echo "<h3>" . SAM_LG_BAN_PAGE_NUMBER_OF_BANS_LIST_SECTION . "</h3>";
}


// create fields



function clear_banlist_callback(){
	echo "<button type=\"button\" class=\"clear_banlist button-secondary\">" . SAM_LG_CLEAR . "</button>";
}


function add_to_banlist_callback() {	
	echo '<p>' . SAM_LG_TITLE_IP . '</p><input type="text" class="input_ip_to_banlist" ' . ' value="' . '' . '">' .
		'<p>' . SAM_LG_BAN_PAGE_BAN_PERIOD . '</p><input type="text" class="input_time_to_banlist" ' . ' value="' . '' . '">' .
			'&nbsp<button type="button" class="add_ip_to_banlist button-secondary">' . SAM_LG_ADD . '</button>';
}


function banlist_callback(){
	$res = "";
	$fwObj = getAccessManagerObject();
	$list = $fwObj->getBanList();
	
	if (!empty($list)){
		foreach($list as &$e){
			$res .= $e['ip'] . ", " . $e['time'] ;
		}
	}

	$fwObj = getAccessManagerObject();
	$banList = $fwObj ->getBanList();
	
	$val = "<thead>
				<tr>
				  <th class=\"ip-header\">" . SAM_LG_TITLE_IP . "</th>
				  <th class=\"ip-header\">" . SAM_LG_TITLE_START_TIME . "</th>
				  <th class=\"ip-header\">" . SAM_LG_TITLE_END_TIME . "</th>
				  <th class=\"command-column\">" . SAM_LG_TITLE_OPERATION . "</th>
				</tr>
			</thead>";
			
	$removeWord = SAM_LG_REMOVE;		
	$ipRowTemplate = "<tr>
						<td class=\"banlist-ip\">%s</td>
						<td class=\"banlist-starttime\">%s</td>
						<td class=\"banlist-endtime\">%s</td>
						<td class=\"command-column\"><a class=\"remove-from-banlist\" href=\"#\">%s</a></td>
					</tr>"; // 4 param - ip, start-time, end-time, remove
					
	$tableBodyT = "<tbody>%s</tbody>"; // 1 param				
				
	$bodyContent = "";

	foreach($banList as &$banArr){
		$bodyContent .= sprintf($ipRowTemplate, $banArr['ip'], $banArr['start_time'],$banArr['end_time'], $removeWord);
	}
	
	$val .= sprintf($tableBodyT, $bodyContent);
	echo "<table class=\"fixed_headers_ban\" url=\"" . admin_url('admin-ajax.php')  . "\"  name=\"" . 'table-bl-list' . "\"  remove-word=\"" . $removeWord . "\"" . 
		"remove-confirm-message=\"" . SAM_LG_BAN_PAGE_REMOVE_IP_FROM_BANLIST_Q . "\" " .
		"ajax-error-bad-ip-message=\"" . SAM_LG_AJAX_ERROR_BAD_IP . "\" " . 
		"ajax-error-double-ip-message=\"" . SAM_LG_AJAX_ERROR_IP_EXISTS_IN_BANLIST . "\" " . 
		"ajax-error-bad-period-message=\"" . SAM_LG_AJAX_ERROR_BAD_PERIOD . "\" " . 
		"clear-confirm-message=\"" . SAM_LG_BAN_PAGE_CLEAN_BANLIST_Q . " \" >" .
		$val . "</table>";	
	
}


function sam_plugin_create_page_banlist(){
	echo "<h2>" . SAM_LG_BAN_PAGE_TITLE . "</h2>";
	settings_errors();
	echo "<form method=\"post\" action=\"options.php\"> ";
			do_settings_sections( 'sam-banlist-screen' );
	echo	  "</form>";
}



function banlist_number_clear_callback(){

	echo "<button type=\"button\" class=\"clear-banlist-number button-secondary\">" . SAM_LG_CLEAR . "</button>";
}



function banlist_number_callback(){	
	$res = "";
	$fwObj = getAccessManagerObject();
	$listBannumber = $fwObj->getBanNumberList();
	

	$val = "<thead>
				<tr>
				  <th class=\"ip-header\">" . SAM_LG_TITLE_IP . "</th>
				  <th class=\"ip-number\">" . SAM_LG_TITLE_NUMBER . "</th>
				  <th class=\"command-column\">" . SAM_LG_TITLE_OPERATION . "</th>
				</tr>
			</thead>";
			
	$removeWord = SAM_LG_REMOVE;		
	$ipRowTemplate = "<tr>
						<td class=\"bannumberlist-ip\">%s</td>
						<td class=\"bannumberlist-number\">%s</td>
						<td class=\"command-column\"><a class=\"remove-from-bannumberlist\" href=\"#\">%s</a></td>
					</tr>"; // 3 param - ip, number, remove
					
	$tableBodyT = "<tbody>%s</tbody>"; // 1 param				
				
	$bodyContent = "";

	foreach($listBannumber as &$bannumberArr){
		$bodyContent .= sprintf($ipRowTemplate, $bannumberArr['ip'], $bannumberArr['number'], $removeWord);
	}
	
	$val .= sprintf($tableBodyT, $bodyContent);
	echo "<table class=\"fixed_headers_bannumber\" url=\"" . admin_url('admin-ajax.php')  . "\"  name=\"" . 'table-bl-list' . "\"  remove-word=\"" . $removeWord . "\"" . 
		"remove-confirm-message=\"" . SAM_LG_BAN_PAGE_REMOVE_IP_FROM_NUMBER_OF_BANS_LIST_Q . "\" " .
		"ajax-error-message=\"" . SAM_LG_AJAX_ERROR . "\" " . 
		"clear-confirm-message=\"" . SAM_LG_BAN_PAGE_CLEAN_NUMBER_OF_BANS_LIST_Q . " \" >" .
		$val . "</table>";		
	
	
}


?>