<?php
/* 
Description: Code for Data Export functionality
 
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

if (!class_exists('StageShowTemplateExportAdminClass')) 
{
	class StageShowTemplateExportAdminClass 
	{
		function __construct() //constructor	
		{
			include STAGESHOW_INCLUDE_PATH.'stageshowlib_export.php';	 
			
			$stageShowDBaseClass = STAGESHOWLIB_DBASE_CLASS;
			$myDBaseObj = new $stageShowDBaseClass(__FILE__);  
			   
			// Get the seating plan template
			$seatingID = $_GET['seatingID'];
			$seatingPlan = StageShowZonesDBaseClass::GetSeatingTemplate($myDBaseObj, $seatingID);
			if (count($seatingPlan) == 0) die;

			$seatsTemplate = $seatingPlan[0]->seatingTemplate;
			$seatsTemplate = $myDBaseObj->UpdateSeatingTemplateTags($seatsTemplate, $statusMsg, 0);
			
			$fileExtn = 'txt';
			$fileName = 'stageshow-template-'.StageShowLibMigratePHPClass::Safe_str_replace(' ','-',$seatingPlan[0]->seatingRef);
			
			// Add Timestamp to Template and then export it
			$html  = "<!-- Template Downloaded - ".date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT)." -->\n";
			$html .= $seatsTemplate;
			
			$exportObj = new StageShowLibExportAdminClass($myDBaseObj);
			
			$exportObj->fileExtn = $fileExtn;
			$exportObj->fileName = $fileName;
			
			// FUNCTIONALITY: Export File
			$exportObj->Export('text/part-html', 'utf-8', $html);
		}
	}
}
