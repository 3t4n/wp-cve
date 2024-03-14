<?php
/*
Description: Log File Utilities

Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!class_exists('StageShowLibLogFileClass'))
{
	class StageShowLibLogFileClass // Define class
	{
		const ForReading = 1;
		const ForWriting = 2;
		const ForAppending = 8;

		var	$LogsFolderPath;

		function __construct($LogsFolderPath = '')
		{
			// Remove any trailing slashes
			if (StageShowLibMigratePHPClass::Safe_substr($LogsFolderPath, -1) === '/')
			{
				$LogsFolderPath = StageShowLibMigratePHPClass::Safe_substr($LogsFolderPath, 0, -1);
			}

			if (!is_dir($LogsFolderPath))
			{
				mkdir($LogsFolderPath, STAGESHOWLIB_LOGFOLDER_PERMS, true);
			}

			$this->LogsFolderPath = $LogsFolderPath;
		}

		function GetLogFilePath($Filename)
		{
			$Filepath = StageShowLibMigratePHPClass::Safe_str_replace("\\", "/", $Filename);

			for ($i = 1; $i <= 2; $i++)
			{
				$firstChar = StageShowLibMigratePHPClass::Safe_substr($Filepath, 0, 1);
				if (StageShowLibMigratePHPClass::Safe_strpos($Filepath, ':') || ($firstChar == '/'))
				{
					// This is an absolute path .... leave it as it is
					break;
				}
				else if ($i == 1)
				{
					// Add the "default" logs path folder ... if it is set
					if (StageShowLibMigratePHPClass::Safe_strlen($this->LogsFolderPath) > 0)
						$Filepath = $this->LogsFolderPath.'/'.$Filepath;
				}
				else
				{
					$Filepath = ABSPATH.$Filepath;
				}
			}

			return $Filepath;
		}

		function StampedLogToFile($Filename, $LogLine, $OpenMode = self::ForAppending, $LogHeader = '')
		{			
			$LogStamp  = 'Log Timestamp: '.current_time(DATE_RFC1123)."\n";
			$LogStamp .= 'Log Length: '.StageShowLibMigratePHPClass::Safe_strlen($LogLine)."\n";
			$LogStamp .= "\n";

			$LogLine  = $LogStamp.$LogLine;
			$LogLine .= "\n---------------------------------------------\n\n";

			$this->LogToFile($Filename, $LogLine, $OpenMode, $LogHeader);
		}

		function LogToFile($Filename, $LogLine, $OpenMode = self::ForAppending, $LogHeader = '')
		{
			$Filepath = $this->GetLogFilePath($Filename);
			return self::LogToFileAbs($Filepath, $LogLine, $OpenMode, $LogHeader);
		}

		static function LogToFileAbs($Filepath, $LogLine, $OpenMode = self::ForAppending, $LogHeader = '')
		{

			// Create a filesystem object
			if ($OpenMode == self::ForAppending)
			{
				$fopenMode = "ab";
			}
			else
			{
				$fopenMode = "wb";
			}
			$logFile = fopen($Filepath, $fopenMode);

			// Write log entry
			if ($logFile != 0)
			{
				//$LogLine = "Open Mode: $fopenMode\n" . $LogLine;
				if ($LogHeader !== '')
				{
					fseek($logFile, 0, SEEK_END);
					if (ftell($logFile)	=== 0)
						fwrite($logFile, $LogHeader, StageShowLibMigratePHPClass::Safe_strlen($LogHeader));
				}

				fwrite($logFile, $LogLine, StageShowLibMigratePHPClass::Safe_strlen($LogLine));
				fclose($logFile);

				$rtnStatus = true;
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error writing to ".$Filepath."<br>\n");
				$rtnStatus = false;
			}

			return $rtnStatus;
		}

		function DumpToFile($Filename, $dataId, $dataToDump)
		{
			$Filepath = $this->GetLogFilePath($Filename);

			if (is_array($dataToDump))
			{
				$arrayData = '';
				foreach($dataToDump as $key => $value)
					$arrayData .= "[$key]".$value;
				$dataToDump = $arrayData;
			}

			$dataLen = StageShowLibMigratePHPClass::Safe_strlen($dataToDump);

			$dumpOutput = $dataId."\n";
			for ($i=0;;$i++)
			{
				if (($i % 16) == 0)
				{
					if ($i > $dataLen) break;
					$hexOutput = sprintf("%04x ", $i);
					$asciiOutput = " ";
				}

				$nextChar = StageShowLibMigratePHPClass::Safe_substr($dataToDump, $i, 1);
				if ($i < $dataLen)
				{
					$hexOutput .= sprintf("%02x ", ord($nextChar));
					if ((ord($nextChar) >= 0x20) && (ord($nextChar) <= 0x7f))
						$asciiOutput .= $nextChar;
					else
						$asciiOutput .= ".";
				}
				else
				{
					$hexOutput .= "   ";
					$asciiOutput .= " ";
				}

				if (($i % 16) == 15)
					$dumpOutput .= $hexOutput.$asciiOutput."\n";
			}

			$this->LogToFileAbs($Filepath, $dumpOutput);
		}

		function AddToTestLog($LogLine)
		{
			$Filepath = "testlog.txt";

			self::LogToFileAbs($Filepath, "------------------------------------------------\n");
			self::LogToFileAbs($Filepath, 'Log Time/Date:'.StageShowLibDBaseClass::GetLocalDateTime()."\n");
			//self::LogToFileAbs($Filepath, 'Request URL:'.StageShowLibUtilsClass::GetHTTPTextElem('server', 'REQUEST_URI')."\n");
			self::LogToFileAbs($Filepath, self::ShowCallStack(false));
			self::LogToFileAbs($Filepath, $LogLine."\n");
		}

	}
}



