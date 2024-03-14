<?php
/* 
Description: Core Library Admin Page functions
 
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

include 'stageshowlib_calendar.php'; 

if (!class_exists('StageShowCalendarClass')) 
{
	class StageShowCalendarClass extends StageShowLibCalendarClass
	{
		function __construct($myDBaseObj)
		{
			$this->cssRoot = 'stageshowCal';
			$this->linksOpenNewTab = false;
			
			if (StageShowLibUtilsClass::IsElementSet('request', 'path'))
			{
				$calPath = StageShowLibUtilsClass::GetHTTPFilenameElem($_REQUEST, 'path');
				$this->cellBaseURL = StageShowLibUtilsClass::GetPageHost().$calPath;
			}
			else
			{
				$this->cellBaseURL = StageShowLibUtilsClass::GetPageURL();
			}
			$this->cellBaseURL = remove_query_arg('caldate', $this->cellBaseURL);
			
			parent::__construct($myDBaseObj);
		}
		
		function OutputCalender($results, $months=-1)
		{
			return parent::OutputCalender($results, $months);
		}
		
		function GetRecordTimestamp($result)
		{
			// Convert time string to UNIX timestamp
			$timestamp = StageShowLibMigratePHPClass::Safe_strtotime($result->perfDateTime);
			return $timestamp;
		}
		
		function GetRecordDate($result)
		{
			// Convert time string to UNIX timestamp
			$timestamp = StageShowLibMigratePHPClass::Safe_strtotime( $result->perfDateTime );

			// Just return the date
			return date(StageShowLibDBaseClass::MYSQL_DATE_FORMAT, $timestamp);
		}
					
		function DateTileClass($result, $tileDateTime)
		{
			if ($result->seatsAvailable == 0)
				return ' '.$this->cssRoot.'SoldOut';
			
			if (StageShowLibMigratePHPClass::Safe_strncmp($result->perfDateTime, $tileDateTime, 10) !== false)	// Just compare dates
				return ' '.$this->cssRoot.'Active';
				
			return '';
		}
		
		function DateTileTitle($result)
		{
			$cellAltTag = '';
			$cellAltTag .= $this->myDBaseObj->FormatDateForDisplay($result->perfDateTime);
			$cellAltTag .= " - ".$result->showName;
/*
			if ($result->eventDistance > 0)
				$cellAltTag .= " - ".$result->eventDistance.' Miles';
*/				
			return $cellAltTag;
		}
		
		function DateTileURL($result)
		{
			if ($result->seatsAvailable == 0)
				return '';
				
			$timestamp = $result->perfDateTime;
			$timestamp = StageShowLibMigratePHPClass::Safe_substr($timestamp, 0, StageShowLibMigratePHPClass::Safe_strpos($timestamp, ' '));
			$cellURL = $this->cellBaseURL;
			$cellURL = add_query_arg('caldate', $timestamp, $cellURL);
			return $cellURL;
		}
	}
}

?>
