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
* Contains all ajax-calls of the plugin
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

include_once('config.php');
include_once('access-controller.php');
include_once('constants.php');


add_action('wp_ajax_nopriv_sam_remove_ip_from_blacklist', 'sam_remove_ip_from_blacklist');
add_action('wp_ajax_sam_remove_ip_from_blacklist', 'sam_remove_ip_from_blacklist');


function sam_remove_ip_from_blacklist(){

	$ip = $_POST['ip'];

	if (trim($ip) !== ""){
		$fwObj = getAccessManagerObject();
		$fwObj->startSession();
		$fwObj->removeFromBlackList( $ip );
		$fwObj->endSession();
		echo 1;
	}
	else{
		echo 0;
	}


	die();
	
	return;
	
}


add_action('wp_ajax_nopriv_sam_add_ip_to_blacklist', 'sam_add_ip_to_blacklist');
add_action('wp_ajax_sam_add_ip_to_blacklist', 'sam_add_ip_to_blacklist');


function sam_add_ip_to_blacklist(){

	$ip = $_POST['ip'];
	// check if IP is valid 
	if (filter_var($ip, FILTER_VALIDATE_IP)){
		$fwObj = getAccessManagerObject();
		$fwObj->startSession();
		//echo 1 . '|' . $ip;
		if (! $fwObj->isInBlackList( $ip ) ){
			$fwObj->setToBlackList( $ip );
			$fwObj->endSession();
			echo 1 . '|' . $ip;
			die();
	
			return;
		}
		else{
			$fwObj->endSession();
			echo 2 . '|' . "";
			die();
	
			return;
		}
	}
	else{
		echo 0 . '|' . "";
	}


	die();
	
	return;
	
}


// clear blacklist
add_action('wp_ajax_nopriv_sam_clear_blacklist', 'sam_clear_blacklist');
add_action('wp_ajax_sam_clear_blacklist', 'sam_clear_blacklist');

function sam_clear_blacklist(){

	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$fwObj->cleanBlackList();
	$fwObj->endSession();
	
	echo  1;
	
	die();
	
}



add_action('wp_ajax_nopriv_sam_add_ip_to_banlist', 'sam_add_ip_to_banlist');
add_action('wp_ajax_sam_add_ip_to_banlist', 'sam_add_ip_to_banlist');

function sam_add_ip_to_banlist(){

	$ip = $_POST['ip'];
	$period = $_POST['period'];
	
	$fwObj = getAccessManagerObject();
	$fwObj->startSession();	
	
	if ( ! filter_var($ip, FILTER_VALIDATE_IP)){
		$fwObj->endSession();
		// show error message
		echo  3 . "|" . $ip . " " . "" . "|" . 0 . "|" . 0;
		
		die();
	}	
	
	if ($fwObj->isInBanList( $ip ) ){
		$fwObj->endSession();
		// show error message
		echo  2 . "|" . $ip . " " . "" . "|" . 0 . "|" . 0;
		
		die();
	}	
	
	if ( !validatePeriod($period) ){
		$fwObj->endSession();
		echo  0 . "|" . "" . "|" . 0 . "|" . 0;
		
		die();
	}
	
	
	$fwObj->setToBanList( $ip, $period );
	$fwObj->endSession();
	
	// calculate etart and end ban time
	$startTime = time();
	$endTime = strtotime("+" . $period . "seconds", time() );
	$startBanDate = date('Y-m-d H:i:s', $startTime);		
	$endBanDate = date('Y-m-d H:i:s', $endTime);	
	
	echo  1 . "|" . $ip .  "|" . $startBanDate . "|" . $endBanDate;

	die();
	
}


function validatePeriod($period){
	if (trim($period) == ""){
		return false;
	}
	
	if ( (int )trim($period) < 0 ){
		return false;
	}
	
	if ( ! periodIsInt($period)  ){
		return false;
	}
	
	return true;
}


function periodIsInt($s) {
    return ctype_digit($s) || is_int($s);
}


// remove ip from banlist
add_action('wp_ajax_nopriv_sam_remove_ip_from_banlist', 'sam_remove_ip_from_banlist');
add_action('wp_ajax_sam_remove_ip_from_banlist', 'sam_remove_ip_from_banlist');

function sam_remove_ip_from_banlist(){

	$ip = $_POST['ip'];

	$fwObj = getAccessManagerObject();
	$fwObj->startSession();	
	$fwObj->removeFromBanList( $ip );
	$fwObj->endSession();

	echo 1;
	
	die();
	
}


// clear banlist
add_action('wp_ajax_nopriv_sam_clear_banlist', 'sam_clear_banlist');
add_action('wp_ajax_sam_clear_banlist', 'sam_clear_banlist');

