<?php
/*
Description: Core Library Export functions

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

if (!class_exists('StageShowLibExportAdminClass'))
{

	class StageShowLibExportAdminClass // Define class
	{
		var $myDBaseObj;
		var $fieldNames;

		var $fileName;
		var $fileExtn = 'txt';

		var $exportData = '';
		
		function __construct($myDBaseObj) //constructor
		{
			$this->myDBaseObj = $myDBaseObj;

			$this->fileName = 'stageshow';
			$this->DispositionExtras = "";	// was attachment;
		}

		function Export($application, $charset = 'utf-8', $content = '')
		{
			$this->myDBaseObj->CheckAdminReferer('stageshowlib_export.php');
			$this->output_downloadHeader($application, $charset);
			StageShowLibEscapingClass::Safe_EchoHTML($content);
		}

		function GetFields()
		{
			return array();
		}

		function SelectFields($dbFields)
		{
			$fieldNames = $this->GetFields();

			if ($dbFields != '')
			{
				$validDbFields = explode(',', $dbFields);
				$ourFieldNames = array();
				foreach ($validDbFields as $validDbField)
				{
					$ourFieldNames[$validDbField] = $fieldNames[$validDbField];
				}
				$fieldNames = $ourFieldNames;
			}

			return $fieldNames;
		}

		function DecodeField($fieldID, $fieldVal, $dbEntry)
		{
			return $fieldVal;
		}

		function header($content)
		{
			if ( $this->myDBaseObj->isDbgOptionSet('Dev_ShowSQL')
				|| $this->myDBaseObj->isDbgOptionSet('Dev_ShowDBOutput') )
			{
				if (!isset($this->doneFirstHeader)) 
					StageShowLibEscapingClass::Safe_EchoHTML("SQL Debug Output Enabled - HTML Headers output as text<br>\n");
				StageShowLibEscapingClass::Safe_EchoHTML($content."<br>\n");
				$this->doneFirstHeader = true;
			}
			else
				header($content);
		}

		function output_downloadHeader($application, $charset = 'utf-8')
		{
			$this->header( 'Content-Description: File Transfer' );
			$this->header( 'Content-Disposition:'.$this->DispositionExtras.' filename=' . $this->fileName.'.'. $this->fileExtn );
			$this->header( "Content-Type: $application; charset=$charset" );
		}

		function exportDB($dbList, $exportHTML = false)
		{
			$this->exportData .= $this->GetExport($dbList, $exportHTML);
			StageShowLibEscapingClass::Safe_EchoHTML($this->exportData);
		}

		function GetExport($dbList, $exportHTML = false)
		{
			$doneHeader = false;
			$header = '';
			$line = '';

			foreach($dbList as $dbEntry)
			{
				if ($exportHTML)
					$line .= '"';

				if (isset($this->fieldNames)) $keysList = $this->fieldNames;
				else $keysList = $dbEntry;

				foreach ($keysList as $key => $unused)
				{
					if (!property_exists($dbEntry, $key))
						continue;

					if (isset($this->fieldNames[$key]) && ($this->fieldNames[$key] == ''))
						continue;

					$option = $dbEntry->$key;

					if (!$doneHeader)
					{
						if (!$exportHTML && isset($this->fieldNames[$key]))
							$header .= $this->fieldNames[$key];
						else
							$header .= $key;
						$header .= "\t";
					}

					$option = StageShowLibMigratePHPClass::Safe_str_replace("\r\n",",",$option);
					$option = StageShowLibMigratePHPClass::Safe_str_replace("\r",",",$option);
					$option = StageShowLibMigratePHPClass::Safe_str_replace("\n",",",$option);	// Remove any CRs in the db entry ... i.e. in Address Fields

					$option = $this->DecodeField($key, $option, $dbEntry);
					$line .= "$option\t";
				}

				$doneHeader = true;

				if ($exportHTML)
					$line .= '",';
				$line .= "\n";
			}

			if ($exportHTML)
			{
				$header = '"'.$header.'",';
			}
			$header .= "\n";

			return $header.$line;
		}


	}
}



