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

if (!class_exists('StageShowLibCalendarClass')) 
{
	class StageShowLibCalendarClass 
	{
		var $cssRoot = 'calendar';
		var $linksOpenNewTab = true;
		var $sideCellIDs = array();
		
		var $dateNow;		// Timestamp of noon today
		var $startDate;		// Timestamp of noon on the first day shown on the calendar
		var $monthsInRow = 1;
		var $noOfWeeks = 52;
		
		var $separateMonths = false;	// Set to true to ensure 1st of month is in new block
	
		var $headerFormat = 'F';
		
		var $activeMonths = false;
		
		var $myDBaseObj;
		
		function __construct($myDBaseObj)
		{		
			$this->myDBaseObj = $myDBaseObj;
			
			$calStartOfWeek = $myDBaseObj->getOption('CalendarStartDay');
			if (!is_numeric($calStartOfWeek))
			{
		  		$calStartOfWeek = get_option( 'start_of_week' );
			}
  		
			// Get current day of the week
			$this->startDate = $this->dateNow = StageShowLibMigratePHPClass::Safe_strtotime(date(StageShowLibDBaseClass::MYSQL_DATE_FORMAT).' 12:00:00');

			// Find date of Start of the Week
			$weekday = date( "w", $this->startDate);
			while ($weekday != $calStartOfWeek)
			{
				$this->startDate -= DAY_IN_SECONDS;
				$weekday = date( "w", $this->startDate);
			}
		}

		function GetDateValue($dateNow)
		{
			// Get date in same format as returned by GetRecordDate()
			// Note: Default is MYSQL formatted date string
			return date(StageShowLibDBaseClass::MYSQL_DATE_FORMAT, $dateNow);			
		}
		
		function OutputCalender($results, $months=-1)
		{		
			$lastDay = 0;	
			$monthActive = false;						 
			$htmlOutput = '';
				
			// Get default class	 
			$dateCellClass = $this->cssRoot.'Date ';		
			$blockClass = $this->cssRoot.'Block ';		
			
			$cellRepeats = 1;
			$cellClass = '';
				
			$eventIndex = 0;
			$eventCount = count($results);
			
			// Get the events list (filtered) starting with last monday
			$calEntryDate = $this->startDate;

			// Find the first record with an (end) date within the range of the calendar
			$result = null;
			for ($eventIndex=0; $eventIndex<$eventCount; $eventIndex++)
			{
				if ($eventIndex===0)
				{
					$result = reset($results);
				}
				else
				{
					$result = next($results);
				}
				$recordTimestamp = $this->GetRecordEndTimestamp($result);
				if ($recordTimestamp >= $calEntryDate) 
				{
					break;
				}
			} 

			$newMonth = true;
			$monthIsStarting = false;
			
			$htmlMonth = '';
			$htmlMonths = array();
			$monthNo = 0;
			
			// Loop Round for noOfWeeks weeks (max)
			for ($weekNo = 1; $weekNo<=$this->noOfWeeks; $weekNo++)
			{
				if ($newMonth)
				{
					$blockMonthDate = $calEntryDate;
					if ($monthIsStarting)
					{
						$weekNo--;
						$calEntryDate -= (7 * DAY_IN_SECONDS);
					}
					
					//if ($result === null) break;
					if ($months-- == 0)
						break;
						
					if ($weekNo != 1) 
					{
						// Add end of table tag block
						$htmlMonth .=  "</tbody></table></div></div>\n";
						if (!$this->activeMonths || $monthActive)
							$htmlMonths[] = $htmlMonth;
						$monthActive = false;
					}
					
					$monthNo++;	
					$htmlMonth = apply_filters($this->cssRoot.'_filter_monthheader', $this->OutputHeader($blockMonthDate), $monthNo);
					$newMonth = false;
				}
					
				$htmlMonth .=  "<tr class=rowstart>\n";			
				
				$htmlWeek = '';
				
				// Loop Round for 7 days
				for ($dayNo = 1; $dayNo<=7; $dayNo++)
				{				
					if ($dayNo == 1)
					{
						foreach ($this->sideCellIDs as $sideCellIndex => $sideCellID)
						{
							$sideCell = $this->GetSideCellValue($result, $sideCellIndex);
							$sideCellClass = $this->GetSideCellClass($sideCellIndex, $result);
							$htmlMonth .=  '<td class="'.$sideCellClass.'">'.$sideCell.'</td>';							
						}
					}
					
					$dayOfMonth = date( "j", $calEntryDate);
					
					// Set default cell text
					$cellLink = $dayOfMonth;

					$dateNowVal = $this->GetDateValue($calEntryDate);

					if ($monthIsStarting && ($dayOfMonth == 1))
					{
					//	$monthIsStarting = false;
					}

					if ($monthIsStarting)
					{
						$htmlWeek .=  '<td>&nbsp</td>'."\n";
					}
					else
					{
						// Loop Through all events with the same date
						$cellAltTag = '';
						while ($eventIndex < $eventCount)
						{
							if ($result == null)
							break;
							
							if ($this->GetRecordDate($result) != $dateNowVal)
								break; 
							
							$tileDateTime = date(StageShowLibDBaseClass::MYSQL_DATE_FORMAT, $calEntryDate);
							$cellClass = $this->DateTileClass($result, $tileDateTime);

							if ($cellRepeats <= 1)
							{
								if ($cellAltTag != '') $cellAltTag .= "\n";
								$cellAltTag .= $this->DateTileTitle($result);
								
								$cellURL = $this->DateTileURL($result);
								if ($cellURL !== '') 
								{
									$monthActive = true;
									$cellLink = "<strong>$dayOfMonth</strong>";
										
									$cellTarget = ($this->linksOpenNewTab) ? ' target="_blank" ' : '';
									
									// TODO - This link will only go to the last entry in the database that matches
									$cellURL = ' href="'.$cellURL.'" ';
									$cellLink =  '<a '.$cellURL.$cellTarget.' alt="'.$cellAltTag.'"  title="'.$cellAltTag.'">'.$cellLink.'</a>';							
								}
								
								$cellRepeats = $this->GetRecordDuration($result);
							}
							
							$eventIndex++;
							if ($eventIndex < $eventCount)
							{
								$nextCellRepeats = $this->GetRecordDuration($result);
								if ($cellRepeats < $nextCellRepeats)
								{
									$cellRepeats = $nextCellRepeats;
								}
								$result = next($results);
							}
							else
							{
								$result = null;
							}
							
						}						
						
						$cellRepeats--;
						$htmlWeek .=  '<td class="'.$dateCellClass.$cellClass.'">'.$cellLink.'</td>'."\n";
						if ($cellRepeats == 0) $cellClass = '';						
					}
					
					if ($lastDay > $dayOfMonth)
						$newMonth = true;
									
					$lastDay = $dayOfMonth;
							 
					$calEntryDate += DAY_IN_SECONDS;
					
					if (date( "j", $calEntryDate) == 1)
					{
						if ($this->separateMonths && ($dayNo < 7))
						{
							$monthIsStarting = !$monthIsStarting;
							$newMonth = $monthIsStarting;
						}
						else
						{
							$newMonth = true;
						}
						$lastDay = 1;
					}
				}
				$htmlMonth .=  $htmlWeek."</tr>\n";
			}			
			$htmlMonth .=  "</tbody></table></div></div>\n";
			if (!$this->activeMonths || $monthActive)
				$htmlMonths[] = $htmlMonth;

			if (!is_numeric($this->monthsInRow) || ($this->monthsInRow < 1)) $this->monthsInRow = 1;
			
			$htmlOutput = '';
			if ($this->monthsInRow > 1) $htmlOutput .= "<table class=coltable>\n";
			for ($month=0, $colNo=0; $month<count($htmlMonths); )
			{
				$htmlOutput .= "<tr class=debugrow>\n";
				for ($colNo=0; $colNo<$this->monthsInRow; $colNo++)
				{
					$htmlOutput .= '<td class="'.$blockClass.'">'."\n";
					$htmlOutput .= isset($htmlMonths[$month]) ? $htmlMonths[$month] : '&nbsp;';				
					$htmlOutput .= "</td>\n";
					$month++;
				}
				$htmlOutput .= "</tr>\n";
			}		
			if ($this->monthsInRow > 1) $htmlOutput .= "</table>\n";
			
			return $htmlOutput;
		}
		
		function OutputHeader( $headerDate )
		{	
			static $blockCount = 1;
			
			$htmlOutput  =  '';

			$blockClass = $this->cssRoot.'MonthBlock '.$this->cssRoot.'MonthBlock'.$blockCount++;
			
			$htmlOutput .= '<div class="'.$blockClass.'">'."\n";						
			$htmlOutput .= '<div class="'.$this->cssRoot.'Month">'.date( $this->headerFormat, $headerDate)."</div>\n";						
			$htmlOutput .= '<div class="'.$this->cssRoot.'">';						
			$htmlOutput .= '<table class="'.$this->cssRoot.'Table"><tbody>'."\n<tr>\n";
			
			foreach ($this->sideCellIDs as $sideCellIndex => $sideCellID)
			{
				$sideCellClass = $this->GetSideCellClass($sideCellIndex);
				$htmlOutput .=  '<td class="'.$sideCellClass.'">'.$sideCellID.'</td>';							
			}
						
			// Loop Round for 7 days
			for ($dayNo = 1; $dayNo<=7; $dayNo++)
			{
				$cellLink = date( "D", $headerDate);
							 
				$htmlOutput .=  '<td class="'.$this->cssRoot.'Day">'.$cellLink.'</td>';
				$htmlOutput .=  "\n";

				$headerDate += DAY_IN_SECONDS;
			}
			
			$htmlOutput .=  "</tr>";
			return $htmlOutput;
		}
		
		function GetRecordTimestamp($result)
		{
			// This function must be defined in a derived class
			return 0;
		}
		
		function GetRecordEndTimestamp($result)
		{
			// This function can be defined in a derived class
			return $this->GetRecordTimestamp($result);
		}
		
		function GetRecordDate($result)
		{
			$timestamp = $this->GetRecordTimestamp($result);
			
			// Strip Time from Date & Time
			$recordDate = date(StageShowLibDBaseClass::MYSQL_DATE_FORMAT, $timestamp);
			return $recordDate;
		}
					
		function GetRecordDuration($result)
		{
			return 1;
		}
					
		function DateTileClass($result, $tileTimestamp)
		{
			return '';
		}
		
		function DateTileTitle($result)
		{
			return '';
		}
		
		function DateTileURL($result)
		{
			return '';
		}
		
		function GetSideCellValue($result, $sideCellIndex)
		{
			return '';
		}
					
		function GetSideCellClass($sideCellIndex, $result = array())
		{
			return '';
		}
		
	}
} //End Class StageShowLibCalendarClass

?>