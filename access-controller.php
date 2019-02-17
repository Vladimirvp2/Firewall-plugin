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
 * Copyright (C) 2017 Vladimir Pishida.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

 /**
* Main file with data operations
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/
 

include_once('config.php');
include_once('data.php');
include_once('constants.php');
include_once('common-functios.php');



class Firewall{

	function __construct(DataInterface $dt){
		$this->data = $dt;
		// period in seconds
		$this->banPeriod = DEFAULT_BAN_PERIOD;
		$this->checkPeriod = DEFAULT_CHECK_PERIOD;
		// max allowed number of access for check period
		$this->checkNumber = DEFAULT_CHECK_NUMBER;
		$this->maxBanNumber = DEFAULT_MAX_BAN_NUMBER;
	}
	
	
	function setBanPeriod( $time ){
		$this->banPeriod = $time;
	}
	
	
	function setCheckPeriod( $time ){
		$this->checkPeriod = $time;
	}


	function setCheckNumber( $number ){
		$this->checkNumber = $number;
	}


	function setMaxBanNumber( $number ){
		$this->maxBanNumber = $number;
	}
	
	
	/**
	 * Call this before each working with the class
	 *
	 * @return void
	 */	
	function startSession(){
		$this->data->startConnection();
	}
	
	
	/**
	 * Call this after all work with the class
	 *
	 * @return void
	 */
	function endSession(){
		$this->data->closeConnection();
	}


	/**
	 * Call this during plugin activation to create database structure
	 *
	 * @return void
	 */
	function createBasicDataStructure(){
		$this->data->createBasicStructure();
	}
	
	
	function removeBasicStructure(){
		$this->data->removeBasicStructure();
	}

	
	/**
	 * Should be called every time if some IP access a site page. 
	 * Add current IP to the IP list table
	 *
	 * @param IP, string
	 *
	 * @return void
	 */		
	function setNewRequest( $ip ){
		$this->data->setIP( $ip );
		$this->data->addStatistics($ip, $this->getCurrentReferer() );
	}

	
	/**
	 * Find out wether current IP is allowed access
	 *
	 * @param IP, string
	 *
	 * @return boolean
	 */	
	function accessAllowed( $ip  ){

		// if $ip is in the black list return false
		$res = true;
		if ($this->data->isInBlackList( $ip )){
			$res = false;
		}
		// if ban period has not elapsed yet return false
		else if ($this->data->isInBanPeriod( $ip ) ){
			//$this->data->removeFromBanListIfExist( $ip );
			$res = false;
		}
		// if current IP is suspicious (see the condition #1) return false
		else if ($this->data->getRequestNumber( $ip, $this->checkPeriod ) >= $this->checkNumber ){
			$this->data->setToBanList( $ip, $this->banPeriod );
			$this->data->increaseBanNumber( $ip );
			// send email if specified
			if ( get_option( SAM_EMAIL_ME_IF_ADD_TO_BANLIST ) ){
				emailAddToBanList( $ip );
			}
			$res = false;
		}
		// if current IP exceeds the max number of bans
		else if ( $this->data->getBanNumber( $ip ) >= $this->maxBanNumber ){
			$this->data->setToBlackList( $ip );
			$res = false;	
		}		
		
		else{
			$res = true;
		}
		
		// remove old records
		$this->data->cleanOldRecordsInBanlist();
		

		return $res;		
	}
	

	function getUserIP() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}	
	
	
	function cleanBlackList(){
		$this->data->cleanBlackList();
	}
	
	
	function setToBlackList( $ip ){
		$this->data->setToBlackList( $ip );
	}
	
	
    function cleanBanList(){
		$this->data->cleanBanList();
		//$this->data->cleanBanNumberList();
	}	
	
	
	function getBanList(){
		return $this->data->getBanList();
	}
	
	function getBlackList(){
		return $this->data->getBlackList();
	}
	
	
	function removeFromBlackList( $ip ){
		$this->data->removeFromBlackList( $ip );
	}
	
	
	function isInBlackList( $ip ){
		return $this->data->isInBlackList( $ip );
	}
	
	function isInBanList( $ip ){
		return $this->data->isInBanList( $ip );
	}
	
	function setToBanList( $ip, $period ){
		$this->data->setToBanList( $ip, $period );
	}
	
	function  removeFromBanList( $ip ){
		$this->data->removeFromBanList( $ip );
	}
	
	/*	@return: assoc_array (ip, number)	*/
	function getBanNumberList(){
		return $this->data->getBanNumberList();
	}
	
	
	function removeFromBanNumberList( $ip ){
		$this->data->removeFromBanNumberList( $ip );
	}
	
	function cleanBanNumberList(){
		$this->data->cleanBanNumberList();
	}
	
	
	function getStatisticsYear($year, $referer){
		return $this->data->getStatisticsYear($year, $referer);
	}
	
	/*	@return assoc_array(day, number)	*/
	function getStatisticsMonth($year, $month, $referer){
		return $this->data->getStatisticsMonth($year, $month, $referer);
	}
	
	function cleanStatistics($startDate, $endDate){
		$this->data->cleanStatistics($startDate, $endDate);
	}
	
	function getCurrentReferer(){
		return $_SERVER["HTTP_REFERER"];
	}
	

	

}


function getAccessManagerObject(){
	if (! $GLOBALS[SAM_OBJECT]){
		$GLOBALS[SAM_OBJECT] = new Firewall( new SQLData(SAM_DB_SERVER, SAM_DB_LOGIN, SAM_DB_PASSWORD, SAM_DB_DATABASE) );
		// init
		$banCheckPeriod = esc_attr( get_option(SAM_BAN_CHECK_PERIOD, DEFAULT_CHECK_PERIOD) );
		$banCheckNumber = esc_attr( get_option(SAM_BAN_CHECK_NUMBER, DEFAULT_CHECK_NUMBER) );
		$banPeriod = esc_attr( get_option(SAM_BAN_PERIOD, DEFAULT_BAN_PERIOD) );
		$banNumberForrAddToBlacklist = esc_attr( get_option(SAM_ADD_BAN_NUMBER_FOR_ADD_TO_BLACKLIST, DEFAULT_MAX_BAN_NUMBER) );
		$GLOBALS[SAM_OBJECT]->setCheckPeriod( $banCheckPeriod );
		$GLOBALS[SAM_OBJECT]->setCheckNumber( $banCheckNumber );
		$GLOBALS[SAM_OBJECT]->setBanPeriod( $banPeriod );
		$GLOBALS[SAM_OBJECT]->setMaxBanNumber( $banNumberForrAddToBlacklist );
	}
	
	return $GLOBALS[SAM_OBJECT];
}





?>