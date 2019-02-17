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
* Statistics item page
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/


include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'access-controller.php');
include_once( dirname(__DIR__) . '..' . DIRECTORY_SEPARATOR . 'constants.php');




function sam_custom_settings_statistics(){
	// register fields for storing in DB
	register_setting('sam-settings-statistics-group', "stats");

	// add sections
	add_settings_section('sam-statistics-operations', '', 'sam_statistics_operations_callback', 'sam-admin-statistics');
	
	
	// add fields
	add_settings_field('statistics-table', SAM_LG_STATISTICS_PAGE_STATISTICS_OF_REQUESTS, 'statistics_table_callback', 'sam-admin-statistics', 'sam-statistics-operations');	
	add_settings_field('statistics-clear-section', SAM_LG_STATISTICS_PAGE_STATISTICS_CLEAR_STATISTICS, 'statistics_clear_callback', 'sam-admin-statistics', 'sam-statistics-operations');
}

// section 1
function sam_statistics_operations_callback(){

}



// page
function sam_plugin_create_page_statistics(){
	echo "<h2>" . SAM_LG_STATISTICS_PAGE_TITLE . "</h2>";
	settings_errors();
	echo "<form method=\"post\" action=\"options.php\"> ";
			do_settings_sections( 'sam-admin-statistics' );
	echo	  "</form>";
}


