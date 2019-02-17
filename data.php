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
* Contains classes and interfaces for data models
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

include_once('config.php');
include_once('constants.php');



global $wpdb;

/**
* Interface for data model
*/
interface DataInterface
{
	function createBasicStructure();
	
	function startConnection();
	
	function closeConnection();
	/**
	* Set the IP by each request
	*/
    public function setIP( $ip );
	
    public function isInBlackList( $ip );
	
    public function isInBanPeriod( $ip );
	
	public function isInBanList( $ip );
	/**
	* get the number of requests for a given ip and periond
	*/
    public function getRequestNumber( $ip, $period  );
	
    function  setToBlackList( $ip );
	
    function  removeFromBlackList( $ip );
	
	function  removeFromBanNumberList( $ip );
	
    function  setToBanList( $ip, $period );
	
    function  removeFromBanList( $ip );
	
	function  removeFromBanListIfExist( $ip );

    function cleanBlackList();
	
    function cleanBanList();
	
    function cleanRequestList(); 
	
	function cleanOldRecords();
	
	function getBanNumber( $ip );
	
	function increaseBanNumber( $ip );
	
	function cleanBanNumber( $ip );
	
	function cleanBanNumberList();
	
	/*	@return: assoc_array (ip, time)	*/
	function getBanList();
	
	/*	@return: assoc_array (ip, number)	*/
	function getBanNumberList();
	
	/*	@return: assoc_array (ip)	*/
	function getBlackList();		
	
	function removeBasicStructure();
	
	function addStatistics($ip, $referer);
	
	function cleanStatistics($startDate, $endDate);
	
	/*	@return assoc_array(month, val)	*/
	function getStatisticsYear($year, $referer);
	
	/*	@return assoc_array(day, val)	*/
	function getStatisticsMonth($year, $month, $referer);
	
}


class FireWallDatabaseException extends Exception {
	/*	Exception occuring while some problem with the database	*/
	public function errorMessage() {
    //error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
			.': <b>'.$this->getMessage().'</b> Problem with the database';
		return $errorMsg;
	}
}



/**
* MySQL implementation of  data model
*/
class SQLData implements DataInterface{

	const CLEAN_PERIOD = CLEAN_REQUESTS_PERIOD;
	const CLEAN_LIMIT_NUMBER = CLEAN_REQUESTS_LIMIT_NUMBER;

	function __construct($server, $login, $password, $database ){
		$this->DB_SERVER = $server;
		$this->DB_LOGIN = $login;
		$this->DB_PASSWORD = $password;
		$this->DB_DATABASE = $database;
		$this->connection = NULL;
	
		
		$this->date = new DateTime();
	}


	function createBasicStructure(){
	
		$req = [];
		$req[] = "CREATE TABLE IF NOT EXISTS `" . SAM_DB_TABLE_REQUEST .  "` (
			`ID` int(11) unsigned NOT NULL auto_increment,
			`IP` varchar(20) NOT NULL,
			`LAST_R` timestamp NOT NULL,
			PRIMARY KEY  (`ID`),
			KEY(`IP`)
		)";	
		