function sam_clear_banlist(){

	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$fwObj->cleanBanList();
	$fwObj->endSession();
	
	echo  1;
	
	die();
	
}

// remove ip from number of bans list
add_action('wp_ajax_nopriv_sam_remove_ip_from_bannumberlist', 'sam_remove_ip_from_bannumberlist');
add_action('wp_ajax_sam_remove_ip_from_bannumberlist', 'sam_remove_ip_from_bannumberlist');

function sam_remove_ip_from_bannumberlist(){
	$ip = $_POST['ip'];
	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$fwObj->removeFromBanNumberList( $ip );
	$fwObj->endSession();

	echo 1;
	
	die();
}


// clear number of bans list
add_action('wp_ajax_nopriv_sam_clear_bannumberlist', 'sam_clear_bannumberlist');
add_action('wp_ajax_sam_clear_bannumberlist', 'sam_clear_bannumberlist');

function sam_clear_bannumberlist(){
	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$fwObj->cleanBanNumberList();
	$fwObj->endSession();

	echo 1;
	
	die();
}

// update statistics
add_action('wp_ajax_nopriv_sam_update_statistics', 'sam_update_statistics');
add_action('wp_ajax_sam_update_statistics', 'sam_update_statistics');

function sam_update_statistics(){

	$year = $_POST['year'];
	$month = $_POST['month'];
	$referer = $_POST['referer'];
	
	echo 1 . "|" . sam_draw_statistics_chart($year, $month, $referer);

	die();
}


// draw statistics chart
function sam_draw_statistics_chart($year, $month, $referer){

	// generate chart
	// get chart data
	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$currYear = date('Y', time() );
	$currMonth = date('m', time() );
	$statData = "";
	if ($month != 0){
		$statData = $fwObj->getStatisticsMonth($year , $month, $referer); //SAM_ALL_REFERERS
	}
	else{
		$statData = $fwObj->getStatisticsYear($year , $referer); //SAM_ALL_REFERERS		
	}
	$fwObj->endSession();
	
	$labels = "[";
	$chartData = "[";
	$counter = 0;
	foreach($statData as $stat){	
		if ($counter > 0){
			$labels .= ( ", " . $stat['x'] ) ;
			$chartData .= (", " . $stat['val'] );			
		}
		else{
			$labels .= $stat['x'];
			$chartData .= $stat['val'];				
		}
		
		$counter+=1;
	}
	
	$labels .= "]";
	$chartData .= "]";	
	
	

	return "<div class=\"chart-statistics\" style=\"width: 750px; height: 400px;\">
		<canvas id=\"statChart\" width=\"200\" height=\"100\"></canvas>
	</div>

	<script>
	var ctx = document.getElementById(\"statChart\").getContext('2d');
	var statChart = new Chart(ctx, {
	    type: 'line',
	    data: {
	        labels:" . $labels . ",
	        datasets: [{
	            label: 'Number of Requests',
	            data: " . $chartData .  " ,
	            backgroundColor: 'rgba(200, 200, 200, 0.8)' ,
	            borderColor: 'rgba(100, 100, 100, 0.8)',
	            borderWidth: 1
	        }]
	    },
	    options: {
			scaleShowLabels : true,
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true,
						stepSize: 1,
						display: true,
						labelString: 'Requests'
	                }
	            }],
	            xAxes: [{
	                ticks: {
	                    beginAtZero: true,
						stepSize: 1,
						display: true,
						labelString: 'Days'
	                }
	            }]
	        }
	    }
	});
	</script>";

}



// clean statistics
add_action('wp_ajax_nopriv_sam_clean_statistics', 'sam_clean_statistics');
add_action('wp_ajax_sam_clean_statistics', 'sam_clean_statistics');

function sam_clean_statistics(){

	$fyear = $_POST['fyear'];
	$fmonth = $_POST['fmonth'];
	$freferer = $_POST['freferer'];

	$syear = $_POST['syear'];
	$smonth = $_POST['smonth'];
	
	$eyear = $_POST['eyear'];
	$emonth = $_POST['emonth'];	

	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$daysInEndMonthTotal = cal_days_in_month(CAL_GREGORIAN, (int) $emonth , (int) $eyear );
	$startData = $syear . "-" . $smonth . "-" . "01";
	$endData = $eyear . "-" . $emonth . "-" . $daysInEndMonthTotal;

	$fwObj->cleanStatistics($startData, $endData);
	
	echo 1 . "|" . sam_draw_statistics_chart($fyear, $fmonth, $freferer);
	
	$fwObj->endSession();
	
	die();	
	
}




?>