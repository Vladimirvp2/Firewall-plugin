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
* Contains general plugin functions
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

function emailAddToBanList($ip){
	// send mail
	$to = get_option( SAM_EMAIL_IF_ADD_TO_BANLIST , get_bloginfo('admin_email') );
	$subject = 'IP ' . $ip . ' was added to the banlist because of exceeding the limit of requests: ' .
	esc_attr( get_option(SAM_BAN_CHECK_NUMBER, DEFAULT_CHECK_NUMBER) ) . ' for ' . 
	esc_attr( get_option(SAM_BAN_CHECK_PERIOD, DEFAULT_CHECK_PERIOD) ) . ' ' . 'seconds.';
	$headers[] = "";
	$headers[] = 'From: ' . get_site_url();
	
	wp_mail($to, $subject, $message, $headers);	
	
}


?>