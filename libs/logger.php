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


class GhLoggerException extends RuntimeException
{
}
class GhLogger
{
    const ERROR_LEVEL = 255;
    const DEBUG = 1;
    const NOTICE = 2;
    const WARNING = 4;
    const ERROR = 8;
    static protected $instance;
    static protected $enabled = false;
    static protected $filename;
    protected $file;
	static protected $currLevel = ERROR;
    static public function setFileName($filename)
    {
        self::$filename =  $filename;
    }
    static public function getFileName()
    {
        if (self::$filename == null)
        {
            self::$filename = dirname(__FILE__).'/GhLogger.log';
        }
        return self::$filename;
    }
    static public function enableIf($condition = true)
    {
        if ((bool) $condition)
        {
            self::$enabled = true;
        }
    }
    static public function enable()
    {
        self::$enabled = true;
    }
    static public function disable()
    {
        self::$enabled = false;
    }
    static protected function getInstance()
    {
        if (!self::hasInstance())
        {
            self::$instance = new self("astreinte.log");
        }
        return self::$instance;
    }
    static protected function hasInstance()
    {
        return self::$instance instanceof self;
    }
    static public function setLevel($level)
    {
        self::$currLevel = $level;
    }
    static public function writeIfEnabled($message, $level = self::DEBUG)
    {
        if (self::$enabled)
        {
            self::writeLog($message, $level);
        }
    }
    static public function writeIfEnabledAnd($condition, $message, $level = self::DEBUG)
    {
        if (self::$enabled)
        {
            self::writeIf($condition, $message, $level);
        }
    }
    static public function writeLog($message, $level = self::DEBUG)
    {
        self::getInstance()->writeLine($message, $level);
    }
    static public function writeIf($condition, $message, $level = self::DEBUG)
    {
        if ($condition)
        {
            self::writeLog($message, $level);
        }
    }
    protected function __construct()
    {
        if (!$this->file = fopen(self::getFileName(), 'a+'))
        {
            throw new GhLoggerException(sprintf("Could not open file '%s' for writing.", self::getFileName()));
        }
        $this->writeLine("\n===================== STARTING =====================", 0);
    }
    public function __destruct()
    {
        $this->writeLine("\n===================== ENDING =====================", 0);
        fclose($this->file);
    }
    protected function writeLine($message, $level)
    {

		if (! self::$enabled){
			return;
		}
		
        if ($level & self::$currLevel)
        {
					echo self::getFileName();
            $date = new DateTime();
            $en_tete = $date->format('d/m/Y H:i:s');
            switch($level)
            {
            case self::NOTICE:
                $en_tete = sprintf("%s (notice)", $en_tete);
                break;
            case self::WARNING:
                $en_tete = sprintf("%s WARNING", $en_tete);
                break;
            case self::ERROR:
                $en_tete = sprintf("\n%s **ERROR**", $en_tete);
                break;
            }
            $message = sprintf("%s -- %s\n",  $en_tete, $message);
            fwrite($this->file, $message);
			
			//echo $message . "<br>";
        }
    }
}