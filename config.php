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
* Plugin config file
* @version    1.0.0
* @since      File available since Release 1.0.0
* @author Vladimir Pishida
*/

include_once('libs/logger.php');
include_once('constants.php');
	

const logFile = "log.log";

// configure the logger
GhLogger::setFileName(dirname(__DIR__)  . DIRECTORY_SEPARATOR . SAM_DIR_NAME . DIRECTORY_SEPARATOR . logFile);
GhLogger::enable();
GhLogger::setLevel(GhLogger::ERROR);

// period in seconds
const DEFAULT_BAN_PERIOD = 1000;
// check period and check number are used together. If the access number exeeds the max check number  in a certain period - check period - 
// than current IP will be added to the banlist
const DEFAULT_CHECK_PERIOD = 60;
// max allowed number of access for check period
const DEFAULT_CHECK_NUMBER = 100;
// max number of bans after which the current ID will be added to the blacklist
const DEFAULT_MAX_BAN_NUMBER = 10;

// period in seconds after wich nmax number in the requests table should be checked
const CLEAN_REQUESTS_PERIOD = 1000;
const CLEAN_REQUESTS_LIMIT_NUMBER = 10000;



?>