		$req[] = "CREATE TABLE IF NOT EXISTS `" . SAM_DB_TABLE_BANLIST . "` (
			`ID` int(11) unsigned NOT NULL auto_increment,
			`IP` varchar(20) NOT NULL UNIQUE,
			`START_TIME` timestamp NOT NULL,
			`END_TIME` timestamp NOT NULL,
			PRIMARY KEY  (`ID`),
			KEY(`IP`)
		)";

		$req[] = "CREATE TABLE IF NOT EXISTS `" . SAM_DB_TABLE_BLACKLIST . "` (
			`ID` int(11) unsigned NOT NULL auto_increment,
			`IP` varchar(20) NOT NULL UNIQUE,
			PRIMARY KEY  (`ID`),
			KEY(`IP`)
		)";	

		$req[] = "CREATE TABLE IF NOT EXISTS `" . SAM_DB_TABLE_BANNUMBERLIST . "` (
			`ID` int(11) unsigned NOT NULL auto_increment,
			`IP` varchar(20) NOT NULL UNIQUE,
			`TOTAL` int(8) NOT NULL,
			PRIMARY KEY  (`ID`),
			KEY(`IP`)
		)";

		$req[] = "CREATE TABLE IF NOT EXISTS `" . SAM_DB_TABLE_STATISTICS . "` (
			`ID` int(11) unsigned NOT NULL auto_increment,
			`TOTAL` int(8) NOT NULL,
			`DATE` date NOT NULL,
			`REFERER` varchar(50) NOT NULL,
			PRIMARY KEY  (`ID`),
			KEY(`DATE`)
		)";				
				
				
		$this->startConnectionIfNecessary();
				
		foreach($req as &$query){
			$this->executeQuery( $query );
		}
		
		mysqli_commit( $this->connection );
		
	}

	
	private function executeQuery( $query ){
		$result = mysqli_query($this->connection, $query);
		if ($result) {
			GhLogger::writeLog("Query " . $query . " seccessfully executed ", GhLogger::DEBUG);
		} else {
			GhLogger::writeLog("Problems arose while executing query " . $query . ", " . mysqli_error( $this->connection ), GhLogger::ERROR);
			mysqli_close($this->connection);
			throw new FireWallDatabaseException( mysqli_error( $this->connection ) );
		}
		
		return $result;
				
	}	
	
	
	private function executeQueryNoResult( $query ){
		try {
			mysqli_query($this->connection, $query);
			GhLogger::writeLog("Query " . $query . " seccessfully executed ", GhLogger::DEBUG);
		}
		catch(Exception $e) {
			GhLogger::writeLog("Problems arose while executing query " . $query. ", " . mysqli_error( $this->connection ), GhLogger::ERROR);
			throw new FireWallDatabaseException( mysqli_error( $this->connection ) );
		}
	}	
	
	
	public function startConnection(){
		GhLogger::writeLog("Establishing connection to the database", GhLogger::DEBUG);
		//$this->connection = mysqli_connect(	$this->DB_SERVER, $this->DB_LOGIN, $this->DB_PASSWORD, $this->DB_DATABASE);
		$this->connection = mysqli_connect(	DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		mysqli_query($this->connection, "SET NAMES UTF8");
		// Check connection
		if (!$this->connection) {
			GhLogger::writeLog("SQLData, failed to connect to database " . mysqli_connect_error(), GhLogger::DEBUG);
			throw new FireWallDatabaseException( mysqli_connect_error() );
		}
	}
	
	public function closeConnection(){
		mysqli_close($this->connection);
		GhLogger::writeLog("Closing connection to the database", GhLogger::DEBUG);
	}
	
	
	private function startConnectionIfNecessary(){
		if (!$this->connection || $this->connection->connect_error) {		
			$this->startConnection();
		}	
	}
	
	
    public function setIP( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		//$this->cleanOldRecords();
		
		$currDate = date('Y-m-d H:i:s', time() );		
		$queryInsert = sprintf ("INSERT INTO " . SAM_DB_TABLE_REQUEST  . " (IP, LAST_R) VALUES ('%s', '%s')", $ip, $currDate );
		$this->executeQuery( $queryInsert );
		
		mysqli_commit( $this->connection );	
		
	}
	
	
	/**
	* get the number of requests for a given ip and periond in seconds
	*/
    public function getRequestNumber( $ip, $period  ){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$startTime = strtotime("-" . $period . "seconds", time() );	
		$endTime = time();
		
		$startDate = date('Y-m-d H:i:s', $startTime);		
		$endDate = date('Y-m-d H:i:s', $endTime);
		
		$query = sprintf ("SELECT COUNT(*) num FROM " . SAM_DB_TABLE_REQUEST  .  " WHERE IP='%s' AND LAST_R BETWEEN '%s' AND '%s'  GROUP BY IP", $ip, $startDate, $endDate );
		
		$result = $this->executeQuery( $query );
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$number = $row["num"];
			
			return $number;
		}
		
		return 0;
	
	}	
	
	
    function cleanRequestList(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("DELETE FROM " . SAM_DB_TABLE_REQUEST);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );			
	} 	
	
	
    public function isInBlackList( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("SELECT ID, IP FROM " . SAM_DB_TABLE_BLACKLIST  . " WHERE IP='%s'", $ip);
		$result = $this->executeQuery( $sql );
		
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		
		return false;
	
	}
	

    function  setToBlackList( $ip ){
		// ignore if the entry with such ip already exists
		if($this->isInBlackList( $ip ) ){
			return;
		}
		// start connection if closed
		$this->startConnectionIfNecessary();

		$sql = sprintf ("INSERT IGNORE INTO " . SAM_DB_TABLE_BLACKLIST .  " (IP) VALUES ('%s')", $ip);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );			
	}


    function  removeFromBlackList( $ip ){
		// ignore if no entry with such ip  exists
		if( !$this->isInBlackList( $ip ) ){
			return;
		}	
	
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("DELETE FROM " . SAM_DB_TABLE_BLACKLIST . " where IP='%s'", $ip);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );			
	}	
	
	
    function cleanBlackList(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("DELETE FROM " . SAM_DB_TABLE_BLACKLIST);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );			
	}


	function getBlackList(){
		// start connection if closed
		$this->startConnectionIfNecessary();

		$sql = sprintf ("SELECT * FROM " . SAM_DB_TABLE_BLACKLIST);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );	
		$res = [];
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$res[] = array (
					'ip' => $row["IP"]
				);
			}			
		}

		return $res; 		
	}
	
	
    public function isInBanPeriod( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();	
	
		$currDate = date('Y-m-d H:i:s', time() );		
		
		$sql = sprintf ("SELECT ID, IP FROM " . SAM_DB_TABLE_BANLIST . " WHERE IP='%s' AND END_TIME >= '%s'", $ip, $currDate);	
		$result = $this->executeQuery( $sql );

		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		
		return false;		
	
	}
	
	
	function isInBanList( $ip ){	
		// start connection if closed
		$this->startConnectionIfNecessary();			
		
		$sql = sprintf ("SELECT ID, IP FROM " . SAM_DB_TABLE_BANLIST . " WHERE IP='%s' ", $ip );	
		$result = $this->executeQuery( $sql );

		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		
		return false;	
	}

	
    function  setToBanList( $ip, $period ){
		// start connection if closed
		$this->startConnectionIfNecessary();
			
		$startTime = time();
		$endTime = strtotime("+" . $period . "seconds", time() );
		
		$startDate = date('Y-m-d H:i:s', $startTime);		
		$endDate = date('Y-m-d H:i:s', $endTime);		
		
		$sql = sprintf ("INSERT IGNORE INTO " . SAM_DB_TABLE_BANLIST . " (IP, START_TIME, END_TIME) VALUES ('%s', '%s', '%s')", $ip, $startDate, $endDate);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );			
	}
	
	
    function  removeFromBanList( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("DELETE FROM " . SAM_DB_TABLE_BANLIST . " where IP='%s'", $ip);
		$result = $this->executeQuery( $sql );
		mysqli_commit( $this->connection );			
	}
	
	
	function removeFromBanListIfExist( $ip ){
		$sql = sprintf ("SELECT ID, IP FROM " . SAM_DB_TABLE_BANLIST . " WHERE IP='%s'", $ip);	
		$result = $this->executeQuery( $sql );

		if (mysqli_num_rows($result) > 0) {
			$this->removeFromBanList( $ip );
		}		
	}	

	
    function cleanBanList(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("DELETE FROM " . SAM_DB_TABLE_BANLIST);
		$result = $this->executeQuery( $sql );
		mysqli_commit( $this->connection );			
	}
	
	
	function cleanOldRecords(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$endTime = strtotime("-" . self::CLEAN_PERIOD . "seconds", time() );		
		$endDate = date('Y-m-d H:i:s', $endTime);
		
		$query = sprintf ("SELECT COUNT(*) num FROM " . SAM_DB_TABLE_REQUEST . " WHERE LAST_R < '%s' ", $endDate );
		
		$result = $this->executeQuery( $query );
		
		$number = 0;
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$number = $row["num"];
		}

		// if the limit of old records is reached, clean them
		if($number > self::CLEAN_LIMIT_NUMBER){
			$query = sprintf ("DELETE FROM " . SAM_DB_TABLE_REQUEST . " WHERE LAST_R < '%s' ", $endDate );
			$this->executeQuery( $query );
		}	
		
		mysqli_commit( $this->connection );	
	}
	
	
	function getBanNumber( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();
				
		$query = sprintf ("SELECT TOTAL FROM " . SAM_DB_TABLE_BANNUMBERLIST . " WHERE IP = '%s'", $ip );
		$result = $this->executeQuery( $query );
		
		$number = 0;
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$number = $row["TOTAL"];			
		}
		
		return $number;
		
	}
	
	
	function increaseBanNumber( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();

		$num = $this->getBanNumber( $ip );
		$query = "";
		if ($num == 0){
			$query = sprintf ("INSERT IGNORE INTO " . SAM_DB_TABLE_BANNUMBERLIST . " (IP, TOTAL) VALUES ('%s', %s)", $ip, $num+1);
		} 
		else {
			$query = sprintf ("UPDATE " . SAM_DB_TABLE_BANNUMBERLIST . " SET TOTAL=%s WHERE IP='%s'", $num+1, $ip);
		}
		$result = $this->executeQuery( $query );
		
		mysqli_commit( $this->connection );		
	}
	
	
	function cleanBanNumber( $ip ){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$query = sprintf ("DELETE FROM " . SAM_DB_TABLE_BANNUMBERLIST . " WHERE IP = '%s' ", $ip );
		$this->executeQuery( $query );

		mysqli_commit( $this->connection );			
	}
	
	
	function cleanBanNumberList(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$query = sprintf ("DELETE FROM " . SAM_DB_TABLE_BANNUMBERLIST);
		$this->executeQuery( $query );

		mysqli_commit( $this->connection );		
	}
	
	
	/**
	 * Get full ban list
	 *
	 * @return assoc array ( 'ip', 'number' )
	 */
	function getBanNumberList(){
		// remove old records
		$this->cleanOldRecordsInBanlist();
		// start connection if closed
		$this->startConnectionIfNecessary();		
		$query = sprintf ("SELECT * FROM " . SAM_DB_TABLE_BANNUMBERLIST . " ORDER BY TOTAL DESC");
		$result = $this->executeQuery( $query );

		$data = [];
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				$data[] = array (
					'ip' => $row["IP"],
					'number' => $row["TOTAL"],
				);
			}
		}		
		
		return $data;
	}	
	
	
    function  removeFromBanNumberList( $ip ){
		// ignore if no entry with such ip  exists
		$num = $this->getBanNumber( $ip );
		if( $num < 1 ){
			return;
		}	
	
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$sql = sprintf ("DELETE FROM " . SAM_DB_TABLE_BANNUMBERLIST . " where IP='%s'", $ip);
		$result = $this->executeQuery( $sql );	
		mysqli_commit( $this->connection );			
	}	
	
	
	
	/**
	 * Get full ban list
	 *
	 * @return assoc array ( 'ip', 'time' )
	 */
	function getBanList(){
		// remove old records
		$this->cleanOldRecordsInBanlist();
		// start connection if closed
		$this->startConnectionIfNecessary();		
		$query = sprintf ("SELECT * FROM " . SAM_DB_TABLE_BANLIST);
		$result = $this->executeQuery( $query );

		$data = [];
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				$data[] = array (
					'ip' => $row["IP"],
					'start_time' => $row["START_TIME"],
					'end_time' => $row["END_TIME"]
				);
			}
		}		
		
		return $data;
	}
	
	
	function cleanOldRecordsInBanlist(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$endTime = strtotime("-" . self::CLEAN_PERIOD . "seconds", time() );		
		$endDate = date('Y-m-d H:i:s', $endTime);
		
		
		$currentTime = time();
		$currentDate = date('Y-m-d H:i:s', $currentTime);	
		
		$query = sprintf ("DELETE FROM " . SAM_DB_TABLE_BANLIST . " WHERE END_TIME < '%s' ", $currentDate );
		$this->executeQuery( $query );
	
		mysqli_commit( $this->connection );	
	}	
	
	
	/**
	 * Remove the tables from the database. Should be called during plugin uninstall
	 *
	 * @return void
	 */	
	function removeBasicStructure(){
		// start connection if closed
		$this->startConnectionIfNecessary();
		$requests[] = "DROP TABLE " . SAM_DB_TABLE_REQUEST ;
		$requests[] = "DROP TABLE " . SAM_DB_TABLE_BANLIST;
		$requests[] = "DROP TABLE " . SAM_DB_TABLE_BLACKLIST;
		$requests[] = "DROP TABLE " . SAM_DB_TABLE_BANNUMBERLIST;
		
		foreach ($requests as &$query){
			$this->executeQueryNoResult( $query );
		}
		
		mysqli_commit( $this->connection );	

	}
	
	
	/**
	 * Clean all the records in the statistics table
	 *
	 * @return void
	 */	
	function cleanStatistics($startDate="", $endDate=""){
		$startDate = trim($startDate);
		$endDate = trim($endDate);
		// start connection if closed
		$this->startConnectionIfNecessary();
		$query = "";
		// if periods are not specified, remove all
		if ($startDate == "" && $endDate == ""){
			$query = sprintf ( "DELETE FROM " . SAM_DB_TABLE_STATISTICS);
		}
		// if startDate is specified and endDate not, remove to the current
		else if ($startDate != "" && $endDate == ""){
			$query = sprintf ( "DELETE FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE>='%s' ", $startDate);
		}
		// if startDate is not specified and endDate is, remove all to the endDate
		else if ($startDate == "" && $endDate != ""){
			$query = sprintf ( "DELETE FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE<='%s' ", $endDate);
		}
		else{ // both pariods are specified		
			$query = sprintf ( "DELETE FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE BETWEEN '%s' AND '%s' ", $startDate, $endDate );
		}
		$this->executeQuery( $query );

		mysqli_commit( $this->connection );		
	}
	
	

	function getStatisticsYear($year, $referer=""){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$res = [];
		$monthTotal = 12;
		// if year is the current one, than set the end month to the current one
		$currYear = date('Y', time() );
		if ((int) $year == $currYear){
			$monthTotal = date('m', time() );
		}
		
		for ($month = 1; $month <= $monthTotal; $month++){
			// start date
			$checkDateStart =  $year . '-' . sprintf("%02d", $month) . '-' . '01' ;
			// end date
			$daysInMonthTotal = cal_days_in_month(CAL_GREGORIAN, (int) $month , (int) $year );
			$checkDateEnd = $checkDateEnd =  $year . '-' . sprintf("%02d", $month) . '-' . $daysInMonthTotal;
			
			$query = "";
			// if the referer is SAM_ALL_REFERERS fetch all the data for a certain month
			if (trim($referer) == SAM_ALL_REFERERS){
				$query = sprintf ("SELECT SUM(TOTAL) num FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE BETWEEN '%s' AND '%s'  ", $checkDateStart, $checkDateEnd  );
			}
			else{
				if (trim($referer) == ""){
					$query = sprintf ("SELECT SUM(TOTAL) num FROM " . SAM_DB_TABLE_STATISTICS . " WHERE (DATE BETWEEN '%s' AND '%s') AND REFERER='%s'  ", $checkDateStart, $checkDateEnd, $referer  );
				}
				else{
					$query = sprintf ("SELECT SUM(TOTAL) num FROM " . SAM_DB_TABLE_STATISTICS . " WHERE (DATE BETWEEN '%s' AND '%s') AND REFERER LIKE '%s'  ", $checkDateStart, $checkDateEnd, '%' . $referer . '%'  );
				}
			}
			$result = $this->executeQuery( $query );
			
			$row = mysqli_fetch_assoc($result);

			$number = 0;
			if (mysqli_num_rows($result) > 0) {
				$number = ($row['num'] == "" || !$row['num']) ? 0 : $row['num'];
			}

			// add result
			$res[] = array(
				'x' => $month,
				'val' => $number
			);
		}
		
		mysqli_commit( $this->connection );	
		
		return $res;
	}


	function getStatisticsMonth($year, $month, $referer=""){
		// start connection if closed
		$this->startConnectionIfNecessary();
		
		$res = [];
		// get the total number of days in the given month and year
		$daysTotal = cal_days_in_month(CAL_GREGORIAN, (int) $month , (int) $year );
		// if year and month are the current ones, than set the end day to the current one
		$currYear = date('Y', time() );
		$currMonth = date('m', time() );
		if ((int) $year == $currYear && (int) $month == $currMonth){
			$daysTotal = date('d', time() );
		}
		for ($day = 1; $day <= $daysTotal; $day++){
			$checkDate =  $year . '-' . sprintf("%02d", $month) . '-' . $day;
			
			$query = "";
			// if referer is SAM_ALL_REFERERS ferch all the data for a certain day
			if (trim($referer) == SAM_ALL_REFERERS){
				$query = sprintf ("SELECT SUM(TOTAL) num FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE='%s' ", $checkDate );
			}
			else{
				if (trim($referer) == ""){
					$query = sprintf ("SELECT SUM(TOTAL) num FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE='%s' AND REFERER='%s' ", $checkDate, $referer );				
				}
				else{
					$query = sprintf ("SELECT SUM(TOTAL) num FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE='%s' AND REFERER LIKE '%s' ", $checkDate, '%' . $referer .'%' );
				}
			}
			
			$result = $this->executeQuery( $query );
		
			$number = 0;
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$number = ($row['num'] == "" || !$row['num']) ? 0 : $row['num'];
			}

			// add result
			$res[] = array(
				'x' => $day,
				'val' => $number
			);
		}
		
		mysqli_commit( $this->connection );	
		
		return $res;
	}	

	
	function addStatistics($ip, $referer=""){
		$currDate = date('Y-m-d', time());
		// start connection if closed
		$this->startConnectionIfNecessary();
			
		// check if the record with the current date already exists
		$query = sprintf ("SELECT TOTAL FROM " . SAM_DB_TABLE_STATISTICS . " WHERE DATE = '%s' AND REFERER = '%s' ", $currDate, $referer  );
			$result = $this->executeQuery( $query );
		
			$number = 0;
			$query = "";
			// if there is no record with such date
			if (mysqli_num_rows($result) == 0) {
				$query = sprintf ("INSERT INTO " . SAM_DB_TABLE_STATISTICS  . " (TOTAL, DATE, REFERER) VALUES ('%s', '%s', '%s')", 1, $currDate, $referer );
			}
			else{
				$row = mysqli_fetch_assoc($result);
				$number = $row["TOTAL"];
				$query = sprintf ("UPDATE " . SAM_DB_TABLE_STATISTICS . " SET TOTAL=%s WHERE DATE='%s' AND REFERER='%s' ", $number+1, $currDate, $referer);
			}
			
			$this->executeQuery( $query );
			mysqli_commit( $this->connection );	
			
	}	


	
	

	
	

	
	
}



?>