// fields
function statistics_table_callback(){


	// show select years	
	$currYear = date('Y', time() );
	$currMonth = date('m', time() );
	echo "
		  <b>" . SAM_LG_STATISTICS_PAGE_STATISTICS_FILTER . "</b>:
		  <div id=\"year-select-container\" style=\";margin-left:5px;display: inline-block;\"" . "year=\"" . $currYear . "\"" . ">" .
			"<select id='year-select'>
			" . createYearsSelectContent() . "
			</select> 
			
		   </div>";  
   
	// generate months options	
	// show months select options
	echo   "<div id=\"month-select-container\" style=\";margin-left:5px;display: inline-block;\"" . "month=\"" . $currMonth . "\""  . ">"  .
		"<select id='month-select' >
			"  . createMonthsSelectContent(13) .  "
		</select> 
		
		</div>
		
		";


	// show referers
	echo " <b>" . SAM_LG_STATISTICS_PAGE_STATISTICS_REFERER . "</b>:
	  <div id=\"referer\" style=\";margin-left:5px;display: inline-block;\" custom-referer-id=\"custom\"" .  "referer=\"" . SAM_ALL_REFERERS . "\"" . ">" .
		"<select id='referer-select' >
			"  . createRefererSelectContent()  . "
		</select> 
	   </div>    
	  
	  <input id=\"custom-referer\" type=\"text\" name=\"custom-referer\" value=\"\" placeholder=\"" . SAM_LG_STATISTICS_ENTER_CUSTOM_REFERER . "\">
	  
	";	

	echo "&nbsp&nbsp&nbsp&nbsp&nbsp<button type=\"button\" class=\"statistics-filter button-secondary\" ajax-url=\"" . admin_url('admin-ajax.php') . "\" "  . 
			"ajax-error-message=\"" . SAM_LG_AJAX_ERROR . "\" " . 
		"\">" . SAM_LG_STATISTICS_PAGE_STATISTICS_APPLY_FILTER .
		"</button>";
		
	echo "<br><br>";
	
	// generate chart
	// get chart data
	$fwObj = getAccessManagerObject();
	$fwObj->startSession();
	$currYear = date('Y', time() );
	$currMonth = date('m', time() );
	$statData = $fwObj->getStatisticsMonth($currYear , $currMonth, SAM_ALL_REFERERS);
	$fwObj->endSession();
	
	$labels = "[";
	$chartData = "[";
	$counter = 0;
	// find max Y value. It's used to find out the y-step
	$maxY = 0;
	foreach($statData as $stat){	
		if ($counter > 0){
			$labels .= ( ", " . $stat['x'] ) ;
			$chartData .= (", " . $stat['val'] );
			if ($stat['val'] > $maxY){
				$maxY = $stat['val'];
			}
		}
		else{
			$labels .= $stat['x'];
			$chartData .= $stat['val'];				
		}
		
		$counter+=1;
	}
	
	$labels .= "]";
	$chartData .= "]";
	
	$chartStepY = getChartStepSizeY( $maxY );

	// show chart
	echo "<div class=\"chart-container\">
			<div class=\"chart-statistics\" style=\"width: 750px; height: 400px;\">
				<canvas id=\"statChart\" width=\"200\" height=\"100\"></canvas>
			</div>

	<script>
	var ctx = document.getElementById(\"statChart\").getContext('2d');
	var statChart = new Chart(ctx, {
	    type: 'line',
	    data: {
	        labels:" . $labels . ",
	        datasets: [{
	            label: " . "\"" . SAM_LG_STATISTICS_PAGE_STATISTICS_NUMBER_OF_REQUESTS . "\"" . ",
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
						stepSize: " . $chartStepY . ",
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
	</script>
	</div>";
}


function getChartStepSizeY( $total ){

	$length = ceil(log10(abs( $total ) + 1));
	$step = (int ) ( '1' . str_repeat('0', $length - 1) );
	
	if ($total / $step <= 2){
		return $step / 10;
	}
	
	if ($total / $step <= 5) {
		return $step / 2;
	}
	
	return $step;

}


function createMonthsSelectContent($num=12){

	$option = "<option value='%s'>%s</option>";
	$optionSelected = "<option selected value='%s'>%s</option>";

   // generate months options
	$monthsArr = "";
	$currMonth = date('m', time() );
	$monthAcc = "";	
   if ($num == 13){
		$monthsArr = [SAM_LG_ALL, SAM_LG_JANUARY, SAM_LG_FEBRUARY, SAM_LG_MARCH, SAM_LG_APRIL, SAM_LG_MAY, SAM_LG_JUNE, SAM_LG_JULY, SAM_LG_AUGUST, SAM_LG_SEPTEMBER, SAM_LG_OCTOBER,
				SAM_LG_NOVEMBER, SAM_LG_DECEMBER];  	
		for ($i = 0; $i <= 12; $i++){;
			if ($i == $currMonth){
				$monthAcc .= sprintf ($optionSelected, $i, $monthsArr [$i] );
			}
			else {
				$monthAcc .= sprintf ($option, $i, $monthsArr [$i]);
			}
		}
				
   }
   else{
		$monthsArr = [SAM_LG_JANUARY, SAM_LG_FEBRUARY, SAM_LG_MARCH, SAM_LG_APRIL, SAM_LG_MAY, SAM_LG_JUNE, SAM_LG_JULY, SAM_LG_AUGUST, SAM_LG_SEPTEMBER, SAM_LG_OCTOBER,
				SAM_LG_NOVEMBER, SAM_LG_DECEMBER]; 

		for ($i = 0; $i < 12; $i++){;
			if ($i == $currMonth){
				$monthAcc .= sprintf ($optionSelected, $i+1, $monthsArr [$i] );
			}
			else {
				$monthAcc .= sprintf ($option, $i+1, $monthsArr [$i]);
			}
		}				
   }
				

	
	return $monthAcc;
}


function createYearsSelectContent(){
	$currYear = date('Y', time() );
	$option = "<option value='%s'>%s</option>";
	$optionSelected = "<option selected value='%s'>%s</option>";	
	$yearsAcc = "";
	for ($i = 0; $i <= ($currYear - SAM_START_YEAR); $i++){
		$iyear = SAM_START_YEAR + $i;
		if ($iyear == $currYear){
			$yearsAcc .= sprintf ($optionSelected , $iyear, $iyear );
		}
		else {
			$yearsAcc .= sprintf ($option, $iyear, $iyear);
		}
	}
	
	return $yearsAcc;
}

function createRefererSelectContent(){
	$option = "<option value='%s'>%s</option>";
	$optionSelected = "<option selected value='%s'>%s</option>";

	$referersArr = array(
		array('value' => SAM_ALL_REFERERS, 'text' => SAM_LG_ALL),
		array('value' => '', 'text' => SAM_LG_SITE),
		array('value' => 'google', 'text' => 'google'),
		array('value' => 'yandex', 'text' => 'yandex'),
		array('value' => 'facebook', 'text' => 'FB'),
		array('value' => 'vk.com', 'text' => 'VK'),
		array('value' => 'youtube.com', 'text' => 'youtube'),
		array('value' => 'custom', 'text' => SAM_LG_CUSTOM),
	);
	
	$refererAcc = "";
	foreach($referersArr as &$e){
		if ($e['value'] == SAM_ALL_REFERERS){
			$refererAcc .= sprintf ($optionSelected, $e['value'], $e['text']);
		}
		else{
			$refererAcc .= sprintf ($option, $e['value'], $e['text']);
		}
	}

	return 	$refererAcc;
}



function statistics_clear_callback(){

	// find out the year and month for the clean section
	$currYear = date('Y', time() );
	$currMonth = date('m', time() );
	$selectMonth = "";
	$selectYear = "";
	if ($currMonth == 1){
		$selectMonth = 12; 
		$selectYear = $currYear - 1;
	}
	else{
		$selectMonth = $currMonth - 1; 
		$selectYear = $currYear;
	}
	

	// show select years	
	echo "<p>" . SAM_LG_STATISTICS_REMOVE_INFO . "</p><br>";
	echo "
		  <b>" . SAM_LG_STATISTICS_FROM . ":</b>
		  <div class=\"statistics-clean-year-start-container\" style=\";margin-left:5px;display: inline-block;\" " . "value=\"" . $selectYear .  "\"" . ">" . 
			"<select id='statistics-clean-start-year'>
			" . createYearsSelectContent() . "
			</select> 
			
		   </div>";  
   
	// generate months options	
	// show months select options
	echo   "<div class=\"statistics-clean-month-start-container\" style=\";margin-left:5px;display: inline-block;\" " . "value=\"" . $selectMonth .  "\"" . ">" . 
		"<select id='statistics-clean-start-month' >
			"  . createMonthsSelectContent(12) .  "
		</select> 
		
		</div>
		
		";
		
		
	echo "&nbsp&nbsp&nbsp";	
	// show select years	
	echo "
		  <b>" . SAM_LG_STATISTICS_TILL . ":</b>
		  <div class=\"statistics-clean-year-end-container\" style=\";margin-left:5px;display: inline-block;\" " . "value=\"" . $selectYear .  "\"" . ">" .
			"<select id='statistics-clean-end-year'>
			" . createYearsSelectContent() . "
			</select> 
			
		   </div>";  
   
	// generate months options	
	// show months select options
	echo   "<div class=\"statistics-clean-month-end-container\" style=\";margin-left:5px;display: inline-block;\" " . "value=\"" . $selectMonth .  "\"" . ">" .
		"<select id='statistics-clean-end-month' >
			"  . createMonthsSelectContent(12) .  "
		</select> 
		
		</div>
		
		";
		
		
		
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<button type=\"button\" class=\"clear-statistics button-secondary\" ajax-url=\"" .
	admin_url('admin-ajax.php')  . "\" " .
	"clear-confirm-message=\"" . SAM_LG_STATISTICS_CLEAR_Q . "\" " .
	"data-bad-message=\"" . SAM_LG_STATISTICS_CLEAR_START_LATER_ERROR . "\" " .
	"ajax-error-message=\"" . SAM_LG_AJAX_ERROR . "\" " . 
	">" . SAM_LG_STATISTICS_CLEAR . "</button>";
	

	

}



?>